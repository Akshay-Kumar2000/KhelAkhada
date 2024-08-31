<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LoveyCom\CashFree\PaymentGateway\Order;
use Paykun\Checkout\Payment;
use App\UserPayment;
use App\Transaction;
use App\User;
use App\Gateway;
use Auth;

class PaymentController extends Controller
{
    public function gateways(Request $request)
    {
        $gateways = Gateway::where('status', 1)->get();
        return response([
            'gateways' => $gateways,
            'success' => 1
        ], 200);
    }

    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $order_id = time() . '' . $user->id;

        $payment = UserPayment::create([
            'user_id' => $user->id,
            'order_id' => $order_id,
            'amount' => $request->amount,
            'tax' => $request->tax,
            'ip' => $request->ip(),
        ]);

        if ($payment) {
            return response([
                'order' => $payment,
                'message' => 'Order created succesfully!',
                'success' => 1
            ], 200);
        }

        return response([
            'message' => 'Sorry! Failed to create order!',
            'success' => 0
        ], 400);

    }

    public function paymentProcessing(Request $request)
    {
        $order = UserPayment::where('order_id', $request->order_id)->first();
        $user = User::find($order->user_id);
        switch ($request->gateway_id) {
            case (1):
                $payment_link = $this->cashfreeGateway($order, $user);
                return redirect($payment_link);

            case (2):
                $this->paykunGateway($order, $user);
        }

        return response([
            'message' => 'Sorry! We cannot process your request!',
            'success' => 0
        ], 400);

    }

    private function cashfreeGateway($data, $user)
    {
        //echo "<pre>";print_r($user);die;
        $order = new Order();
        $od["orderId"] = $data->order_id;
        $od["orderAmount"] = ($data->amount + $data->tax);
        $od["orderNote"] = "Recharge";
        $od["customerPhone"] = $user->mobile;
        $od["customerName"] = $user->name;
        $od["customerEmail"] = $user->email;
        $od["returnUrl"] = "http://15.207.18.168/api/cashfree-gateway-success";
        //$od["notifyUrl"]      = "http://127.0.0.1:8000/order/success";

        $order->create($od);
        $link = $order->getLink($od['orderId']);

        return $link->paymentLink;
    }

    private function paykunGateway($data, $user)
    {
        $obj = new Payment('255634081703627', '394D9F815BB1E875AD8774144755B02A', 'DEAD5C63E6285A0BCC662ECC1D625C24', false, false);

        // Initializing Order
        $obj->initOrder($data->order_id, 'Recharge', ($data->amount + $data->tax), 'http://15.207.18.168/api/paykun-gateway-success ', 'http://15.207.18.168/api/paykun-gateway-success');

        // Add Customer
        $obj->addCustomer($user->name, $user->email, $user->mobile);
        echo $obj->submit();
        die('ll');
    }

    public function successCashfree(Request $request)
    {
        $data = $request->all();
        //echo "<pre>";print_r($data);die('k');
        $payment = UserPayment::where('order_id', $data['orderId'])->first();

        if ($payment->status == 0 && $data['txStatus'] == 'SUCCESS') {
            $amount = $data['orderAmount'] - $payment->tax;
            $payment->status = 1;
            $payment->gateway_id = 1;
            $payment->save();

            $txn = Transaction::create([
                'user_id' => $payment->user_id,
                'source_id' => $data['orderId'],
                'amount' => $amount,
                'status' => 'WALLET',
                'pdate' => date('Y-m-d'),
                'ip' => $request->ip()
            ]);

            if ($txn) {
                User::where('id', $payment->user_id)->increment('wallet_bal', $amount);
                return response([
                    'message' => 'Wallet recharge succesfully!',
                    'success' => 1
                ], 200);
            }

            return response([
                'message' => 'Sorry! Failed to recharge wallet!',
                'success' => 0
            ], 400);
        }

        return response([
            'message' => 'Sorry! Failed to recharge wallet!',
            'success' => 0
        ], 400);
    }

    public function successPaykun(Request $request)
    {
        $req = $request->all();
        //echo "<pre>";print_r($data);die('k');

        $paymentId = $req['payment-id'];
        $obj = new Payment('255634081703627', '394D9F815BB1E875AD8774144755B02A', 'DEAD5C63E6285A0BCC662ECC1D625C24', false, false);
        $transactionData = $obj->getTransactionInfo($paymentId);
        //echo "<pre>";print_r($transactionData['data']['transaction']);die();
        $order_id = $transactionData['data']['transaction']['order']['order_id'];
        $g_amount = $transactionData['data']['transaction']['order']['gross_amount'];

        $payment = UserPayment::where('order_id', $order_id)->first();

        if ($payment->status == 0 && $transactionData['data']['transaction']['status'] == 'Success') {
            $amount = $g_amount - $payment->tax;
            $payment->status = 1;
            $payment->gateway_id = 2;
            $payment->save();

            $txn = Transaction::create([
                'user_id' => $payment->user_id,
                'source_id' => $order_id,
                'amount' => $amount,
                'status' => 'WALLET',
                'pdate' => date('Y-m-d'),
                'ip' => $request->ip()
            ]);

            if ($txn) {
                User::where('id', $payment->user_id)->increment('wallet_bal', $amount);
                return response([
                    'message' => 'Wallet recharge succesfully!',
                    'success' => 1
                ], 200);
            }

            return response([
                'message' => 'Sorry! Failed to recharge wallet!',
                'success' => 0
            ], 400);
        }

        return response([
            'message' => 'Sorry! Failed to recharge wallet!',
            'success' => 0
        ], 400);
    }

    public function failPaykun(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
        die;
    }

    public function inAppSuccess(Request $request)
    {
        $order = UserPayment::where('order_id', $request->order_id)->first();
        if (isset($order) && $order->status == 0) {
            $order->gateway_id = $request->gateway_id;
            $order->status = 1;
            $order->gtw_txn_id = $request->gtw_txn_id;
            $order->gtw_receipt = $request->gtw_receipt;
            $order->save();

            $txn = Transaction::create([
                'user_id' => $order->user_id,
                'source_id' => $order->order_id,
                'amount' => $order->amount,
                'status' => 'WALLET',
                'pdate' => date('Y-m-d'),
                'ip' => $request->ip()
            ]);

            if ($txn) {
                User::where('id', $order->user_id)->increment('wallet_bal', $order->amount);
                return response([
                    'message' => 'Wallet recharge succesfully!',
                    'success' => 1
                ], 200);
            }

            return response([
                'message' => 'Sorry! Failed to recharge wallet!',
                'success' => 0
            ], 400);
        }

        return response([
            'message' => 'Sorry! Failed to recharge ios wallet!',
            'success' => 0
        ], 400);
    }

}

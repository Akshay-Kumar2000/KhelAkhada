<?php

namespace App\Http\Controllers\User;

use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use LoveyCom\CashFree\PaymentGateway\Order;
use App\PaymentOrder;
use App\UserData;
// use App\Log;

use Illuminate\Support\Facades\Log;
use App\Transaction;
use GuzzleHttp\Client;
use Paykun\Checkout\Payment;
use Carbon\Carbon;
use App\Setting;

class PaymentController
{
    public function paymentGatewayRes(Request $request)
    {
        $pCheck = PaymentOrder::where('order_id', $request['orderId'])->first();
        $user_id = $pCheck->user_id;
        $amount = $request['orderAmount'];
        $user_data = User::where('id', $user_id)->first();
        $wallet = $user_data->wallet;

        if ($request['txStatus'] == 'SUCCESS' && $pCheck->status == 0 && $amount == $pCheck->amount) {
            $txn = Transaction::create([
                'user_id' => $user_id,
                'source_id' => $request['orderId'],
                'amount' => $amount,
                'a_amount' => 0,
                'status' => 'Wallet',
                'remark' => 'Cashfree wallet recharge',
                'ip' => $request->ip(),
                'closing_balance' => $wallet + $amount,

            ]);

            if ($txn) {
                $pCheck->status = 1;
                $pCheck->save();

                User::where('id', $user_id)->increment('wallet', $amount);

                return redirect('dashboard')->with('payment_status', $request['txMsg']);
            }
        }

        if ($request['txStatus'] == 'FAILED') {
            return redirect('dashboard')->with('payment_status', $request['txMsg']);
        }

        die("Invalid request. <a href=" . route('dashboard') . ">Click here to go home</a>");
    }

    public function paymentGatewayResWebhook()
    {
        $pChecks = PaymentOrder::where('status', 0)->latest()->get();
        foreach ($pChecks as $row) {
            $dateAndTime = $row->created_at;
            $carbonDate = Carbon::parse($dateAndTime);

            $date = $carbonDate->format('d-m-Y');
            $orderid = $row->order_id;
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.ekqr.in/api/check_order_status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\r\n  \"key\": \"8e4fb829-a510-4353-92ef-4f671e02edad\",\r\n  \"client_txn_id\": \"$orderid\",\r\n  \"txn_date\": \"$date\"\r\n}",
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $request = json_decode($response);
                // return $response;
                $pCheck = PaymentOrder::where('id', $row->id)->first();
                $user_id = $pCheck->user_id;
                $amount = $request->data->amount;
                $user_data = User::where('id', $user_id)->first();
                $wallet = $user_data->wallet;
                if ($request->data->status == 'success') {
                    $txn = Transaction::create([
                        'user_id' => $user_id,
                        'source_id' => $request->data->client_txn_id,
                        'amount' => $amount,
                        'a_amount' => 0,
                        'status' => 'Wallet',
                        'remark' => 'UpiGateway wallet recharge',
                        'ip' => "000000",
                        'closing_balance' => $wallet + $amount,
                    ]);

                    if ($txn) {
                        $pCheck->status = 1;
                        $pCheck->save();
                        User::where('id', $user_id)->increment('wallet', $amount);
                        // return redirect('dashboard')->with('payment_status', $request['txMsg']);
                    }
                }
            }
        }
    }

    private function checkDepositStatus()
    {

        // Use 'field_value' instead of 'value' and ensure the correct key is being used
        $DepositStatusS = Setting::where('field_name', 'DepositStatusS')->value('field_value');

        if ($DepositStatusS == "no") {
            return redirect('/wallet')->with('error', 'Deposit currently paused due to some Bank issue, Please try after some time!');
        }
    }


    public function addMoney(Request $request)
    {
        $DepositStatus = Setting::where('field_name', 'DepositStatus')->first();
        $DepositStatus = $DepositStatus->field_value;

        if ($DepositStatus == "no") {
            return redirect('/wallet')->with('error', 'Withdrawal currently paused due to some Bank issue, Please try after some time!');
        }
        return view('user.add-money');
    }

    public function addMoneyChk(Request $request)
    {
        return view('user.add-money-chk');
    }


    public function createOrderChk(Request $request)
    {
        return redirect("https://google.com");
    }

    public function createOrdernew(Request $request)
    {
        // Validate the order amount
        $request->validate([
            'orderAmount' => 'required|numeric|gt:0|between:1,20000',
        ]);

        $amount = $request->orderAmount;

        // Refetch the authenticated user to get the latest data
        $user = Auth::user()->fresh();

        // Fetch KYC status from the user_data table
        $userKYCData = UserData::where('user_id', $user->id)->first();

        // Determine the KYC status
        if ($userKYCData) {
            $kycStatus = $userKYCData->verify_status == 1 ? 'completed' : 'pending';
        } else {
            $kycStatus = 'not available';
        }

        // Log the KYC status for debugging
        // \Log::info('KYC Status for User ID ' . $user->id . ': ' . $kycStatus);

        // Check if the user is attempting to deposit more than 500 without completing KYC
        // Check if the user is attempting to deposit more than 500 without completing KYC
        if ($kycStatus !== 'completed' && $amount > 500) {
            // Redirect back to the add-money page with an error message
            return redirect()->back()->with('error', 'Please complete your KYC to deposit more than 500.');
        }


        // Fetch the selected payment gateway
        $GatewayChoice_setting = Setting::find(7);
        $ChoicedGateway = $GatewayChoice_setting->field_value;

        // Process payment based on the selected gateway
        if ($ChoicedGateway == "mpay") {
            $gateway = 'AKDA_PhonePe';
            $user_id = $user->id;
            $order_id = $gateway . '-' . time() . '-' . $user_id;

            // Create a new payment order
            $order = PaymentOrder::create([
                'user_id' => $user_id,
                'order_id' => $order_id,
                'amount' => $request->orderAmount,
                'gateway' => $gateway,
                'ip' => $request->ip()
            ]);

            // Prepare the cURL request to the payment gateway
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://mothersolution.in/api/pg/phonepe/initiate',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "token": "$2y$10$5IaoAASmcK0JRWmp4obfSOvaHrFSFmvKU7WzMbqeTPFCVJN7TT7Ty",
                    "userid": "MP15751",
                    "amount": "' . $request->orderAmount . '",
                    "mobile": "' . $user->mobile . '",
                    "orderid": "' . $order_id . '",
                    "callback_url": "https://akadda.com"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'X-API-Key: $2y$10$5IaoAASmcK0JRWmp4obfSOvaHrFSFmvKU7WzMbqeTPFCVJN7TT7Ty'
                ),
            ));

            $respons = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($respons);
            if ($response->status == true) {
                echo "<script>window.location.href='" . $response->url . "'</script>";
                die();
            } else {
                echo $response->message;
                die('error');
            }
        }
        elseif ($ChoicedGateway == "upi") {
            $gateway = 'UPI-Gateway';
            $user_id = $user->id;
            $order_id = $gateway . '-' . time() . '-' . $user_id;

            // Create a new payment order
            $order = PaymentOrder::create([
                'user_id' => $user_id,
                'order_id' => $order_id,
                'amount' => $request->orderAmount,
                'gateway' => $gateway,
                'ip' => $request->ip()
            ]);

            // Prepare the cURL request to the payment gateway
            $postdata = [
                "loginid" => "9257024792",
                "apikey" => "7pacgmqbzx",
                "orderid" => $order_id,
                "amt" => $request->orderAmount,
                "trxnote" => $user->username,
                // "redirecturl" => "https://game.bottomfunnel.net/",
                // "mcallback_url" => "https://game.bottomfunnel.net/new-upi-gateway-response"
                // "redirecturl" => "http://192.168.29.247:8080/",
                // "mcallback_url" => "http://192.168.29.247:8080/new-upi-gateway-response"
                "redirecturl" => "https://akplayers.com/",
                "mcallback_url" => "https://akplayers.com/new-upi-gateway-response"
            ];

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://upipg.gtelararia.com/order/create",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postdata),
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            \Log::info("API Response when QR generated: " . $response);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $data = json_decode($response);
                if ($data->status == "success") {
                    return redirect($data->gotourl);
                } else {
                    return $data;
                }
            }
        }
        elseif ($ChoicedGateway == "phonepeupi") {
            $gateway = 'UPI-Gateway';
            $user_id = $user->id;
            $order_id = $gateway . '-' . time() . '-' . $user_id;
            $key = '8e4fb829-a510-4353-92ef-4f671e02edad';

            // Create a new payment order
            $order = PaymentOrder::create([
                'user_id' => $user_id,
                'order_id' => $order_id,
                'amount' => $request->orderAmount,
                'gateway' => $gateway,
                'ip' => $request->ip()
            ]);

            // Prepare the cURL request to the payment gateway
            $content = json_encode(
                array(
                    "key" => $key,
                    "client_txn_id" => $order_id,
                    "amount" => $request->orderAmount,
                    "p_info" => "Product Name",
                    "customer_name" => $user->username,
                    "customer_email" => "alshuindia@gmail.com",
                    "customer_mobile" => $user->mobile,
                    "redirect_url" => url('/'),
                    "udf1" => "user defined field 1",
                    "udf2" => "user defined field 2",
                    "udf3" => "user defined field 3",
                )
            );

            $url = "https://merchant.upigateway.com/api/create_order";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array("Content-type: application/json")
            );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($status != 200) {
                die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
            }
            curl_close($curl);

            $response = json_decode($json_response, true);

            if ($response["status"] == true) {
                echo "<script>window.location.href='" . $response["data"]["payment_url"] . "'</script>";
                die();
            } else {
                echo $response['msg'];
                die('error');
            }
        } else {
            return "Something went wrong!!";
        }
    }


    // using this for the upipayment status check callback
    public function upiStatusCheck() {
        $orders = PaymentOrder::where('status', 0)->get();
        \Log::info("Payment Orders Processing: " . $orders);
        foreach ($orders as $key => $value) {
            $payOrder = PaymentOrder::find($value->id);
            if (!empty($payOrder)) {
                \Log::info("Processing order ID: " . $payOrder->id);

                $client = new Client();
                $res = $client->request('GET', 'https://upipg.gtelararia.com/order/statuscheck.php?loginid=9257024792&apikey=7pacgmqbzx&request_id=' . $payOrder->order_id);

                if ($res->getStatusCode() == 200) {
                    $response_data = $res->getBody()->getContents();
                    $response = json_decode($response_data, true);
                    \Log::info("API Response: " . $response_data);

                    // Set the $user_id variable early
                    $user_id = $payOrder->user_id;
                    \Log::info("User ID: " . $user_id);

                    if ($response['status'] == 'success') {
                        $user_data = User::where('id', $user_id)->first();
                        \Log::info("User Wallet Before: " . $user_data->wallet);

                        $wallet = $user_data->wallet;
                        $txn = Transaction::create([
                            'user_id' => $user_id,
                            'source_id' => $payOrder->order_id,
                            'amount' => $payOrder->amount,
                            'a_amount' => 0,
                            'status' => 'Wallet',
                            'remark' => 'Upigateway wallet recharge',
                            'ip' => "127.0.0.1",
                            'closing_balance' => $wallet + $payOrder->amount,
                        ]);

                        $payOrder->status = 1;
                        \Log::info("Transaction Created: " . $txn->id);
                    } elseif ($response['status'] == 'fail') {
                        $payOrder->status = 2;
                        \Log::info("Payment failed for order ID: " . $payOrder->id);
                    }

                    $payOrder->save();
                    \Log::info("Order status updated: " . $payOrder->status);

                    // Only update wallet balance if the payment was successful
                    if ($payOrder->status == 1) {
                        User::where('id', $user_id)->increment('wallet', $payOrder->amount);
                        \Log::info("User Wallet After: " . ($wallet + $payOrder->amount));
                    }
                }
            }
        }
    }

    public function upitel_recharge_status(Request $request)
    {
        Log::info(json_encode($request->all()));
        //  $initialdaya = json_encode($request->all());
        $json_string = file_get_contents('php://input');
        $initialdaya = json_decode($json_string, true);
        Log::info(json_encode($initialdaya));
        // return $initialdaya;
        // PaymentOrder::where('id',1)->update(['amount'=>$json_string]);
        if (!isset($initialdaya['status']) || $initialdaya['status'] != "success") {
            Log::info("PaymentController:721 => Status is Not Success");
            return "Status is Not Success";
        }
        $dec = openssl_decrypt($initialdaya['data'], 'AES-128-ECB', "aGRjdXB4ZWcybQ==");
        $resp = json_decode($dec, true);
        // return $dec;
        if (!$resp) {
            Log::info("PaymentController:728 => No data in Encode data");
            return "No data in Encode data";
        }
        // return $resp;
        $cust_mobile = $resp['cust_mobile'];
        $amt = $resp['amt'];
        $utr = $resp['utr'];
        $trxnote = $resp['trxnote'];
        $order_date = $resp['order_date'];
        $orderid = $resp['orderid'];

        $pCheck = PaymentOrder::where('order_id', $orderid)->first();
        // 		return $pCheck;
// 		$orderData	=	$this->checkOrderStatus($orderid,$pCheck->created_at);
        //echo "<pre>";print_r($pCheck);die;
        $user_id = $pCheck->user_id;
        //$amount		=	$request['orderAmount'];
        //if($pCheck->status == 0 && $amount == $pCheck->amount){ //die('kk');
        $user_data = User::where('id', $user_id)->first();
        $wallet = $user_data->wallet;

        // 		if($pCheck->status == 0 && $pCheck->amount == $orderData['amount'] && $orderData['status'] == 'success'){ //die('kk');
        $txn = Transaction::create([
            'user_id' => $user_id,
            'source_id' => $orderid,
            'amount' => $pCheck->amount,
            'a_amount' => 0,
            'status' => 'Wallet',
            'remark' => 'Upigateway wallet recharge',
            'ip' => $request->ip(),
            'closing_balance' => $wallet + $pCheck->amount,

        ]);

        if ($txn) {
            $pCheck->status = 1;
            $pCheck->save();

            User::where('id', $user_id)->increment('wallet', $pCheck->amount);
            return "Successfull";
            // return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
        }
        // 		}

        // if($request['txStatus'] ==	'FAILED'){
        // 	return redirect('dashboard')->with('payment_status', $request['txMsg']);
        // }

        die("Invalid request. <a href=" . route('challenges') . ">Click here to go home</a>");
    }
    public function upigateway_recharge_callback(Request $request)
    {
        // $l = new Log;
        // $l->value = json_encode($request->all());
        // $l->save();
        // return $request->all();
        $trnstatus = $request->status;
        $trn = $request->client_txn_id;
        if ($trn && $trnstatus == "success") {
            $pCheck = PaymentOrder::where('order_id', $trn)->where('status', '!=', 1)->first();
            $exist = Transaction::where('source_id', $trn)->count();
            if ($exist > 0) {
                return response()->json(array("status" => 0, "message" => "Dublicate sessions!"));
            }
            if ($pCheck) {
                $user_id = $pCheck->user_id;
                $user_data = User::where('id', $user_id)->first();
                $wallet = $user_data->wallet;
                $txn = Transaction::create([
                    'user_id' => $user_id,
                    'source_id' => $trn,
                    'amount' => $pCheck->amount,
                    'a_amount' => 0,
                    'status' => 'Wallet',
                    'remark' => 'Phonepe wallet recharge',
                    'ip' => $request->ip(),
                    'closing_balance' => $wallet + $pCheck->amount,
                ]);
                if ($txn) {
                    $pCheck->status = 1;
                    $pCheck->save();

                    User::where('id', $user_id)->increment('wallet', $pCheck->amount);
                    return response()->json(array("status" => 1));
                    // return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
                }
            }
        } else {
            return response()->json(array("status" => 0, "message" => $request->code));
        }
    }
    public function cashfree_recharge_callback(Request $request)
    {
        $event = $request->type;
        // return $request->all();
        $trnstatus = $request->data['payment']['payment_status'];
        $trn = $request->data['order']['order_id'];
        $trn_razorpay = $request->data['payment']['bank_reference'];
        $amount_razorpay = $request->data['payment']['payment_amount'];
        // $l = new Log;
        // $l->value = json_encode($request->all());
        // $l->save();

        if ($event == "PAYMENT_SUCCESS_WEBHOOK" && $trn && $trnstatus == "SUCCESS") {
            $pCheck = PaymentOrder::where('order_id', $trn)->where('status', '!=', 1)->first();
            $exist = Transaction::where('source_id', $trn)->count();
            if ($exist > 0) {
                return response()->json(array("status" => 0, "message" => "Dublicate sessions!"));
            }
            if ($pCheck) {
                $user_id = $pCheck->user_id;
                $user_data = User::where('id', $user_id)->first();
                $wallet = $user_data->wallet;
                $txn = Transaction::create([
                    'user_id' => $user_id,
                    'source_id' => $trn_razorpay,
                    'amount' => $amount_razorpay,
                    'a_amount' => 0,
                    'status' => 'Wallet',
                    'remark' => 'Cashfree wallet recharge',
                    'ip' => $request->ip(),
                    'closing_balance' => $wallet + $amount_razorpay,
                ]);
                if ($txn) {
                    $pCheck->amount = $amount_razorpay;
                    $pCheck->order_id = $trn_razorpay;
                    $pCheck->status = 1;
                    $pCheck->save();

                    User::where('id', $user_id)->increment('wallet', $amount_razorpay);
                    return response()->json(array("status" => 1));
                }
            }
        } else {
            return response()->json(array("status" => 0, "message" => $request->code));
        }
    }
    public function Mpay_recharge_callback(Request $request)
    {
        // $l = new Log;
        // $l->value = json_encode($request->all());
        // $l->save();
        // return $request->all();
        $trnstatus = $request->status;
        $trn = $request->client_txn_id;
        if ($trn && $trnstatus == "success") {
            $pCheck = PaymentOrder::where('order_id', $trn)->where('status', '!=', 1)->first();
            $exist = Transaction::where('source_id', $trn)->count();
            if ($exist > 0) {
                return response()->json(array("status" => 0, "message" => "Dublicate sessions!"));
            }
            if ($pCheck) {
                $user_id = $pCheck->user_id;
                $user_data = User::where('id', $user_id)->first();
                $wallet = $user_data->wallet;
                $txn = Transaction::create([
                    'user_id' => $user_id,
                    'source_id' => $trn,
                    'amount' => $pCheck->amount,
                    'a_amount' => 0,
                    'status' => 'Wallet',
                    'remark' => 'Phonepe wallet recharge',
                    'ip' => $request->ip(),
                    'closing_balance' => $wallet + $pCheck->amount,
                ]);
                if ($txn) {
                    $pCheck->status = 1;
                    $pCheck->save();

                    User::where('id', $user_id)->increment('wallet', $pCheck->amount);
                    return response()->json(array("status" => 1));
                    // return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
                }
            }
        } else {
            return response()->json(array("status" => 0, "message" => $request->status));
        }
    }


    public function recharge_status(Request $request)
    {
        $l = new Log;
        $l->value = json_encode($request->all());
        $l->save();
        $trn = $request->data['OrderKeyId'];
        $trnstatus = $request->data['OrderPaymentStatusText'];
        if ($trn && $trnstatus == "Paid") {
            $pCheck = PaymentOrder::where('order_id', $trn)->where('status', '!=', 1)->first();
            $exist = Transaction::where('source_id', $trn)->count();
            if ($exist > 0) {
                return redirect('challenges')->with('payment_status', "Dublicate sessions!");
            }
            if ($pCheck) {
                $user_id = $pCheck->user_id;
                $user_data = User::where('id', $user_id)->first();
                $wallet = $user_data->wallet;
                $txn = Transaction::create([
                    'user_id' => $user_id,
                    'source_id' => $trn,
                    'amount' => $pCheck->amount,
                    'a_amount' => 0,
                    'status' => 'Wallet',
                    'remark' => 'Phonepe wallet recharge',
                    'ip' => $request->ip(),
                    'closing_balance' => $wallet + $pCheck->amount,
                ]);
                if ($txn) {
                    $pCheck->status = 1;
                    $pCheck->save();

                    User::where('id', $user_id)->increment('wallet', $pCheck->amount);

                    return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
                }
            }
        } else {
            return redirect('challenges')->with('payment_status', $request->code);
        }
    }
    public function upiGatewayRes(Request $request)
    {
        $pCheck = PaymentOrder::where('order_id', $request->client_txn_id)->first();
        $orderData = $this->checkOrderStatus($request->client_txn_id, $pCheck->created_at);
        //echo "<pre>";print_r($pCheck);die;
        $user_id = $pCheck->user_id;
        //$amount		=	$request['orderAmount'];
        //if($pCheck->status == 0 && $amount == $pCheck->amount){ //die('kk');
        $user_data = User::where('id', $user_id)->first();
        $wallet = $user_data->wallet;

        if ($pCheck->status == 0 && $pCheck->amount == $orderData['amount'] && $orderData['status'] == 'success') { //die('kk');
            $txn = Transaction::create([
                'user_id' => $user_id,
                'source_id' => $request->client_txn_id,
                'amount' => $pCheck->amount,
                'a_amount' => 0,
                'status' => 'Wallet',
                'remark' => 'Upigateway wallet recharge',
                'ip' => $request->ip(),
                'closing_balance' => $wallet + $pCheck->amount,

            ]);

            if ($txn) {
                $pCheck->status = 1;
                $pCheck->save();

                User::where('id', $user_id)->increment('wallet', $pCheck->amount);

                return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
            }
        }

        // if($request['txStatus'] ==	'FAILED'){
        // 	return redirect('dashboard')->with('payment_status', $request['txMsg']);
        // }

        die("Invalid request. <a href=" . route('challenges') . ">Click here to go home</a>");
    }

    public function upiGatewayResPost(Request $request)
    {
        $pCheck = PaymentOrder::where('order_id', $request->client_txn_id)->first();
        $orderData = $this->checkOrderStatus($request->client_txn_id, $pCheck->created_at);
        //echo "<pre>";print_r($pCheck);die;
        $user_id = $pCheck->user_id;
        //$amount		=	$request['orderAmount'];
        //if($pCheck->status == 0 && $amount == $pCheck->amount){ //die('kk');
        $user_data = User::where('id', $user_id)->first();
        $wallet = $user_data->wallet;

        if ($pCheck->status == 0 && $pCheck->amount == $orderData['amount'] && $orderData['status'] == 'success') { //die('kk');
            $txn = Transaction::create([
                'user_id' => $user_id,
                'source_id' => $request->client_txn_id,
                'amount' => $pCheck->amount,
                'a_amount' => 0,
                'status' => 'Wallet',
                'remark' => 'Cashfree wallet recharge',
                'ip' => $request->ip(),
                'closing_balance' => $wallet + $pCheck->amount,

            ]);

            if ($txn) {
                $pCheck->status = 1;
                $pCheck->save();

                User::where('id', $user_id)->increment('wallet', $pCheck->amount);

                return redirect('challenges')->with('payment_status', "Wallet rechaged successfully!");
            }
        }

        // if($request['txStatus'] ==	'FAILED'){
        // 	return redirect('dashboard')->with('payment_status', $request['txMsg']);
        // }

        die("Invalid request. <a href=" . route('challenges') . ">Click here to go home</a>");
    }

    private function checkOrderStatus($order_id, $date)
    {
        $client = new Client();
        $key = '08b3ae69-431b-4da8-b05e-a5669083d839';
        $res = $client->request('POST', 'https://merchant.upigateway.com/api/check_order_status', [
            'form_params' => [
                'key' => $key,
                'client_txn_id' => $order_id,
                'txn_date' => date('d-m-Y', strtotime($date)),
            ]
        ]);

        if ($res->getStatusCode() == 200) { // 200 OK
            $response_data = $res->getBody()->getContents();
        }
        $response = json_decode($response_data, true);

        return $response['data'];
        //echo "<pre>";print_r($response['data']);die;
    }

    protected function orderCashfree($orderAmount, $order_id)
    {
        $order = new Order();
        $od["orderId"] = $order_id;
        $od["orderAmount"] = $orderAmount;
        $od["orderNote"] = "Recharge";
        $od["customerPhone"] = Auth::user()->mobile;
        $od["customerName"] = Auth::user()->name;
        $od["customerEmail"] = 'bygame47@gmail.com';
        $od["returnUrl"] = route('payment-gateway-cashfree-res');
        $od["notifyUrl"] = route('payment-gateway-cashfree-res');
        //$od["notifyUrl"]      = "http://127.0.0.1:8000/order/success";

        $order->create($od);
        $link = $order->getLink($od['orderId']);//echo $link->paymentLink;die;
        return redirect()->to($link->paymentLink)->send();
    }

}

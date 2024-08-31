<?php

namespace App\Http\Controllers\User;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use LoveyCom\CashFree\PaymentGateway\Order;
use App\PaymentOrder;
use App\Transaction;
use App\UserSetting;

use Paykun\Checkout\Payment;

class WebhookController
{
	public function paymentGatewayRes(Request $request)
    { 	
		$pCheck		=	PaymentOrder::where('order_id',$request['orderId'])->first();
		$user_id	=	$pCheck->user_id;
		$amount		=	$request['orderAmount'];
		$user_data = User::where('id', $user_id)->first();
		$wallet = $user_data->wallet;
		
		if($request['txStatus'] ==	'SUCCESS' && $pCheck->status == 0 && $amount == $pCheck->amount){
			$txn	=	Transaction::create([
				'user_id'	=>	$user_id,
				'source_id'	=>	$request['orderId'],
				'amount'	=>	$amount,
				'a_amount'	=>	0,				
				'status'	=>	'Wallet',
				'remark'	=>	'Cashfree wallet recharge',
				'ip'		=>	$request->ip(),
				'closing_balance' =>  $wallet+$amount,
       
			]);

			if($txn){
				$pCheck->status 	=	1;
				$pCheck->save() ;

				UserSetting::where('user_id',$user_id)->increment('wallet',$amount);

				return redirect('dashboard')->with('payment_status', $request['txMsg']);
			}
		}

		if($request['txStatus'] ==	'FAILED'){
			return redirect('dashboard')->with('payment_status', $request['txMsg']);
		}
		
		die('Access denied!');
	}
	 
    public function addMoney(Request $request){
		return view('user.add-money');
	}
	
	public function createOrder(Request $request)
    {  
		$request->validate([
            'orderAmount' => 'required|numeric|gt:0|between:10,20000',
        ]);
        
		$gateway	=	env('PAYMENT_GATEWAY');
		$user_id	=	Auth::user()->id;
		$order_id	=	env('PAYMENT_GATEWAY').'-'.time().'-'.$user_id;
		$order		=	PaymentOrder::create([
			'user_id'	=>	$user_id,
			'order_id'	=>	$order_id,
			'amount'	=>	$request->orderAmount,
			'gateway'	=>	$gateway,
			'ip'		=>	$request->ip()
		]);

		if($gateway	==	'Paykun' && $order){
			$this->orderPaykun($request->orderAmount,$order_id);
		}

		if($gateway	==	'Cashfree' && $order){
			$this->orderCashfree($request->orderAmount,$order_id);
		}		
		
    }
    
	public function paymentGatewayPaykunPostSuccess(Request $request)
    {  
		$req	=	$request->all();		
		$paymentId	=	$req['payment-id'];
		$obj = new Payment('152026487994391', '6879E4B179F251C34A420EAEC5AD69CB', '297C14B55661148EB5F28571A2082B5C',false, false);
		//$obj = new Payment(env('PAYKUN_MERCHANT_ID'), env('PAYKUN_ACCESS_TOKEN'), env('PAYKUN_KEY_SECRET'), true, false);
		$transactionData = $obj->getTransactionInfo($paymentId);
		//echo "<pre>";print_r($transactionData);die;
		$order_id		=	$transactionData['data']['transaction']['order']['order_id'];
		
		$pCheck		=	PaymentOrder::where('order_id',$order_id)->first();
		$user_id	=	Auth::user()->id;
		$amount		=	$transactionData['data']['transaction']['order']['gross_amount'];
		if($transactionData['data']['transaction']['status'] ==	'Success' && $pCheck->status == 0 && $amount == $pCheck->amount){
			$user_data = User::where('id', Auth::user()->id)->first();
			$wallet = $user_data->wallet;
		
			$txn	=	Transaction::create([
				'user_id'	=>	$user_id,
				'source_id'	=>	$order_id,
				'amount'	=>	$amount,
				'a_amount'	=>	0,				
				'status'	=>	'Wallet',
				'remark'	=>	'Paykun wallet recharge',
				'ip'		=>	$request->ip(),
				'closing_balance' =>  $wallet+$amount,
              
			]);

			if($txn){
				$pCheck->status 	=	1;
				$pCheck->save() ;

				UserSetting::where('user_id',$user_id)->increment('wallet',$amount);

				return redirect('dashboard')->with('payment_status', "Recharge successfully!");
			}
		}
		
		echo "Invalid request!"; die;
    }

	public function paymentGatewayPaykunPostFail(Request $request)
    { 
		return redirect('dashboard')->with('payment_status', "Connection Error!");
    }

	protected function orderPaykun($orderAmount,$order_id){
		$obj = new Payment(env('PAYKUN_MERCHANT_ID'),env('PAYKUN_ACCESS_TOKEN'),env('PAYKUN_KEY_SECRET'),false, false);
		//$obj = new Payment(env('PAYKUN_MERCHANT_ID'), env('PAYKUN_ACCESS_TOKEN'), env('PAYKUN_KEY_SECRET'), true, false);
		
		// Initializing Order
		$obj->initOrder($order_id, 'Purchase', $orderAmount, route('payment-gateway-paykun-ok').' ',  route('payment-gateway-paykun-fail'));
		
		// Add Customer
		$obj->addCustomer(Auth::user()->name, 'bygame47@gmail.com', Auth::user()->mobile);		
		echo $obj->submit(); 
	}

	protected function orderCashfree($orderAmount,$order_id)
    { 
		$order                  = new Order();
        $od["orderId"]          = $order_id;
        $od["orderAmount"]      = $orderAmount ;
        $od["orderNote"]        = "Recharge";
        $od["customerPhone"]    = Auth::user()->mobile;
        $od["customerName"]     = Auth::user()->name;
        $od["customerEmail"]    = 'bygame47@gmail.com';
        $od["returnUrl"]        = route('payment-gateway-cashfree-res');
        //$od["notifyUrl"]      = "http://127.0.0.1:8000/order/success";
       
        $order->create($od);        
        $link = $order->getLink($od['orderId']);//echo $link->paymentLink;die;
        return redirect()->to($link->paymentLink)->send();
    }
	
}

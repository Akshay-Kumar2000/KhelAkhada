<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Transaction;
use App\PaymentOrder;
use Hash;

class HomeController extends Controller
{
    
    
    public function index()
    {
        
        return view('home');
    }
	 public function request_response(Request $request){
         
     
     //  $request = file_get_contents("php://input");
	//	$request = json_decode($request);
		$request2 = json_encode($_GET);

	//	file_put_contents('./log_log', $request, FILE_APPEND);
		\Log::channel('logo')->info($request2);
	$_GET['UserTxnId'] = (int) str_replace('ATC','',$_GET['UserTxnId']);
	$txt_no = $_GET['UserTxnId'];
	    $amount = $_GET['amount'];
	     	$user_data = User::where('id', $_GET['UserTxnId'])->first();
					
				    	$new_amount = $amount;
				    	$user_id = $_GET['UserTxnId'];
				    	
				    	
				    		$order		=	PaymentOrder::create([
                			'user_id'	=>	$user_id,
                			'order_id'	=>	time(),
                			'amount'	=>	$new_amount,
                			'gateway'	=>	'MyPay',
                			'ip'		=>	'0'
                		]);
				/*	 $Payment = new Payment;
					 $Payment->user_id = $user_id;
					 $Payment->vplay_id = 0;
					  $Payment->mobile = $user_data->mobile;
					   $Payment->amount = $new_amount;
					  $Payment->order_token = time();
					 $Payment->status = "PAID";
					 $Payment->save();
                    */
						$user_data = User::where('id', $_GET['UserTxnId'])->first();
						$wallet = $user_data->wallet;
						$new_amount = $amount;
					
			 
					 $user  = User::where('id', $user_id)->first();
					 $user->wallet  = $wallet + $new_amount;
					 $user->save();
					 
					 
					 
				 		$txn	=	Transaction::create([
            				'user_id'	=>	$user_id,
            				'source_id'	=>	0,
            				'amount'	=>	$new_amount,
            				'a_amount'	=>	0,				
            				'status'	=>	'Wallet',
            				'remark'	=>	'mypaygateway wallet recharge',
            				'ip'		=>	'0',
            				'closing_balance' =>  $wallet+$new_amount
                   
            			]);
					
					/*
					 $Trans_hist = new TransactionHistory;
					 $Trans_hist->user_id = $user_id;
					 $Trans_hist->payment_id = $order->id;
					 $Trans_hist->order_id = time();
					 $Trans_hist->day = date('d');
					 $Trans_hist->month = date('M');
					 $Trans_hist->year = date('Y');
					 $Trans_hist->paying_time = date('h:i A');
					 $Trans_hist->amount = $new_amount;
					 $Trans_hist->add_or_withdraw = 'add';
					 $Trans_hist->closing_balance = $wallet+$new_amount;
					 $Trans_hist->save();
					 */
					 echo "OK";
					   /* echo json_encode(array('success'=>'true','msg'=>'Transaction Successfull !!'));
	                  die;*/
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        return view('clear-cache');
    }
}

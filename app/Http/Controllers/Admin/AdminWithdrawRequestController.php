<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\WithdrawRequest;
use App\User;

class AdminWithdrawRequestController extends Controller
{
    public function index(Request $request){
        $search='';
        if($request->search){
            $search     =  $request->search;
            $requests   = WithdrawRequest::select('withdraw_requests.*','users.username')->with('playername')
                        ->leftJoin('users','withdraw_requests.user_id','=','users.id')
                        ->where('users.username','LIKE','%'.$search.'%')
                        ->latest()->paginate(2000);
            $requests->appends(['search' => $search]);
        }else{
            $requests       =   WithdrawRequest::with('playername')->latest()->paginate(2000);
        }

        //echo "<pre>";print_r($requests);die;
        return view('admin/withdraw/requests',compact('requests','search'));
    }
    public function sendpayment($amount,$vpa,$orderid,$name){
        $curl = curl_init();
        $json_string = '{
            "APIID": "API1004",
            "Token": "6091fba5-1c3e-47ff-b9dc-0b784818b08b",
            "MethodName": "upitransfer",
            "OrderID": "'.$orderid.'",
            "Name": "'.$name.'",
            "Amount": "'.$amount.'",
            "vpa": "'.$vpa.'"
        }';
// echo $json_string;
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://ibrpay.com/api/UPITransferLive.aspx",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $json_string,
          CURLOPT_HTTPHEADER => [
            "Accept: */*",
            "Content-Type: application/json"
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return array('status'=>false,'data'=>json_decode($err));
        } else {
            return array('status'=>true,'data'=>json_decode($response));
        }
    }
    public function checkUpi($newupiid){
        $url = base64_decode('aHR0cHM6Ly93d3cucGF5bmltby5jb20vYXBpL0NvbW1vbkFQSS9WUEFWYWxpZGF0aW9u');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
           "Accept: */*",
           "Accept-Language: en-US,en;q=0.9",
           "Connection: keep-alive",
           "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
           "Origin: ".base64_decode('aHR0cHM6Ly93d3cudHBzbC1pbmRpYS5pbg=='),
           "Referer: ".base64_decode('aHR0cHM6Ly93d3cudHBzbC1pbmRpYS5pbg=='),
           "Sec-Fetch-Dest: empty",
           "Sec-Fetch-Mode: cors",
           "Sec-Fetch-Site: cross-site",
           "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
            'sec-ch-ua: "Not_A Brand";v="99", "Google Chrome";v="109", "Chromium";v="109"',
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: 'Windows'",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
         $data = '{"vpa_id":"'.$newupiid.'",'.base64_decode('Im1lcmNoYW50VHJhbklkIjoiMjI4Njc1OCIsIm1lcmNoYW50Q29kZSI6IkwyMzM0NDci').'}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        return curl_exec($curl);
        curl_close($curl);
    }
    public function acceptRequestMannual(Request $request , $uid){
        $data        =  WithdrawRequest::find($uid);
        $transaction = Transaction::where('id',$data->tid)->update(["status"=>"Withdraw"]);
        $data->status   =   'Paid';
        $transaction = Transaction::where('id',$data->tid)->update(["status"=>"Withdraw"]);
        $data->save();
        // return $transaction;
        if($transaction){
            return redirect('admin\withdraw-requests')->with('success', 'Withdraw request paid successfully!');
        }else{
            return redirect('admin\withdraw-requests')->with('error', 'Failed to paid Withdraw request! Try again.');
        }
    }
    public function acceptRequest(Request $request , $uid){
        $data        =  WithdrawRequest::find($uid);
        $checkUPI = json_decode($this->checkUpi($data->upi));
        if($data->upi == '' || $data->upi == null){
            return redirect('admin\withdraw-requests')->with('error', 'Upi Id is null!');
        }
        if(!$checkUPI->status){
            return redirect('admin\withdraw-requests')->with('error', 'Upi id incorrect');
        }
        $name = $checkUPI->message;
        //Order id
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $randomString = '';

        for ($i = 0; $i < 35; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        // $data->amount = 1;
        //Order id
        $response = $this->sendpayment($data->amount,$data->upi,$randomString.$data->id,$name);
        // return $response['data']->status;
        $GatewayPitput = $response['data'];
        if($GatewayPitput->status == "failed"){
            return redirect('admin\withdraw-requests')->with('error', $GatewayPitput->mess);
        }
        if($GatewayPitput->status == "success"){
            $transaction = Transaction::where('id',$data->tid)->update(["status"=>"Withdraw"]);
            // $data->status   =   'Processing';
            // $data->save();
            return redirect('admin\withdraw-requests')->with('success', 'Withdraw request paid successfully!');
        }
    }
    public function acceptRequestStatusWithdrawal(Request $request){
        $data = json_encode($request->all());
        if($request->status == "success"){
            // return $request->data['OrderID'];
            $id = $request->data['OrderID'];
            $uid = substr($id,35);
            $data        =  WithdrawRequest::find($uid);
            $data->status   =   'Paid';
            $transaction = Transaction::where('id',$data->tid)->update(["response"=>$data,"status"=>"Withdraw"]);
            $data->save();
        }else{
        $transaction = Transaction::where('id',569)->update(["response"=>$data]);
        }
    }


    public function declineRequest(Request $request, $uid){
        $data        =  WithdrawRequest::find($uid);

        if($data->status == 'Unpaid'){
            $data->status   =   'Declined';
            $data->save();
            $walletData =   User::find($data->user_id);

            $transaction = Transaction::create([
                'user_id'           => $data->user_id,
                'source_id'         => $uid,
                'amount'            => $data->amount,
                'status'            => 'Refund',
                'remark'            => 'Request declined by admin',
                'ip'                =>  $request->ip(),
                'closing_balance' =>  $walletData->wallet+$data->amount,
            ]);

            $walletData->increment('win_amount',$data->amount);
            $walletData->increment('wallet',$data->amount);
            $transaction = Transaction::where('id',$data->tid)->update(["status"=>"Withdraw_cancel"]);
            return redirect('admin\withdraw-requests')->with('success', 'Withdraw request declined successfully!');
        }
        return redirect('admin\withdraw-requests')->with('error', 'Selected request approved already!');

    }

}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\WithdrawRequest;
use App\User;
use App\Setting;
use App\Challenge;
use App\Transaction;
use App\UserBank;
use App\UserData;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WithdrawRequestController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $sett = Setting::where('field_name','WithdrawalTimer')->first();
        $WithdrawalStatus = Setting::where('field_name','WithdrawalStatus')->first();
        $WithdrawalStatusS = $WithdrawalStatus->field_value;
        if($WithdrawalStatusS == "no"){
            return redirect('/wallet')->with('error','Withdrawal currently pause due to some Bank issue, Please try after sometime!');
        }
        $existdata = Transaction::where('user_id',$user_id)->where(function($query){
            return $query
            ->where('status','Withdrawing')
            ->orWhere('status','Withdraw');
        })->where('created_at', '>=', now()->subMinutes($sett->field_value))->orderBy('id','desc')->first();
        // return $existdata;
        if($existdata){
        $Created_atLastData = $existdata->created_at;
        $RemainMinutesconst = $sett->field_value;
        // Convert the created_at timestamp to a Carbon instance
        $lastCreatedAt = Carbon::parse($Created_atLastData);

        // Get the current time
        $currentTime = Carbon::now();

        // Calculate the difference in seconds
        $elapsedTimeInSeconds = $lastCreatedAt->diffInSeconds($currentTime);

        // Convert elapsed time to hours, minutes, and seconds
        $elapsedHours = floor($elapsedTimeInSeconds / 3600);
        $elapsedMinutes = floor(($elapsedTimeInSeconds % 3600) / 60);
        $elapsedSeconds = $elapsedTimeInSeconds % 60;
                // Calculate the remaining time in seconds
        $remainingTimeInSeconds = ($RemainMinutesconst * 60) - $elapsedTimeInSeconds;

        // Convert remaining time to hours, minutes, and seconds
        $remainingHours = floor($remainingTimeInSeconds / 3600);
        $remainingMinutes = floor(($remainingTimeInSeconds % 3600) / 60);
        $remainingSeconds = $remainingTimeInSeconds % 60;
        $finalDifferenceTime = $remainingHours."hrs. ".$remainingMinutes."min. ".$remainingSeconds."sec";
            return redirect('/wallet')->with('error','Next Withdrawal allowed after '.$finalDifferenceTime);
        }
        $winningAmount  = $this->findWinningWallet($user_id);
     	$user_kyc = UserData::where('user_id',$user_id)->first();
    //  	return $user_kyc;
		return view('user.withdraw-request-page',compact('user_kyc'));
    }
    public function findWinningWallet($user_id){
        $win =  User::where('id',$user_id)->sum('win_amount');
        $wallet =  User::where('id',$user_id)->sum('wallet');
        if($win > $wallet){
            return $wallet;
        }
        return $win;
    }
    public function upiWithdraw(Request $request)
    {
        $request->session()->forget('withdrawalPending');
        $user_id = Auth::user()->id;
        $winningAmount  = $this->findWinningWallet($user_id);
		return view('user.upi-withdraw',compact('winningAmount'));
    }
    private function APIHitDeepvue($token,$url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'x-api-key: 0fad5b8a34504ec6938da5bc4eeccf35',
            'Authorization: Bearer '.$token
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
    private function AuthDeepvue(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://production.deepvue.tech/v1/authorize',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('client_id' => 'free_tier_khanaasaf69_ab971a2871','client_secret' =>'0fad5b8a34504ec6938da5bc4eeccf35'),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
    public function universalWithdraw(Request $request)
    {
        $user_id = Auth::user()->id;
        $sett = Setting::where('field_name', 'WithdrawalTimer')->first();
        $WithdrawalStatus = Setting::where('field_name', 'WithdrawalStatus')->first();
        $WithdrawalStatusS = $WithdrawalStatus->field_value;

        if ($WithdrawalStatusS == "no") {
            return redirect('/wallet')->with('error', 'Withdrawal currently paused due to some Bank issue, Please try after some time!');
        }

        // Cast field_value to an integer
        $withdrawalTimer = (int)$sett->field_value;

        $existdata = Transaction::where('user_id', $user_id)
            ->where(function ($query) {
                return $query
                    ->where('status', 'Withdrawing')
                    ->orWhere('status', 'Withdraw');
            })
            ->where('created_at', '>=', now()->subMinutes($withdrawalTimer))
            ->orderBy('id', 'desc')
            ->first();

        if ($existdata) {
            $Created_atLastData = $existdata->created_at;
            $RemainMinutesconst = $withdrawalTimer;

            // Convert the created_at timestamp to a Carbon instance
            $lastCreatedAt = Carbon::parse($Created_atLastData);

            // Get the current time
            $currentTime = Carbon::now();

            // Calculate the difference in seconds
            $elapsedTimeInSeconds = $lastCreatedAt->diffInSeconds($currentTime);

            // Calculate the remaining time in seconds
            $remainingTimeInSeconds = ($RemainMinutesconst * 60) - $elapsedTimeInSeconds;

            // Convert remaining time to hours, minutes, and seconds
            $remainingHours = floor($remainingTimeInSeconds / 3600);
            $remainingMinutes = floor(($remainingTimeInSeconds % 3600) / 60);
            $remainingSeconds = $remainingTimeInSeconds % 60;

            $finalDifferenceTime = $remainingHours . "hrs. " . $remainingMinutes . "min. " . $remainingSeconds . "sec";
            return redirect('/wallet')->with('error', 'Next Withdrawal allowed after ' . $finalDifferenceTime);
        }

        $winningAmount = $this->findWinningWallet($user_id);
        $user_kyc = UserData::where('user_id', $user_id)->first();

        $UPIbankDetail = null;
        $IMPSbankDetail = null;

        $WithdrawalUPIStatus = Setting::where('field_name', 'UpiWithdrawal')->first();
        if ($WithdrawalUPIStatus->field_value == "yes") {
            $UPIbankDetail = UserBank::where('uid', $user_id)->where('type', 'upi')->where('status', 1)->orderBy('id', 'desc')->first();
        }

        $WithdrawalIMPSStatus = Setting::where('field_name', 'ImpsWithdrawal')->first();
        if ($WithdrawalIMPSStatus->field_value == "yes") {
            $IMPSbankDetail = UserBank::where('uid', $user_id)->where('type', 'imps')->where('status', 1)->orderBy('id', 'desc')->first();
        }

        $ModeOn = [$WithdrawalUPIStatus->field_value, $WithdrawalIMPSStatus->field_value];
        return view('user.universal-withdraw', compact('winningAmount', 'ModeOn', 'IMPSbankDetail', 'UPIbankDetail', 'user_kyc'));
    }

    public function editBankDetail(Request $request)
    {
        $user_id = Auth::user()->id;
        $IMPSbankDetail = UserBank::where('uid',$user_id)->where('type','imps')->where('status',1)->orderBy('id','desc')->first();
        $UPIbankDetail = UserBank::where('uid',$user_id)->where('type','upi')->where('status',1)->orderBy('id','desc')->first();
        $WithdrawalUPIStatus = Setting::where('field_name','UpiWithdrawal')->first();
        if($WithdrawalUPIStatus->field_value == "yes"){
            $UPIbankDetail = UserBank::where('uid',$user_id)->where('type','upi')->where('status',1)->orderBy('id','desc')->first();
        }
        $WithdrawalIMPSStatus = Setting::where('field_name','ImpsWithdrawal')->first();
        if($WithdrawalIMPSStatus->field_value == "yes"){
            $IMPSbankDetail = UserBank::where('uid',$user_id)->where('type','imps')->where('status',1)->orderBy('id','desc')->first();
        }
        $ModeOn = [$WithdrawalUPIStatus->field_value,$WithdrawalIMPSStatus->field_value];
        return view('user.edit-bank',compact('IMPSbankDetail','ModeOn','UPIbankDetail'));
    }
    public function POSTeditBankTypeDetail($type,Request $request)
    {
        $user_id = Auth::user()->id;
        $TokenAUth = $this->AuthDeepvue();
        $token = $TokenAUth->access_token;
        if($type == "upi"){
            $upiId = $request->upi_id;
            $url = "https://production.deepvue.tech/v1/verification/upi?vpa=".$upiId;
            $bankDetailDB = UserBank::where('number',$upiId)->where('type',$type)->where('auto',1)->first();
            $name_at_bank = "Rajasthani Ludo User";
            $flag = 0;
            if(!$bankDetailDB){
                $response = $this->APIHitDeepvue($token,$url);
                if(isset($response->code) && $response->code == 200){
                    if($response->data->account_exists){
                        $name_at_bank = $response->data->name_at_bank;
                        $flag = 1;
                    }
                }
            }else{
                $name_at_bank = $bankDetailDB->name;
            }
            if($flag == 1){
                $bankDetail = UserBank::where('uid',$user_id)->where('type',$type)->where('status',1)->orderBy('id','desc')->first();
                if($bankDetail){
                    $bankDetail->number = $upiId;
                    $bankDetail->type = $type;
                    $bankDetail->name = $name_at_bank;
                    if($bankDetail->save()){
                        return redirect('/universal-withdraw');
                    }else{
                        return back()->with('error','Someting wents wrong, Please try again.');
                    }
                }else{
                    $dd = new UserBank;
                    $dd->uid = $user_id;
                    $dd->type = $type;
                    $dd->number = $upiId;
                    $dd->name = $name_at_bank;
                    $dd->status = 1;

                    if($dd->save()){
                        return redirect('/universal-withdraw');
                    }else{
                        return back()->with('error','Someting wents wrong, Please try again.');
                    }
                }
            }else{
                $dd = new UserBank;
                $dd->uid = $user_id;
                $dd->type = $type;
                $dd->number = $upiId;
                $dd->name = $name_at_bank;
                $dd->auto = 1;
                $dd->status = 1;
                if($dd->save()){
                    return redirect('/universal-withdraw');
                }else{
                    return back()->with('error','Someting wents wrong, Please try again.');
                }
            }
        }elseif($type=='imps'){
            $account_no = $request->accountNumber;
            $ifsc = $request->ifsc;
            $url = "https://production.deepvue.tech/v1/verification/bankaccount?account_number=$account_no&ifsc=$ifsc";
            $bankDetailDB = UserBank::where('number',$account_no)->where('ifsc',$ifsc)->where('type',$type)->where('auto',1)->first();
            $name_at_bank = "Rajasthani Ludo User";
            $flag = 0;
            if(!$bankDetailDB){
                $response = $this->APIHitDeepvue($token,$url);
                if(isset($response->code) && $response->code == 200){
                    if($response->data->account_exists){
                        $name_at_bank = $response->data->name_at_bank;
                        $flag = 1;
                    }
                }
            }else{
                $name_at_bank = $bankDetailDB->name;
            }
            if($flag == 1){
                $dd = new UserBank;
                $dd->uid = $user_id;
                $dd->number = $account_no;
                $dd->ifsc = $ifsc;
                $dd->type = $type;
                $dd->name = $name_at_bank;
                $dd->auto = 1;
                $dd->status = 1;
                if($dd->save()){
                    return redirect('/universal-withdraw');
                }else{
                    return back()->with('error','Someting wents wrong, Please try again.');
                }
            }else{
                $bankDetail = UserBank::where('uid',$user_id)->where('type',$type)->where('status',1)->orderBy('id','desc')->first();
                if($bankDetail){
                    $bankDetail->number = $account_no;
                    $bankDetail->ifsc = $ifsc;
                    $bankDetail->name = $name_at_bank;
                    if($bankDetail->save()){
                        return redirect('/universal-withdraw');
                    }else{
                        return back()->with('error','Someting wents wrong, Please try again.');
                    }
                }else{
                    $dd = new UserBank;
                    $dd->uid = $user_id;
                    $dd->number = $account_no;
                    $dd->ifsc = $ifsc;
                    $dd->type = $type;
                    $dd->name = $name_at_bank;
                    $dd->status = 1;
                    if($dd->save()){
                        return redirect('/universal-withdraw');
                    }else{
                        return back()->with('error','Someting wents wrong, Please try again.');
                    }
                }
            }
        }
        $bankDetail = UserBank::where('uid',$user_id)->where('type',$type)->first();
        return view('user.edit-bank-type',compact('bankDetail','type'));
    }
    public function editBankTypeDetail($type)
    {
        $user_id = Auth::user()->id;
        $bankDetail = UserBank::where('uid',$user_id)->where('type',$type)->first();
        return view('user.edit-bank-type',compact('bankDetail','type'));
    }

    public function bankWithdraw(Request $request)
    {
        $user_id = Auth::user()->id;
        $winningAmount  = $this->findWinningWallet($user_id);
        return view('user.bank-withdraw',compact('winningAmount'));
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
    public function checkupis(Request $r){
        $upiid = $r->upiid;
        return $this->checkUpi($upiid);
    }
    public function sendpayment($amount,$vpa,$orderid,$name)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://alshuindia.com/payout_integrate.php',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('upiid' => $vpa,'amount' => $amount),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return array('status'=>false,'data'=>json_decode($err));
        } else {
            return array('status'=>true,'data'=>json_decode($response));
        }
    }
    public function POSTuniversalWithdraw(Request $request)
    {
        // Start by logging the request
        Log::info('POSTuniversalWithdraw called', ['user_id' => Auth::user()->id, 'request_data' => $request->all()]);

        session()->forget('withdrawalPending');
        if(session()->has('withdrawalPending')){
            Log::warning('Duplicate withdrawal attempt', ['user_id' => Auth::user()->id]);
            return response()->json(['error'=>'Duplicate Withdrawal not Allowed!!']);
        }

        $user_id = Auth::user()->id;
        $walletData = User::find($user_id);
        $winningamount = $this->findWinningWallet($user_id);
        $bankDetail = UserBank::where('uid', $user_id)->where('type', $request->type)->where('status', 1)->orderBy('id', 'desc')->first();

        if(!$bankDetail){
            Log::error('Banking detail not found', ['user_id' => $user_id, 'type' => $request->type]);
            return response()->json(['error'=>'Banking detail not added.']);
        }

        $creGames = Challenge::where('c_id', $user_id)->where('status', '!=', 0)->sum('amount');
        $oppGames = Challenge::where('o_id', $user_id)->where('status', '!=', 0)->sum('amount');
        $sett = Setting::where('field_name', 'WithdrawalTimer')->first();


        if (!is_numeric($sett->field_value) || empty($sett->field_value)) {
            Log::error('Invalid or empty WithdrawalTimer value', ['field_value' => $sett->field_value]);
            return response()->json(['error' => 'Invalid withdrawal timer setting.']);
        }

        Log::info('WithdrawalTimer value', ['field_value' => $sett->field_value]);

        $existdata = Transaction::where('user_id', $user_id)
            ->where(function($query){
                return $query
                    ->where('status', 'Withdrawing')
                    ->orWhere('status', 'Withdraw');
            })
            ->where('created_at', '>=', now()->subMinutes($sett->field_value))
            ->count();

            if ($existdata > 0) {
                Log::info('Withdrawal attempt within restricted time', ['user_id' => $user_id, 'minutes' => $sett->field_value]);
                return response()->json(['error' => 'Add 1 withdrawal in a ' . $sett->field_value . 'min.']);
            }

        if($creGames > 0 || $oppGames > 0){
            Log::info('User has pending games', ['user_id' => $user_id, 'creGames' => $creGames, 'oppGames' => $oppGames]);
            return response()->json(['error'=>'Please complete remaining games.']);
        }

        if($walletData->wallet <= 0 || $winningamount < $request->amount){
            Log::error('Insufficient balance', ['user_id' => $user_id, 'wallet' => $walletData->wallet, 'winningamount' => $winningamount, 'requested_amount' => $request->amount]);
            return response()->json(['error'=>'Insufficient balance or clear your pending game first!']);
        }

        $request->session()->put('withdrawalPending', true);
        $request->validate([
            'amount' => 'required|numeric|min:200',
            'type' => 'required'
        ]);

        // Logging before the transaction is created
        Log::info('Creating transaction', ['user_id' => $user_id, 'amount' => $request->amount, 'type' => $request->type]);

        $wallet = $walletData->wallet;
        $amount = $request->amount;

        if($request->type == "upi"){
            $transaction = Transaction::create([
                'user_id'           => $user_id,
                'source_id'         => $bankDetail->number,
                'amount'            => $request->amount,
                'status'            => 'Withdrawing',
                'remark'            => 'Pending',
                'response'          => $bankDetail->number,
                'ip'                =>  $request->ip(),
                'closing_balance' =>  $wallet-$amount,
            ]);
            $withdraw = WithdrawRequest::create([
                'user_id'       =>  $user_id,
                'amount'        =>  $request->amount,
                'upi'           =>  $bankDetail->number,
                'holdername'    =>  $bankDetail->name,
                'type'          =>  'UPI',
                'status'        =>  'Unpaid',
                'ip'            =>  $request->ip(),
                'tid'           =>  $transaction->id
            ]);
        } elseif($request->type == "imps"){
            $transaction = Transaction::create([
                'user_id'           => $user_id,
                'source_id'         => $bankDetail->number,
                'amount'            => $request->amount,
                'status'            => 'Withdrawing',
                'remark'            => 'Pending',
                'ip'                =>  $request->ip()
            ]);
            $withdraw = WithdrawRequest::create([
                'user_id'       =>  $user_id,
                'amount'        =>  $request->amount,
                'ifsc_code'     =>  $bankDetail->ifsc,
                'account_no'    =>  $bankDetail->number,
                'holdername'    =>  $bankDetail->name,
                'type'          =>  'Bank',
                'ip'            =>  $request->ip(),
                'tid'           =>  $transaction->id
            ]);
        }

        // Log order id generation
        Log::info('Generating order id', ['withdraw_id' => $withdraw->id]);

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $orderid = '';
        for ($i = 0; $i < 35; $i++) {
            $orderid .= $characters[rand(0, strlen($characters) - 1)];
        }
        $order_idd = $orderid.$withdraw->id;
        Transaction::where('id',$transaction->id)->update(["source_id"=>$order_idd]);

        if($withdraw){
            $ammmmm = $request->amount;
            if($winningamount >= $ammmmm){
                if($walletData->wallet >= $walletData->win_amount){
                    $walletData->decrement('win_amount',$ammmmm);
                }else{
                    $amwin = $winningamount - $ammmmm;
                    User::where('id',$user_id)->update(['win_amount'=>$amwin]);
                }
            }
            $walletData->decrement('wallet',$ammmmm);

            // Final log before response
            Log::info('Withdrawal request processed', ['user_id' => $user_id, 'amount' => $ammmmm, 'remaining_wallet' => $walletData->wallet]);

            $request->session()->forget('withdrawalPending');
            return response()->json(['success'=>'Withdraw request paid successfully!','wallet_amount' => number_format($walletData->wallet,2)]);
        } else {
            Log::error('Withdrawal request failed', ['user_id' => $user_id]);
        }
    }
    public function upiWithdrawPost(Request $request)
	{
	   // $request->session()->forget('withdrawalPending');
	    $existupi = $this->checkUpi($request->upi_id);
	    $upiexist = json_decode($existupi);
	   // $upiexist->message = "User";
	   // $upiexist->status = "true";
	    if($upiexist->status == "true"){
// 		try
//         {
            if(session()->has('withdrawalPending')){
                return response()->json(['error'=>'Dublicate Withdrawal not Allowed!!']);
            }
            $user_id    =   Auth::user()->id;
            $walletData =   User::find($user_id);
            $winningamount = $this->findWinningWallet($user_id);
            $creGames   =   Challenge::where('c_id',$user_id)->where('status','!=',0)->sum('amount');
            $oppGames   =   Challenge::where('o_id',$user_id)->where('status','!=',0)->sum('amount');
            $sett = Setting::where('field_name','WithdrawalTimer')->first();
            $existdata = Transaction::where('user_id',$user_id)->where(function($query){
                return $query
                ->where('status','Withdrawing')
                ->orWhere('status','Withdraw');
            })->where('created_at', '>=', now()->subMinutes($sett->field_value))->count();
            if($existdata > 0){
                return response()->json(['error'=>'Add 1 withdrawal in a '.$sett->field_value.'min.']);
            }
            // if($existdata > 5){
            // $request->amount = ;
            if($creGames > 0 || $oppGames > 0){
                return response()->json(['error'=>'Please complete remianing games.']);
            }
            if($walletData->wallet <=0 || $winningamount < $request->amount){
                return response()->json(['error'=>'Insufficient balance or clear your pending game first!']);
            }
            $request->session()->put('withdrawalPending',true);
            $request->validate([
                'upi_id' => 'required',
                'amount' => 'required|numeric|min:200',
                // 'amount' => 'required|numeric|min:0',
            ]);
            $wallet = $walletData->wallet;
            $amount = $request->amount;
            $transaction = Transaction::create([
            'user_id'           => $user_id,
            'source_id'         => $request->upi_id,
            'amount'            => $request->amount,
            'status'            => 'Withdrawing',
            'remark'            => 'Pending',
            'response'          => $request->upi_id,
            'ip'                =>  $request->ip(),
            'closing_balance' =>  $wallet-$amount,
            ]);
            // return $transaction->id;
            $withdraw = WithdrawRequest::create([
                'user_id'       =>  $user_id,
                'amount'        =>  $request->amount,
                'upi'           =>  $request->upi_id,
                'type'          =>  'UPI',
                'status'          =>  'Unpaid',
                'ip'            =>  $request->ip(),
                'tid'            =>  $transaction->id
            ]);
            //Order id
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $orderid = '';

            for ($i = 0; $i < 35; $i++) {
                $orderid .= $characters[rand(0, strlen($characters) - 1)];
            }
            $order_idd = $orderid.$withdraw->id;
            Transaction::where('id',$transaction->id)->update(["source_id"=>$order_idd]);
            if($withdraw){
                $ammmmm = $request->amount;
                    if($winningamount >= $ammmmm){
                        if($walletData->wallet >= $walletData->win_amount){
                            $walletData->decrement('win_amount',$ammmmm);
                        }else{
                            $amwin = $winningamount - $ammmmm;
                            User::where('id',$user_id)->update(['win_amount'=>$amwin]);
                        }
                    }
                    $walletData->decrement('wallet',$ammmmm);
                    $sett = Setting::where('field_name','auto_withdraw')->first();
                    if($sett && $sett->field_value == "yes"){
                        $auto_withdraw = $sett->field_value;
                        $gateway_status = $this->sendpayment($request->amount,$request->upi_id,$order_idd,$upiexist->message);
                        // return $gateway_status;
                        // if($gateway_status['status']){
                        $GatewayPitput = $gateway_status['data'];
                        // return $GatewayPitput->status;
                        // if($GatewayPitput->status == "failed" || $GatewayPitput->status == ""){
                        //     // return response()->json(['upi'=>$upiexist->message,'error'=>$GatewayPitput->mess,'wallet_amount' => number_format($walletData->wallet,2)]);
                        //     Setting::where('field_name','withdraw_gateway_log')->update(["field_value"=>$GatewayPitput->mess]);
                        //     return response()->json(['upi'=>$upiexist->message,'success'=>'Withdraw request successfully, Paid after some time!','wallet_amount' => number_format($walletData->wallet,2)]);
                        // }
                        if($GatewayPitput->status == "processing" || $GatewayPitput->status == "queued"){
                            // Setting::where('field_name','withdraw_gateway_log')->update(["field_value"=>'']);
                            $data        =  WithdrawRequest::find($withdraw->id);
                            $transaction = Transaction::where('id',$transaction->id)->update(["status"=>"Withdraw","remark"=>"Processing"]);
                            $data->account_no   =   $GatewayPitput->id;
                            $data->remark   =   'Processing';
                            $data->save();
                            // $request->session()->forget('withdrawalPending');
                            // return response()->json(['upi'=>$upiexist->message,'success'=>'Withdraw request paid successfully!','wallet_amount' => number_format($walletData->wallet,2)]);
                        }
                        // return $GatewayPitput;
                        // Setting::where('field_name','withdraw_gateway_log')->update(["field_value"=>$GatewayPitput->mess]);

                        // return response()->json(['upi'=>$upiexist->message,'success'=>'Withdraw request paid successfully!','wallet_amount' => number_format($walletData->wallet,2)]);

                        // return response()->json(['upi'=>$upiexist->message,'error'=>$GatewayPitput->mess,'wallet_amount' => number_format($walletData->wallet,2)]);
                        // }
                    }
                    $request->session()->forget('withdrawalPending');
                    return response()->json(['upi'=>$upiexist->message,'success'=>'Withdraw request paid successfully!','wallet_amount' => number_format($walletData->wallet,2)]);
            }
        // } catch (\Exception $e) {
        //     $bug = $e->getMessage();
        //     return $bug;
        // }
	    }else{
	        return response()->json(['error'=>'Invalid Upi Id!']);
	    }
    }

    public function bankWithdrawPost(Request $request)
	{
		try
        {
            $user_id    =   Auth::user()->id;
            $walletData =   User::find($user_id);
            $winningamount = $this->findWinningWallet($user_id);
            $creGames   =   Challenge::where('c_id',$user_id)->where(function($query){
                return $query
                ->where('status',1)
                ->orWhere('status',2);
            })->sum('amount');

            $oppGames   =   Challenge::where('o_id',$user_id)->where(function($query){
                return $query
                ->where('status',1)
                ->orWhere('status',2);
            })->sum('amount');

            if($walletData->wallet <=0 || $winningamount < $request->amount){
                return response()->json(['error'=>'Insufficient balance or clear your pending game first!']);
            }
            $request->validate([
                'ifsc_code' => 'required',
                'account_no' => 'required|numeric',
                'amount' => 'required|numeric|min:190',
            ]);
            $transaction = Transaction::create([
            'user_id'           => $user_id,
            'source_id'         => $request->account_no,
            'amount'            => $request->amount,
            'status'            => 'Withdrawing',
            'remark'            => 'Pending',
            'ip'                =>  $request->ip()
        ]);
            $withdraw = WithdrawRequest::create([
                'user_id'       =>  $user_id,
                'amount'        =>  $request->amount,
                'ifsc_code'     =>  $request->ifsc_code,
                'account_no'    =>  $request->account_no,
                'holdername'    =>  $request->holdername,
                'type'          =>  'Bank',
                'ip'            =>  $request->ip(),
                'tid'           =>  $transaction->id
            ]);

            if($withdraw){
                $ammmmm = $request->amount;
                if($winningamount >= $ammmmm){
                        if($walletData->wallet >= $walletData->win_amount){
                            $walletData->decrement('win_amount',$ammmmm);
                        }else{
                            $amwin = $winningamount - $ammmmm;
                            User::where('id',$user_id)->update(['win_amount'=>$amwin]);
                        }
                    }
                    $walletData->decrement('wallet',$ammmmm);

                return response()->json(['success'=>'Withdraw request sent successfully!','wallet_amount' => number_format($walletData->wallet,2)]);
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Challenge;
use App\UserResult;
use App\Transaction;
use App\ChallengeResult;
use App\User;
use App\Setting;
use Auth;
use App\Http\Controllers\Admin\AdminChallengeController;
use Http;

class UserResultController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function challengeDetail(Request $request,$chid)
    {
		$chData			=	Challenge::with('userresult')->where('id',$chid)->first();
		$user_id 		= 	Auth::user()->id;

        $settingData    =   Setting::find(1);
        if($chData->c_id == $user_id || $chData->o_id == $user_id){
            //if($settingData->field_value)
                return view('user.challenge-detail',compact('chData'));
            // else
            //     return view('user.challenge-detail-old',compact('chData'));
        }

        die('Access denied!');
    }

    public function challengeDetailChk(Request $request,$chid)
    {
		$chData			=	Challenge::with('userresult')->where('id',$chid)->first();
		$user_id 		= 	Auth::user()->id;

        $settingData    =   Setting::find(1);
        if($chData->c_id == $user_id || $chData->o_id == $user_id){
            //if($settingData->field_value)
                return view('user.challenge-detail-chk',compact('chData'));
            // else
            //     return view('user.challenge-detail-old',compact('chData'));
        }

        die('Access denied!');
    }



    public function getRoomCode(Request $request){
      $chData			=	Challenge::find($request->ch_id);
      $roomcode   =   $chData->rcode;

        if(($chData->status == 4 || $chData->status == 3)){
            return response()->json(['data'=>$roomcode]);
        }
    }

    public function getRoomCodeChk(Request $request){

    }

    public function roomCode(Request $request)
    {
		$request->validate( [
			'room_id' => 'required|numeric|unique:challenges,rcode',
			'ch_id'   => 'required|numeric'
		]);
        try
        {
            $chData     =   Challenge::find($request->ch_id);
            if(($chData->status == 4 || $chData->status == 3) && $chData->rcode == 0){
                $chData->update([
                    'rcode' =>  $token,
                ]);
                return response()->json(['data'=>$chData]);
            }
            return response([
                'message'        => 'We are unable to process your request at this time!'
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }

    }

    public function create(Request $request)
	{
        $request->validate( [
			'result'  => 'required',
			'ch_id'   => 'required|numeric'
		]);

		try
        {
            $chData     =   Challenge::find($request->ch_id);
            $user_id    =   Auth::user()->id;
            $resData    =   UserResult::where('ch_id',$request->ch_id)->where('user_id',$user_id)->count();

            if($resData){
                return response([
                    'message'        => 'You have already submitted the result for this game!'
                ],400);
            }

            if($chData->status == 4 || $chData->status == 3){
                $result         =   new UserResult();
                $result->ch_id  =   $request->ch_id;
                $result->user_id  =   $user_id;
                $result->result  =   $request->result;
                $result->reason  =   $request->reason;
                $result->ip      =   $request->ip();
                if($request->result=='Won'){
                        $request->validate([
                                    'result_img' => 'required|image|mimes:jpeg,png,jpg,gif,JPG,JPEG|max:10240',
                                ]);
                        $imageName = time().'-'.$user_id.'.'.$request->result_img->extension();
                        //@chmod(public_path('uploads/'.$result->c_id), 0777);
                        $request->result_img->move(public_path('uploads/'), $imageName);
                        $result->image 	=	'uploads/'.$imageName;
                    }
                $result->save();
                if($chData->rcode == 0 && $request->result == 'Cancel' && $request->status != 5){
                    $adminInstance = new AdminChallengeController(); // Create an instance of the Admin class
                    $adminInstance->cancelGameInParameter($chData->id);

                }
                    if($chData->c_id == $user_id){
                    $rslUpload  =  UserResult::where('ch_id',$request->ch_id)->where('user_id',$chData->o_id)->first();
                    if(isset($rslUpload->result)){
                        switch($rslUpload->result){
                            case 'Won':
                                if($request->result == 'Won'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Loss'){
                                    $a_amount   =   $this->calculateCom($chData->amount);
                                   // $f_amount   =   (2 * $chData->amount - $a_amount);
                                    $f_amount   =   ($chData->amount - $a_amount);
                                   $ff_amount   =   (2 * $chData->amount - $a_amount);
                                    $r_amount   =   0.02 * $chData->amount;
                                    $chData->status =   0;
                                    $chData->save();
                                    $txn        =   $this->createTxn($request,$f_amount,$a_amount,'Won',$chData->o_id,$ff_amount);
                                    if($txn){
                                        $this->submitChResult($request->ch_id,$chData->o_id,0);
                                        $this->updateWallet($ff_amount,$chData->o_id);
                                        $this->updateReferral($request, $r_amount, $chData->o_id);
                                    }
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                            break;
                            case 'Loss':
                                if($request->result == 'Won'){
                                    $a_amount   =   $this->calculateCom($chData->amount);
                                    $f_amount   =   ( $chData->amount - $a_amount);
									$ff_amount   =   (2 * $chData->amount - $a_amount);

                                   // $f_amount   =   (2 * $chData->amount - $a_amount);
                                    $r_amount   =   0.02 * $chData->amount;
                                    $chData->status =   0;
                                    $chData->save();
                                    $txn        =   $this->createTxn($request,$f_amount,$a_amount,'Won',$chData->c_id,$ff_amount);
                                    if($txn){
                                        $this->submitChResult($request->ch_id,$chData->c_id,0);
                                        $this->updateWallet($ff_amount,$chData->c_id);
                                        $this->updateReferral($request, $r_amount, $chData->c_id);
                                    }
                                }
                                if($request->result == 'Loss'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                            break;
                            case 'Cancel':
                                if($request->result == 'Won'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Loss'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   0;
                                    $chData->save();
                                    $txnCre        =   $this->createTxn($request,$chData->amount,0,'Cancel',$chData->o_id,$chData->amount);
                                    $txnOpp        =   $this->createTxn($request,$chData->amount,0,'Cancel',$chData->c_id,$chData->amount);
                                    if($txnCre && $txnOpp){
                                        $this->submitChResult($request->ch_id,0,1);
                                        $this->updateWallet($chData->amount,$chData->o_id);
                                        $this->updateWallet($chData->amount,$chData->c_id);
                                    }
                                }
                            break;
                        }
                    }
                    }

                    if($chData->o_id == $user_id){
                    $rslUpload  =  UserResult::where('ch_id',$request->ch_id)->where('user_id',$chData->c_id)->first();
                    //echo $rslUpload->result ; die;
                    //if($rslUpload->result) die('nrj');
                    if(isset($rslUpload->result)){
                        switch($rslUpload->result){
                            case 'Won':
                                if($request->result == 'Won'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Loss'){
                                    $a_amount   =   $this->calculateCom($chData->amount);
                                   // $f_amount   =   (2 * $chData->amount - $a_amount);
									$ff_amount   =   (2 * $chData->amount - $a_amount);

                                    $f_amount   =   ($chData->amount - $a_amount);
                                    $r_amount   =   0.02 * $chData->amount;
                                    $chData->status =   0;
                                    $chData->save();
                                    $txn        =   $this->createTxn($request,$f_amount,$a_amount,'Won',$chData->c_id,$ff_amount);
                                    if($txn){
                                        $this->submitChResult($request->ch_id,$chData->c_id,0);
                                        $this->updateWallet($ff_amount,$chData->c_id);
                                        $this->updateReferral($request, $r_amount, $chData->c_id);
                                    }
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                            break;
                            case 'Loss':
                                if($request->result == 'Won'){
                                    $a_amount   =   $this->calculateCom($chData->amount);
                                    //$f_amount   =   (2 * $chData->amount - $a_amount);
                                    $ff_amount   =   (2 * $chData->amount - $a_amount);
                                    $f_amount   =   ($chData->amount - $a_amount);
                                    $r_amount   =   0.02 * $chData->amount;
                                    $chData->status =   0;
                                    $chData->save();
                                    $txn        =   $this->createTxn($request,$f_amount,$a_amount,'Won',$chData->o_id,$ff_amount);
                                    if($txn){
                                        $this->submitChResult($request->ch_id,$chData->o_id,0);
                                        $this->updateWallet($ff_amount,$chData->o_id);
                                        $this->updateReferral($request, $r_amount, $chData->o_id);
                                    }
                                }
                                if($request->result == 'Loss'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                            break;
                            case 'Cancel':
                                if($request->result == 'Won'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Loss'){
                                    $chData->status =   5;
                                    $chData->save();
                                }
                                if($request->result == 'Cancel'){
                                    $chData->status =   0;
                                    $chData->save();
                                    $txnCre        =   $this->createTxn($request,$chData->amount,0,'Cancel',$chData->o_id,$chData->amount);
                                    $txnOpp        =   $this->createTxn($request,$chData->amount,0,'Cancel',$chData->c_id,$chData->amount);
                                    if($txnCre && $txnOpp){
                                        $this->submitChResult($request->ch_id,0,1);
                                        $this->updateWallet($chData->amount,$chData->o_id);
                                        $this->updateWallet($chData->amount,$chData->c_id);
                                    }
                                }
                            break;
                        }
                    }
                }

                return response()->json(['data'=>$chData]);
            }
            return response([
                'message'        => 'We are unable to process your request at this time!'
            ],400);
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }
    }

    private function calculateCom($amount){
        if($amount > 0 && $amount <= 250){
        $a_amount	=	10/100*($amount);
        }else{
        $a_amount	=	5/100*($amount); //5/100
        }
        return $a_amount;
    }

    private function updateWallet($amount,$user_id){
        $walletData =   User::find($user_id);
        // $walletData->increment('usd_wallet',$amount);
        $walletData->increment('wallet',$amount);
        $walletData->increment('win_amount',$amount);
    }

    private function updateReferral($request, $amount, $user_id){
        $usertData =   User::with('setting')->find($user_id);

        if(isset($usertData->setting->used_referral) && $usertData->setting->used_referral){
            $uData  =   User::find($usertData->setting->rf_user_id);
            //echo "<pre>";print_r($uData);die('kk');
			  	$user_data = User::where('id', $usertData->setting->rf_user_id)->first();
				$wallet = $user_data->wallet;

            $txn    =   Transaction::create([
                'user_id'       =>  $usertData->setting->rf_user_id,
                'source_id'     =>  $request->ch_id,
                'amount'        =>  $amount,
                'a_amount'      =>  0,
                'status'        =>  'Referral',
                'remark'        =>  $usertData->username,
                'ip'            =>  $request->ip(),
				'closing_balance' =>  $wallet+$amount,
            ]);

            if($txn){
                $uData->increment('win_amount',$amount);
                $uData->increment('wallet', $amount);
            }
        }

    }

    private function createTxn($request, $f_amount, $a_amount,$status, $user_id,$ff_amount){
		  	$user_data = User::where('id', $user_id)->first();
				$wallet = $user_data->wallet;
			if(($status=='Won') || ($status='Cancel')){
			$closing_balance = 	 $wallet+$ff_amount;
			} else {
			$closing_balance = 	 $wallet-$f_amount;

			}
        $txn    =   Transaction::create([
            'user_id'       =>  $user_id,
            'source_id'     =>  $request->ch_id,
            'amount'        =>  $f_amount,
            'a_amount'      =>  $a_amount,
            'status'        =>  $status,
            'remark'        =>  'Result submit by user '.$status,
            'ip'            =>  $request->ip(),
			'closing_balance' =>  $closing_balance,

        ]);
        return $txn;
    }

    private function submitChResult($ch_id,$user_id,$type){
        ChallengeResult::create([
            'ch_id'     =>  $ch_id,
            'user_id'   =>  $user_id,
            'sub_by'    =>  'User',
            'is_cancel' =>  $type
        ]);
    }
}

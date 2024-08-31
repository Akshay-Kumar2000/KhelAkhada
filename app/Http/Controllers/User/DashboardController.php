<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Challenge;
use App\ChallengeResult;
use App\Transaction;
use App\User;
use App\UserSetting;
use App\WithdrawRequest;
use Auth;
use DB;
use Session;
use App\UserData;
class DashboardController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
		$user_id = Auth::user()->id;

		$total_loss = 0;
		$total_won			=	Transaction::where([['user_id','=',$user_id],['status','=','Won']])->sum(DB::raw('amount - a_amount'));
		$total_withdraw		=	Transaction::where([['user_id','=',$user_id],['status','=','Withdraw']])->sum('amount');

		if(Session::has('payment_status'))
			$payment_msg	=	Session::get('payment_status');
		else
			$payment_msg	=	'';

        return view('user.dashboard',compact('total_won','total_withdraw','payment_msg'));
    }

    public function changeUniqueId(Request $request)
    {
        try
        {
            $uid 		= 	$request->username;
            $data	    =	User::where('username',$uid)->count();
            if(!$uid){
                return response()->json(['message'=>'Unique ID may not be empty!']);
            }elseif($data){
                return response()->json(['message'=>'Unique ID already taken!']);
            }elseif(!$data){
                User::where('id',Auth::user()->id)->update(['username'=>$uid]);
                return response()->json(['message'=>'Unique ID changed successfully!']);
            }else{
                return response()->json(['message'=>'Server error!']);
            }
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function useReferralCode(Request $request)
    {
        try
        {
            $referral_code 		= 	$request->referral_code;
            $data	            =	UserSetting::where('referral',$referral_code)->first();
            if(isset($data) && $data->user_id != Auth::user()->id){
                $userSetting    =   UserSetting::where('user_id',Auth::user()->id)->first();
                $userSetting->used_referral =   $referral_code;
                $userSetting->rf_user_id    =   $data->user_id;
                $userSetting->save();
                return response()->json(['message'=>'referral code updated successfully!']);
            }
            return response()->json(['message'=>'Invalid referral code!']);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function userWallet(Request $request){
        $wallet =   DB::table('user_settings')->select('wallet')
                    ->where('user_id',Auth::user()->id)->first();
        return $wallet->wallet;
    }

    public function profile(Request $request)
    {
        $user_id    =   Auth::user()->id;
        $total_won  =   Transaction::where('user_id',$user_id)->where('status','Won')->sum('amount');
        $total_games  =   Challenge::where('c_id',$user_id)->orWhere('o_id',$user_id)->count();
		$user = User::where('id',$user_id)->first();

	     $userData= UserData::where('user_id','=',$user_id)->get();
	     $userDatac= UserData::where('user_id','=',$user_id)->count();

		//$userData   =   User::with('setting')->where('id',$user_id)->first();
        return view('user.profile',compact('user','userData','userDatac','total_won','total_games'));
    }

    public function findWinningWallet($user_id){
        $win =  User::where('id',$user_id)->sum('win_amount');
        $wallet =  User::where('id',$user_id)->sum('wallet');
        if($win > $wallet){
            return $wallet;
        }
        return $win;
    }
    public function wallet(Request $request)
    {
        $user_id    =   Auth::user()->id;
		$userData   =   User::with('setting')->where('id',$user_id)->first();
		$winningAmount  = $this->findWinningWallet($user_id);
        return view('user.wallet',compact('userData','winningAmount'));

     }

    public function referralCode(Request $request)
    {
        $user_id    =   Auth::user()->id;
		$settings   =   UserSetting::where('user_id',$user_id)->first();
      	if(empty($settings)){
          $settings = new UserSetting;
          $settings->user_id   =  $user_id;
          $settings->referral  =  bin2hex(random_bytes(4));
          $settings->used_referral = NULL;
          $settings->save();
        }

      	$userData   =   User::with('setting')->where('id',$user_id)->first();
      	$uReferral	=	UserSetting::where('used_referral',$userData->setting->referral)->count();
        $refEarning =	Transaction::where([['user_id','=',$user_id],['status','=','Referral']])->sum('amount');
        return view('user.referral-code',compact('userData','uReferral','refEarning'));
    }

}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Challenge;
use App\ChallengeResult;
use App\Transaction;
use App\User;

use Auth;
use Illuminate\Support\Facades\Validator;

// use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Firebase\Messaging;
// use Kreait\Firebase\Messaging\Notification;

class AdminChallengeController extends Controller
{

    protected $paging   =   200;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        //echo "<pre>";print_r($request->all());die;
        $search='';
        $search_in='c_id';
        if($request->status == 'Hold'){
            $challenges  = Challenge::with(['creator','opponent','result'])
                            ->where('status',5)->latest()->paginate($this->paging);
        }elseif($request->status == 'Playing'){
            $challenges  = Challenge::with(['creator','opponent','result'])
                            ->where(function($query){
                            return $query
                            ->where('status',2)
                            ->orWhere('status',3)
                            ->orWhere('status',4);
                        })->latest()->paginate($this->paging);
        }elseif($request->search){
            $search         =  $request->search;
            $search_in         =  $request->search_in;

            $challenges  = Challenge::select('challenges.*','users.username')->with(['creator','opponent','result'])
                            ->leftJoin('users','challenges.'.$search_in,'=','users.id')
                            ->where('challenges.id','LIKE','%'.$search.'%')
                            ->orWhere('challenges.amount','LIKE','%'.$search.'%')
                            ->orWhere('challenges.type','LIKE','%'.$search.'%')
                            ->orWhere('users.username','LIKE','%'.$search.'%')
                            ->orWhere('challenges.rcode','LIKE','%'.$search.'%')
                            ->latest()->paginate($this->paging);
        }else{
            $challenges  = Challenge::with(['creator','opponent','result'])->latest()->paginate($this->paging);
        }


        //echo "<pre>";print_r($challenges);die;
        return view('admin/challenge/challenges',compact('challenges','search','search_in'));
    }

    public function details($id)
    {
        $challenge  = Challenge::with(['creator','opponent','result','usersresult','transactions'])->where('id',$id)->first();

        //echo "<pre>";print_r($challenge);die;
        return view('admin/challenge/details',compact('challenge'));
    }

    public function cancelGame(Request $request)
    {
        $validator              = Validator::make($request->all(), [
            'ch_id'              => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try
        {
            $update = $this->setChStatus($request->ch_id);

            if($update){
                $this->setChResult($request->ch_id, 1, NULL);
            }

            $transactions       =   Transaction::where('source_id',$request->ch_id)->where(function($query){
                                        return $query
                                        ->where('status','Create')
                                        ->orWhere('status','Play');
                                    })->get();

            foreach($transactions as $key => $val){
				$user_data = User::where('id', $val->user_id)->first();
			$wallet = $user_data->wallet;

					$closing_balance = 	 $wallet+$val->amount;

                $refund = Transaction::create([
                    'source_id'        => $request->ch_id,
                    'user_id'          => $val->user_id,
                    'amount'           => $val->amount,
                    'status'           => 'Cancel',
                    'remark'           => 'Cancel refund by admin',
                    'ip'               => $request->ip(),
					'closing_balance' =>  $closing_balance

                ]);

                if($refund){
                    $this->updateWallet($val->amount,$val->user_id);
                }
            }

            return response([
                'message'        => 'Game cancelled successfully!'
            ]);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }
    }

    public function gameWinner(Request $request)
    {
        $validator              = Validator::make($request->all(), [
            'ch_id'             => 'required',
            'user_id'           => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try
        {
            $update = $this->setChStatus($request->ch_id);

            if($update){
                $this->setChResult($request->ch_id, 0, $request->user_id);
            }

            $chData            =   Challenge::find($request->ch_id);

            if($chData->c_id   ==  $request->user_id){
                $transaction   =   $this->getTransaction($request->ch_id, $request->user_id, 'Create');
            }

            if($chData->o_id    ==  $request->user_id){
                $transaction   =   $this->getTransaction($request->ch_id, $request->user_id, 'Play');
            }

            if($transaction){
                $a_amount       =   $this->calculateCom($chData->amount);
                $f_amount       =   (2 * $chData->amount - $a_amount);
                $r_amount       =   0.02 * $chData->amount;
                $closing_balance = 	 $f_amount;

                $winner         = Transaction::create([
                    'source_id'        => $request->ch_id,
                    'user_id'          => $request->user_id,
                    'amount'           => $f_amount,
                    'a_amount'         => $a_amount,
                    'status'           => 'Won',
                    'remark'           => 'Set winner by admin',
                    'closing_balance'  =>  $closing_balance,
                    'ip'               => $request->ip()


                ]);

                if($winner){
                    $this->updateWallet($f_amount,$request->user_id);
                    $this->updateReferral($request, $r_amount, $request->user_id);
                }

                return response([
                    'message'        => 'Game winner set successfully!'
                ]);
            }

            return response([
                'message'        => 'Unable to set winner!'
            ],400);
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }
    }

    public function makeWinner($ch_id, $user_id, Request $request)
    {
        try
        {
            $chData            =   Challenge::find($ch_id);

            if($chData->status == 4){
                $update = $this->setChStatus($ch_id);

                if($update){
                    $this->setChResult($ch_id, 0, $user_id);
                }

			$user_data = User::where('id', $user_id)->first();
			$wallet = $user_data->wallet;

                $a_amount       =   $this->calculateCom($chData->amount);
                $f_amount       =   (2 * $chData->amount - $a_amount);
                $r_amount       =   0.02 * $chData->amount;

				$closing_balance = 	 $wallet+$f_amount;

                $winner         = Transaction::create([
                    'source_id'        => $ch_id,
                    'user_id'          => $user_id,
                    'amount'           => $f_amount,
                    'a_amount'         => $a_amount,
                    'status'           => 'Won',
                    'remark'           => 'Set winner by admin manually',
                    'ip'               => $request->ip(),
					'closing_balance' =>  $closing_balance

                ]);

                if($winner){
                    $this->updateWallet($f_amount,$user_id);
                    //$this->updateReferral($request, $r_amount, $user_id);

                    $usertData =   User::with('setting')->find($user_id);

                    if(isset($usertData->setting->used_referral) && $usertData->setting->used_referral){
                        $uData  =   User::find($usertData->setting->rf_user_id);

                        $txn    =   Transaction::create([
                            'user_id'       =>  $usertData->setting->rf_user_id,
                            'source_id'     =>  $ch_id,
                            'amount'        =>  $r_amount,
                            'a_amount'      =>  0,
                            'status'        =>  'Referral',
                            'remark'        =>  $usertData->username,
                            'ip'            =>  $request->ip()
                        ]);

                        if($txn){
                            $uData->increment('usd_wallet', $r_amount);
                            $uData->increment('wallet', $r_amount);
                        }
                    }
                }

                return redirect()->back()->with('success', 'Winner set successfully!');
            }

            return redirect()->back()->with('error', 'Unable to set winner!');
        }catch (\Exception $e) {
            $bug = $e->getMessage();
            return response([
                'message'        => $bug
            ],400);
        }
    }

    private function updateWallet($amount,$user_id){
        $walletData =   User::find($user_id);
        $walletData->increment('usd_wallet',$amount);
        $walletData->increment('wallet',$amount);
    }

    private function setChResult($ch_id, $is_cancel, $user_id){
        $insert = ChallengeResult::create([
            'ch_id'            => $ch_id,
            'sub_by'           => 'Admin',
            'is_cancel'        => $is_cancel,
            'user_id'          => $user_id,
        ]);

    }

    private function setChStatus($ch_id){
        $data   =   Challenge::find($ch_id);
        $data->update([
            'status'             => 0
        ]);

        return 1;
    }

    // function calculateCom($amount) {
    //     $commission = 0;
    //     $prize = 0;

    //     if ($amount >= 50 && $amount < 250) {
    //         // 10% commission for bets between 50 and 250
    //         $commission = $amount * 0.10;
    //         $prize = $amount - $commission;
    //     } elseif ($amount >= 250 && $amount <= 500) {
    //         // Fixed 25 rupees commission for bets between 250 and 500
    //         $commission = 25;
    //         $prize = $amount - $commission;
    //     } elseif ($amount > 500) {
    //         // 5% commission for bets above 500
    //         $commission = $amount * 0.05;
    //         $prize = $amount - $commission;
    //     } else {
    //         // If the amount doesn't meet any conditions, set prize equal to amount
    //         $prize = $amount;
    //     }

    //     return [
    //         'commission' => $commission,
    //         'prize' => $prize
    //     ];
    // }

    private function updateReferral($request, $amount, $user_id){
        $usertData =   User::with('setting')->find($user_id);

        if(isset($usertData->setting->used_referral) && $usertData->setting->used_referral){
            $uData  =   User::find($usertData->setting->rf_user_id);

            $txn    =   Transaction::create([
                'user_id'       =>  $usertData->setting->rf_user_id,
                'source_id'     =>  $request->ch_id,
                'amount'        =>  $amount,
                'a_amount'      =>  0,
                'status'        =>  'Referral',
                'remark'        =>  $usertData->username,
                'ip'            =>  $request->ip()
            ]);

            if($txn){
                $uData->increment('usd_wallet', $amount);
                $uData->increment('wallet', $amount);
            }
        }

    }

    private function getTransaction($ch_id, $user_id, $status){
        $transaction       =   Transaction::where('source_id',$ch_id)->where('user_id',$user_id)
                                ->where('status',$status)->first();

        return $transaction;
    }

    public function roomCode($ch_id){
        $challenge   =   Challenge::find($ch_id);
        if(($challenge->status != 0 || $challenge->status != 5) && $challenge->rcode == 0){
            return view('admin/challenge/room-code',compact('challenge'));
        }else{
            return redirect()->back()->with('error', 'Unable to change room code');
        }
        //echo "<pre>";print_r($user_id);die;ManualPayment

    }

    public function updateRoomCode(Request $request)
    {
        $request->validate( [
			'rcode' => 'required|numeric|unique:challenges,rcode',
			'ch_id'   => 'required|numeric'
		]);
        $challenge   =   Challenge::find($request->ch_id);

        if(($challenge->status != 0 || $challenge->status != 5) && $challenge->rcode == 0){
            $challenge->update([
                'rcode' =>  $request->rcode,
            ]);
            return response()->json(['data'=>$challenge]);
        }else{
            return response([
                'message'        => 'Unable to process your request at this time!'
            ],400);
        }
    }

}

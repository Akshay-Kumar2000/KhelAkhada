<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Challenge;
use App\ChallengeResult;
use App\Transaction;
use App\User;
use App\UserSetting;
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
    {
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
        }elseif($request->status == 'Play_wait'){
            $challenges = Challenge::leftJoin('user_results', 'challenges.id', '=', 'user_results.ch_id')
    ->select('challenges.id', 'challenges.amount', 'challenges.type', 'challenges.c_id', 'challenges.cname', 'challenges.o_id', 'challenges.oname', 'challenges.rcode', 'challenges.status', 'challenges.ip', 'challenges.created_at', 'challenges.updated_at', 'challenges.deleted_at')
    ->where(function($query) {
        return $query->where('status', 2)
            ->orWhere('status', 3)
            ->orWhere('status', 4);
    })
    ->groupBy('challenges.id', 'challenges.amount', 'challenges.type', 'challenges.c_id', 'challenges.cname', 'challenges.o_id', 'challenges.oname', 'challenges.rcode', 'challenges.status', 'challenges.ip', 'challenges.created_at', 'challenges.updated_at', 'challenges.deleted_at')
    ->havingRaw('COUNT(user_results.id) = 1')
    ->with(['creator', 'opponent', 'result'])
    ->paginate($this->paging);

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
    public function changeStatus($status,$uid)
    {
        // return $status;

        // if($status == 0){
        //     $status = 1;
        // }elseif($status == 1){
        //     $status = 0;
        // }
        $data   = UserSetting::where('user_id',$uid)->first();
        $data1=User::find($uid);
        // return $data;
        if(isset($data)){
            // return $status;
            $data->status=$status;
            $data->save();
            $data1->status=$status;
            $data1->save();
            // $data->update(['status' => $status]);
            // return $status;
            return redirect()->back()->with('success', 'Status updated successfully!');
        }else{
            return redirect()->back()->with('error', 'Record not found');
        }
    }
    public function ApiResult($resultowner){
        // return $resultowner;
        if($resultowner == "waiting" || $resultowner == "Waiting"){
            return '<span style="color:blue;font-weight:900;">Waiting</span>';
        }elseif($resultowner == "Won" || $resultowner == "won"){
            return '<span style="color:green;font-weight:900;">Winner</span>';
        }elseif($resultowner == "Exit" || $resultowner == "exit"){
            return '<span style="color:red;font-weight:900;">Exit</span>';
        }elseif($resultowner == "Playing" || $resultowner == "playing"){
            return '<span style="color:orange;font-weight:900;">Playing</span>';
        }elseif($resultowner == "Hold" || $resultowner == "hold"){
            return '<span style="color:orange;font-weight:900;">Hold</span>';
        }else{
            return '<span style="color:orange;font-weight:900;">Lost</span>';
        }
    }

    // this is the new api callback function for the roomcode
    public function details($id)
    {
        // Fetch the challenge details along with related data
        $challenge = Challenge::with(['creator', 'opponent', 'result', 'usersresult', 'transactions'])->where('id', $id)->first();
        $RoomCode = $challenge->rcode;

        // Prepare the payload for the new API
        $payload = [
            'roomCode' => $RoomCode,
            'purpose'  => 'result'  // Set purpose to "Result"
        ];

        try {
            // Initialize Guzzle client
            $client = new \GuzzleHttp\Client();

            // Send the POST request to the new API endpoint
            $response = $client->post('https://akadda.com/api/cashfree-callback1', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json'    => $payload,  // Guzzle automatically converts array to JSON
                'timeout' => 30
            ]);

            // Decode the JSON response from the API
            $responseData = json_decode($response->getBody()->getContents());

            // Check if the API response indicates success
            if (isset($responseData->success) && $responseData->success) {
                if (isset($responseData->data->result)) {
                    // Extract the game result data
                    $gameResult = $responseData->data->result;

                    // Default statuses to "Hold" before updating
                    $resultowner = $this->ApiResult("Hold");
                    $resultplayer1 = $this->ApiResult("Hold");

                    // Check if the game status is "Running" or "Finished"
                    if ($gameResult->eStatus == 'Running') {
                        $resultowner = $this->ApiResult('Playing');
                        $resultplayer1 = $this->ApiResult('Playing');
                    } elseif ($gameResult->eStatus == 'Finished') {
                        // If game is finished, directly use the API status for each player
                        $creatorId = $challenge->creator_id;
                        $statusPlayer1 = $gameResult->aPlayers[0]->eStatus;
                        $statusPlayer2 = $gameResult->aPlayers[1]->eStatus;

                        // Map the statuses to the right players
                        if ($gameResult->aPlayers[0]->_userId == $creatorId) {
                            $resultowner = $this->ApiResult($statusPlayer1);
                            $resultplayer1 = $this->ApiResult($statusPlayer2);
                        } else {
                            $resultowner = $this->ApiResult($statusPlayer2);
                            $resultplayer1 = $this->ApiResult($statusPlayer1);
                        }
                    } else {
                        // Set default values if the game is in an unexpected state
                        $resultowner = $this->ApiResult("Hold");
                        $resultplayer1 = $this->ApiResult("Hold");
                    }
                } else {
                    // No game result data found, set default values
                    $resultowner = $this->ApiResult("Hold");
                    $resultplayer1 = $this->ApiResult("Hold");
                }
            } else {
                // API response indicates failure
                $resultowner = $this->ApiResult("Updating..");
                $resultplayer1 = $this->ApiResult("Updating..");
            }
        } catch (\Exception $e) {
            // Log the exception error message
            // \Log::error('Error in fetching game result: ' . $e->getMessage());
            $resultowner = $this->ApiResult("Error");
            $resultplayer1 = $this->ApiResult("Error");
        }

        // Return the view with the challenge and result data
        return view('admin/challenge/details', compact('challenge', 'resultowner', 'resultplayer1'));
    }

    // this is the old api for the roomcode
    // public function details($id)
    // {
    //     $challenge  = Challenge::with(['creator','opponent','result','usersresult','transactions'])->where('id',$id)->first();
    //     $curl = curl_init();
    //     curl_setopt_array($curl, [
    //     	CURLOPT_URL => "https://apiv2.ludoadda.co.in/api/all/result?roomcode=".$challenge->rcode."&apikey=5c055f88",
    //     	CURLOPT_RETURNTRANSFER => true,
    //     	CURLOPT_ENCODING => "",
    //     	CURLOPT_MAXREDIRS => 10,
    //     	CURLOPT_TIMEOUT => 30,
    //     	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     	CURLOPT_CUSTOMREQUEST => "GET"
    //     ]);

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //         return $err;
    //     } else {
    //         $dataaa = json_decode($response);
    //         if(isset($dataaa->result->ownerid) && $dataaa->status){
    //             $dataa = $dataaa->result;
    //             $CreatorPlayer = 2;
    //             $resultowner = isset($dataa->ownerstatus) ? $this->ApiResult($dataa->ownerstatus) : "";
    //             $resultplayer1 = isset($dataa->player1status) ? $this->ApiResult($dataa->player1status) : "";
    //         }else{
    //             $resultplayer1 = $this->ApiResult("Hold");
    //             $resultowner = $this->ApiResult("Hold");
    //         }
    //     }
    //     return view('admin/challenge/details',compact('challenge','resultowner','resultplayer1'));
    // }

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
    public function cancelGameInParameter($ch_id)
    {
        try
        {
            $update = $this->setChStatus($ch_id);

            if($update){
                $this->setChResult($ch_id, 1, NULL);
            }
            $transactions       =   Transaction::where('source_id',$ch_id)->where(function($query){
                                        return $query
                                        ->where('status','Create')
                                        ->orWhere('status','Play');
                                    })->get();

            foreach($transactions as $key => $val){
				$user_data = User::where('id', $val->user_id)->first();
			$wallet = $user_data->wallet;

				$closing_balance = 	 $wallet+$val->amount;

                $refund = Transaction::create([
                    'source_id'        => $ch_id,
                    'user_id'          => $val->user_id,
                    'amount'           => $val->amount,
                    'status'           => 'Cancel',
                    'remark'           => 'Cancel refund by admin',
                    'ip'               => '00',
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
			$user_data = User::where('id', $request->user_id)->first();
			$wallet = $user_data->wallet;
                $a_amount       =   $this->calculateCom($chData->amount);
                $f_amount       =   (2 * $chData->amount - $a_amount);
                $p_amount       =   ((2 * $chData->amount - $a_amount)-$chData->amount);
                $r_amount       =   0.02 * $chData->amount;

				$closing_balance = 	 $wallet+$f_amount;
                $winner         = Transaction::create([
                    'source_id'        => $request->ch_id,
                    'user_id'          => $request->user_id,
                    'amount'           => $p_amount,
                    'a_amount'         => $a_amount,
                    'status'           => 'Won',
                    'remark'           => 'Set winner by admin',
                    'ip'               => $request->ip(),
					'closing_balance'  =>  $closing_balance
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
                    // $this->updateReferral($request, $r_amount, $user_id);

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
        $walletData->increment('win_amount',$amount);
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

    private function calculateCom($amount){
        if($amount > 0 && $amount <=250){
            $a_amount	=	10/100*($amount);
        }elseif($amount > 250 && $amount <=500){
            $a_amount	=	25;
        }elseif($amount > 500){
        $a_amount	=	5/100*($amount); //5/100
        }
        return $a_amount;
    }

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
                $uData->increment('win_amount', $amount);
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

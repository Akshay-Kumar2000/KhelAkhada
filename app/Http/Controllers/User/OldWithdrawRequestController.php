<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\WithdrawRequest;
use App\User;
use App\Setting;
use App\Challenge;
use App\Transaction;
use App\UserData;
use Auth;
use DB;

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
        $WithdrawalStatus = Setting::where('field_name', 'WithdrawalStatus')->first();

        if (!$WithdrawalStatus) {
            return redirect('/wallet')->with('error', 'Withdrawal status setting not found.');
        }

        $WithdrawalStatusS = $WithdrawalStatus->field_value;

        if ($WithdrawalStatusS == "no") {
            return redirect('/wallet')->with('error', 'Withdrawal currently paused due to some Bank issue, Please try after sometime!');
        }

        $existdata = Transaction::where('user_id', $user_id)->where(function ($query) {
            return $query
                ->where('status', 'Withdrawing')
                ->orWhere('status', 'Withdraw');
        })->where('created_at', '>=', now()->subMinutes(300))->count();

        $winningAmount = $this->findWinningWallet($user_id);

        if ($existdata > 0) {
            return redirect('/wallet')->with('error', 'अगला विथद्रवला 5 घंटा बाद ही लगेगा..');
        }

        $user_kyc = UserData::where('user_id', $user_id)->first();
        return view('user.withdraw-request-page', compact('user_kyc'));
    }

    public function findWinningWallet($user_id)
    {
        $win = User::where('id', $user_id)->sum('win_amount');
        $wallet = User::where('id', $user_id)->sum('wallet');
        if ($win > $wallet) {
            return $wallet;
        }
        return $win;
    }
    public function upiWithdraw(Request $request)
    {
        $user_id = Auth::user()->id;
        $winningAmount = $this->findWinningWallet($user_id);
        return view('user.upi-withdraw', compact('winningAmount'));
    }

    public function bankWithdraw(Request $request)
    {
        $user_id = Auth::user()->id;
        $lastData = WithdrawRequest::where("user_id", $user_id)->orderBy('id', 'desc')->first();
        if (!$lastData) {
            $lastData = null;
        }
        $winningAmount = $this->findWinningWallet($user_id);
        return view('user.bank-withdraw', compact('winningAmount', 'lastData'));
    }

    public function checkUpi($newupiid)
    {
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
            "Origin: " . base64_decode('aHR0cHM6Ly93d3cudHBzbC1pbmRpYS5pbg=='),
            "Referer: " . base64_decode('aHR0cHM6Ly93d3cudHBzbC1pbmRpYS5pbg=='),
            "Sec-Fetch-Dest: empty",
            "Sec-Fetch-Mode: cors",
            "Sec-Fetch-Site: cross-site",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
            'sec-ch-ua: "Not_A Brand";v="99", "Google Chrome";v="109", "Chromium";v="109"',
            "sec-ch-ua-mobile: ?0",
            "sec-ch-ua-platform: 'Windows'",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = '{"vpa_id":"' . $newupiid . '",' . base64_decode('Im1lcmNoYW50VHJhbklkIjoiMjI4Njc1OCIsIm1lcmNoYW50Q29kZSI6IkwyMzM0NDci') . '}';
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $datas = array('status' => "true", 'message' => "verified");
        return json_encode($datas);
        return curl_exec($curl);
        curl_close($curl);
    }
    public function checkupis(Request $r)
    {
        $upiid = $r->upiid;
        return $this->checkUpi($upiid);
    }
    public function sendpayment($amount, $vpa, $orderid, $name)
    {
        $curl = curl_init();
        $json_string = '{
            "APIID": "API1004",
            "Token": "6091fba5-1c3e-47ff-b9dc-0b784818b08b",
            "MethodName": "upitransfer",
            "OrderID": "' . $orderid . '",
            "Name": "' . $name . '",
            "Amount": "' . $amount . '",
            "vpa": "' . $vpa . '"
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
            return array('status' => false, 'data' => json_decode($err));
        } else {
            return array('status' => true, 'data' => json_decode($response));
        }
    }
    public function upiWithdrawPost(Request $request)
    {
        $existupi = $this->checkUpi($request->upi_id);
        $upiexist = json_decode($existupi);
        $upiexist->status = "true";
        $upiexist->message = "User";
        if ($upiexist->status == "true") {
            try {
                $user_id = Auth::user()->id;
                $walletData = User::find($user_id);
                $winningamount = $this->findWinningWallet($user_id);
                $creGames = Challenge::where('c_id', $user_id)->where('status', '!=', 0)->sum('amount');
                $oppGames = Challenge::where('o_id', $user_id)->where('status', '!=', 0)->sum('amount');
                $existdata = Transaction::where('user_id', $user_id)->where(function ($query) {
                    return $query
                        ->where('status', 'Withdrawing')
                        ->orWhere('status', 'Withdraw');
                })->where('created_at', '>=', now()->subMinutes(300))->count();
                // if($existdata > 5){
                // $request->amount = ;
                if ($creGames > 0 || $oppGames > 0) {
                    return response()->json(['error' => 'Please complete remianing games.']);
                }
                if ($existdata > 1) {
                    return response()->json(['error' => 'Add 1 withdrawal in a 5 hours.']);
                }
                if ($walletData->wallet <= 0 || $winningamount < $request->amount) {
                    return response()->json(['error' => 'Insufficient balance or clear your pending game first!']);
                }
                $request->validate([
                    'upi_id' => 'required',
                    'amount' => 'required|numeric|min:300',
                    // 'amount' => 'required|numeric|min:0',
                ]);
                $wallet = $walletData->wallet;
                $amount = $request->amount;
                $transaction = Transaction::create([
                    'user_id' => $user_id,
                    'source_id' => $request->upi_id,
                    'amount' => $request->amount,
                    'status' => 'Withdrawing',
                    'remark' => 'Pending',
                    'ip' => $request->ip(),
                    'closing_balance' => $wallet - $amount,
                ]);
                // return $transaction->id;
                $withdraw = WithdrawRequest::create([
                    'user_id' => $user_id,
                    'amount' => $request->amount,
                    'upi' => $request->upi_id,
                    'type' => 'UPI',
                    'status' => 'Unpaid',
                    'ip' => $request->ip(),
                    'tid' => $transaction->id
                ]);
                //Order id
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                $orderid = '';

                for ($i = 0; $i < 35; $i++) {
                    $orderid .= $characters[rand(0, strlen($characters) - 1)];
                }
                $order_idd = $orderid . $withdraw->id;
                Transaction::where('id', $transaction->id)->update(["source_id" => $order_idd]);
                if ($withdraw) {
                    $ammmmm = $request->amount;
                    if ($winningamount >= $ammmmm) {
                        if ($walletData->wallet >= $walletData->win_amount) {
                            $walletData->decrement('win_amount', $ammmmm);
                        } else {
                            $amwin = $winningamount - $ammmmm;
                            User::where('id', $user_id)->update(['win_amount' => $amwin]);
                        }
                    }
                    $walletData->decrement('wallet', $ammmmm);
                    $sett = Setting::where('field_name', 'auto_withdraw')->first();
                    if ($sett && $sett->field_value == "yes") {
                        $auto_withdraw = $sett->field_value;
                        $gateway_status = $this->sendpayment($request->amount, $request->upi_id, $order_idd, $upiexist->message);
                        if ($gateway_status['status']) {
                            $GatewayPitput = $gateway_status['data'];
                            // return $GatewayPitput->status;
                            if ($GatewayPitput->status == "failed" || $GatewayPitput->status == "") {
                                // return response()->json(['upi'=>$upiexist->message,'error'=>$GatewayPitput->mess,'wallet_amount' => number_format($walletData->wallet,2)]);
                                Setting::where('field_name', 'withdraw_gateway_log')->update(["field_value" => $GatewayPitput->mess]);
                                return response()->json(['upi' => $upiexist->message, 'success' => 'Withdraw request successfully, Paid after some time!', 'wallet_amount' => number_format($walletData->wallet, 2)]);
                            }
                            if ($GatewayPitput->status == "success") {
                                Setting::where('field_name', 'withdraw_gateway_log')->update(["field_value" => '']);
                                return response()->json(['upi' => $upiexist->message, 'success' => 'Withdraw request paid successfully!', 'wallet_amount' => number_format($walletData->wallet, 2)]);
                            }
                            // return $GatewayPitput;
                            Setting::where('field_name', 'withdraw_gateway_log')->update(["field_value" => $GatewayPitput->mess]);
                            return response()->json(['upi' => $upiexist->message, 'success' => 'Withdraw request paid successfully!', 'wallet_amount' => number_format($walletData->wallet, 2)]);
                            // return response()->json(['upi'=>$upiexist->message,'error'=>$GatewayPitput->mess,'wallet_amount' => number_format($walletData->wallet,2)]);
                        }
                    } else {
                        return response()->json(['upi' => $upiexist->message, 'success' => 'Withdraw request paid successfully!', 'wallet_amount' => number_format($walletData->wallet, 2)]);
                    }
                }
            } catch (\Exception $e) {
                $bug = $e->getMessage();
                return $bug;
            }
        } else {
            return response()->json(['error' => 'Invalid Upi Id!']);
        }
    }

    public function bankWithdrawPost(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $walletData = User::find($user_id);
            $winningamount = $this->findWinningWallet($user_id);
            $creGames = Challenge::where('c_id', $user_id)->where(function ($query) {
                return $query
                    ->where('status', 1)
                    ->orWhere('status', 2);
            })->sum('amount');
            $oppGames = Challenge::where('o_id', $user_id)->where(function ($query) {
                return $query
                    ->where('status', 1)
                    ->orWhere('status', 2);
            })->sum('amount');
            if ($walletData->wallet <= 0 || $winningamount < $request->amount) {
                return response()->json(['error' => 'Insufficient balance or clear your pending game first!']);
            }
            $request->validate([
                'ifsc_code' => 'required',
                'account_no' => 'required|numeric',
                'amount' => 'required|numeric|min:300',
            ]);
            $transaction = Transaction::create([
                'user_id' => $user_id,
                'source_id' => $request->account_no,
                'amount' => $request->amount,
                'status' => 'Withdrawing',
                'remark' => 'Pending',
                'ip' => $request->ip()
            ]);
            $withdraw = WithdrawRequest::create([
                'user_id' => $user_id,
                'amount' => $request->amount,
                'ifsc_code' => $request->ifsc_code,
                'account_no' => $request->account_no,
                'upi' => $request->acc_holder_name,
                'type' => 'Bank',
                'ip' => $request->ip(),
                'tid' => $transaction->id
            ]);

            if ($withdraw) {
                $ammmmm = $request->amount;
                if ($winningamount >= $ammmmm) {
                    if ($walletData->wallet >= $walletData->win_amount) {
                        $walletData->decrement('win_amount', $ammmmm);
                    } else {
                        $amwin = $winningamount - $ammmmm;
                        User::where('id', $user_id)->update(['win_amount' => $amwin]);
                    }
                }
                $walletData->decrement('wallet', $ammmmm);

                return response()->json(['success' => 'Withdraw request sent successfully!', 'wallet_amount' => number_format($walletData->wallet, 2)]);
            } else {
                return response()->json(['success' => 'Withdraw request failed!', 'wallet_amount' => 0]);
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
}


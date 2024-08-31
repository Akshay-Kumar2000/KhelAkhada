<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Transaction;
use App\UserSetting;
use App\PaymentOrder; // Ensure you include the model for PaymentOrder
use Auth;
use DB;
use Log;

class TransactionController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $page = $request->page ?? 0;
        $user_id = Auth::user()->id;

        $transactions = Transaction::where('user_id', $user_id)->where(function ($query) {
            $query
                ->where('status', 'Wallet')
                ->orWhere('status', 'Withdraw')
                ->orWhere('status', 'Withdraw_cancel')
                ->orWhere('status', 'Withdrawing')
                ->orWhere('status', 'Admin_add');
        })->orderby('id', 'desc')->paginate(50);

        return view('user.history', compact('transactions', 'page'));
    }

    public function gameHistory(Request $request)
    {
        $page = $request->page ?? 0;
        $user_id = Auth::user()->id;

        $transactions = Transaction::with('challengeresult', 'challenge')->where('user_id', $user_id)->where(function ($query) {
            $query
                ->where('status', 'Create')
                ->orWhere('status', 'Play')
                ->orWhere('status', 'Won')
                ->orWhere('status', 'Cancel');
        })->orderby('created_at', 'desc')->paginate(100);

        return view('user.game-history', compact('transactions', 'page'));
    }

    public function referral(Request $request)
    {
        $page = $request->page ?? 0;
        $user_id = Auth::user()->id;
        $transactions = Transaction::where('user_id', $user_id)->where('status', 'Referral')->orderby('created_at', 'desc')->paginate(50);
        $uReferral = UserSetting::where('used_referral', Auth::user()->mobile)->count();

        return view('user.referral-history', compact('transactions', 'page'));
    }

    public function leaderBord(Request $request)
    {
        $leaders = Transaction::with('playername')->Where('status', 'Won')
            ->select([DB::raw("count(amount) as win_count"), DB::raw("SUM(amount - a_amount) as win_amount"), 'user_id'])
            ->groupBy('user_id')->orderby('win_amount', 'desc')->take(50)->get();

        return view('user.leaders', compact('leaders'));
    }

    /**
     * Update the payment status based on the payment gateway's response.
     *
     * @param string $order_id
     * @param string $status
     * @return void
     */
    public function updatePaymentStatus($order_id, $status)
    {
        Log::info('Updating payment status', ['order_id' => $order_id, 'status' => $status]);

        $paymentOrder = PaymentOrder::where('order_id', $order_id)->first();

        if ($paymentOrder) {
            $paymentOrder->status = $status === 'success' ? 1 : 0; // 1 for success, 0 for fail
            $paymentOrder->save();

            Log::info('Payment order found and updated', ['order_id' => $order_id, 'status' => $paymentOrder->status]);

            // Optionally, you can update the related Transaction status as well
            $transaction = Transaction::where('source_id', $order_id)->first();
            if ($transaction) {
                $transaction->status = $status === 'success' ? 'Wallet' : 'Fail';
                $transaction->save();

                Log::info('Transaction status updated', ['transaction_id' => $transaction->id, 'status' => $transaction->status]);
            } else {
                Log::warning('Transaction not found for order_id', ['order_id' => $order_id]);
            }
        } else {
            Log::warning('Payment order not found', ['order_id' => $order_id]);
        }
    }

    /**
     * Handle the payment status callback.
     *
     * This is an example of how you might handle the callback from the payment gateway.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handlePaymentStatus(Request $request)
    {
        $order_id = $request->input('order_id');
        $pg_status = $request->input('pgstatus'); // 'success' or 'fail'

        // Call the updatePaymentStatus method
        $this->updatePaymentStatus($order_id, $pg_status);

        return response()->json(['success' => true]);
    }
}

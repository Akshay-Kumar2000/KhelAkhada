<?php

namespace App\Jobs;

use App\PaymentOrder;
use App\Transaction;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CheckPendingPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $orders = PaymentOrder::where('status', 0)->get();
        Log::info("Processing pending payments: " . $orders);

        foreach ($orders as $order) {
            $client = new Client();
            $res = $client->request('GET', 'https://upipg.gtelararia.com/order/statuscheck.php?loginid=9257024792&apikey=7pacgmqbzx&request_id=' . $order->order_id);

            if ($res->getStatusCode() == 200) {
                $response_data = $res->getBody()->getContents();
                $response = json_decode($response_data, true);
                Log::info("API Response from Status Check Jobs Folder: " . $response_data);

                if ($response['status'] == 'success') {
                    $user = User::find($order->user_id);
                    Log::info("User Wallet Before from Status Check Jobs Folder: " . $user->wallet);

                    $wallet = $user->wallet;
                    $txn = Transaction::create([
                        'user_id' => $order->user_id,
                        'source_id' => $order->order_id,
                        'amount' => $order->amount,
                        'a_amount' => 0,
                        'status' => 'Wallet',
                        'remark' => 'Upigateway wallet recharge',
                        'ip' => "127.0.0.1",
                        'closing_balance' => $wallet + $order->amount,
                    ]);

                    $order->status = 1;
                    Log::info("Transaction Created from Status Check Jobs Folder: " . $txn->id);
                    $user->increment('wallet', $order->amount);
                    Log::info("User Wallet After from Status Check Jobs Folder: " . ($wallet + $order->amount));
                } elseif ($response['status'] == 'fail') {
                    $order->status = 2;
                    Log::info("Payment failed for order ID from Status Check Jobs Folder: " . $order->id);
                }

                $order->save();
                Log::info("Order status updated from Status Check Jobs Folder: " . $order->status);
            }
        }
    }
}

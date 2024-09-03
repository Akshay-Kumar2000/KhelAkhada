<?php
namespace App\Jobs;

use App\PaymentOrder;
use App\User;
use App\Transaction;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payOrder;

    public function __construct(PaymentOrder $payOrder)
    {
        $this->payOrder = $payOrder;
    }

    public function handle()
    {
        $payOrder = $this->payOrder;

        Log::info("Processing order ID: " . $payOrder->id);

        try {
            $client = new Client();
            $res = $client->request('GET', 'https://upipg.gtelararia.com/order/statuscheck.php', [
                'query' => [
                    'loginid' => '9257024792',
                    'apikey' => '7pacgmqbzx',
                    'request_id' => $payOrder->order_id
                ]
            ]);

            if ($res->getStatusCode() == 200) {
                $response_data = $res->getBody()->getContents();
                $response = json_decode($response_data, true);
                Log::info("API Response: " . $response_data);

                $user_id = $payOrder->user_id;
                Log::info("User ID: " . $user_id);

                if ($response['status'] == 'success') {
                    $user_data = User::find($user_id);
                    Log::info("User Wallet Before: " . $user_data->wallet);

                    $wallet = $user_data->wallet;

                    $txn = Transaction::create([
                        'user_id' => $user_id,
                        'source_id' => $payOrder->order_id,
                        'amount' => $payOrder->amount,
                        'a_amount' => 0,
                        'status' => 'Wallet',
                        'remark' => 'Upigateway wallet recharge',
                        'ip' => request()->ip(),
                        'closing_balance' => $wallet + $payOrder->amount,
                    ]);

                    Log::info("Transaction Created: " . $txn->id);

                    $payOrder->status = 1;
                    $payOrder->save();

                    User::where('id', $user_id)->increment('wallet', $payOrder->amount);
                    Log::info("User Wallet After: " . ($wallet + $payOrder->amount));

                } elseif ($response['status'] == 'fail') {
                    $payOrder->status = 2;
                    $payOrder->save();
                    Log::info("Payment failed for order ID: " . $payOrder->id);
                }
            } else {
                Log::error("Failed to get a successful response from the payment gateway for order ID: " . $payOrder->id);
            }

        } catch (\Exception $e) {
            Log::error("Error processing order ID: " . $payOrder->id . ". Exception: " . $e->getMessage());
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PaymentOrder;
use GuzzleHttp\Client;

class CheckPaymentStatus extends Command
{
    protected $signature = 'payment:check-status';
    protected $description = 'Check the status of unpaid payment orders';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $orders = PaymentOrder::where('status', 0)->get();

        foreach ($orders as $order) {
            try {
                $client = new Client();
                $response = $client->request('GET', 'https://upipg.gtelararia.com/order/statuscheck.php', [
                    'query' => [
                        'loginid' => '9257024792',
                        'apikey' => '7pacgmqbzx',
                        'request_id' => $order->order_id
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if ($data['status'] == 'success') {
                    $order->status = 1;
                    $order->save();
                    // Update user wallet or perform other actions
                } elseif ($data['status'] == 'fail') {
                    $order->status = 2;
                    $order->save();
                }
            } catch (\Exception $e) {
                \Log::error("Error checking payment status for order {$order->order_id}: " . $e->getMessage());
            }
        }
    }
}

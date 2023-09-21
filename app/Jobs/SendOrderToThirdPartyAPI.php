<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class SendOrderToThirdPartyAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderData;

    /**
     * Create a new job instance.
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->orderData;

        try {
            $client = new Client();
    
            $response = $client->post(env('API_ENDPOINT') . '/order', [
                'json' => [
                    'Order_ID' => $order->id,
                    'Customer_Name' => $order->customer_name,
                    'Order_Value' => $order->order_value,
                    'Order_Date' => $order->order_date,
                    'Order_Status' => $order->order_status,
                    'Process_ID' => $order->process_id,
                ],
                'verify' => false, // Disable SSL certificate verification (not recommended for production)
            ]);
    
            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody(), true);
            } else {
                Log::error('API request failed with status code: ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            Log::error('Error making API request: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
        }
    }
}

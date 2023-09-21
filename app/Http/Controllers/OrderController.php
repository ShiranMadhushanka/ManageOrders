<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use GuzzleHttp\Exception\RequestException;
use App\Jobs\SendOrderToThirdPartyAPI;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'order_value' => 'required|numeric',
        ]);

        $order = new Order();
        $order->customer_name = $request->input('customer_name');
        $order->order_value = $request->input('order_value');
        $order->order_date = date('Y-m-d H:i:s');
        $order->process_id = rand(1, 10);
        $order->order_status = 'Processing';
        $order->save();

        try {
            SendOrderToThirdPartyAPI::dispatch($order);
            return response()->json([
                'Order_ID' => $order->id,
                'Process_ID' => $order->process_id,
                'Status' => 'Order Created and Submitted to 3rd Party API',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error submitting order to 3rd Party API: ' . $e->getMessage()], 500);
        }
    }
}

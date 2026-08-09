<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = filter_var(config('services.midtrans.is_production'), FILTER_VALIDATE_BOOLEAN);
        Config::$isSanitized = filter_var(config('services.midtrans.is_sanitized'), FILTER_VALIDATE_BOOLEAN);
        Config::$is3ds = filter_var(config('services.midtrans.is_3ds'), FILTER_VALIDATE_BOOLEAN);
    }

    public function index()
    {
        $product = [
            'name' => 'Kaos Tim Lomba',
            'price' => 150000,
        ];

        return view('checkout', compact('product'));
    }

    public function pay(Request $request)
    {
        $orderId = 'ORDER-' . uniqid();

        $order = Order::create([
            'order_id' => $orderId,
            'product_name' => 'Kaos Tim Lomba',
            'amount' => 150000,
            'customer_name' => $request->input('name', 'Guest'),
            'customer_email' => $request->input('email', 'guest@example.com'),
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
            ],
            'item_details' => [
                [
                    'id' => 'kaos-tim-lomba',
                    'price' => $order->amount,
                    'quantity' => 1,
                    'name' => $order->product_name,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $order->update(['snap_token' => $snapToken]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $order->order_id,
        ]);
    }

    public function notification(Request $request)
    {
        return response()->json(['message' => 'not implemented yet']);
    }
}

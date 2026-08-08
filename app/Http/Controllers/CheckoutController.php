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
        // Ambil kredensial dari config/services.php (yang membaca dari .env)
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman produk + tombol beli.
     * Ini contoh data statis; di project nyata, ambil dari database.
     */
    public function index()
    {
        $product = [
            'name'  => 'Kaos Tim Lomba',
            'price' => 150000,
        ];

        return view('checkout', ['product' => $product]);
    }

    /**
     * Dipanggil saat user klik tombol Bayar.
     * Membuat Order baru, lalu minta snap_token dari Midtrans.
     */
    public function pay(Request $request)
    {
        $orderId = 'ORDER-' . uniqid();

        // 1. Simpan order dengan status pending dulu
        $order = Order::create([
            'order_id'        => $orderId,
            'product_name'    => 'Kaos Tim Lomba',
            'amount'          => 150000,
            'customer_name'   => $request->input('name', 'Guest'),
            'customer_email'  => $request->input('email', '[email protected]'),
            'status'          => 'pending',
        ]);

        // 2. Susun parameter transaksi sesuai format Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_id,
                'gross_amount' => $order->amount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email'      => $order->customer_email,
            ],
            'item_details' => [
                [
                    'id'       => 'kaos-tim-lomba',
                    'price'    => $order->amount,
                    'quantity' => 1,
                    'name'     => $order->product_name,
                ],
            ],
        ];

        // 3. Minta snap_token ke Midtrans (di sinilah koneksi ke API terjadi)
        $snapToken = Snap::getSnapToken($params);

        // 4. Simpan token untuk referensi (opsional tapi berguna untuk debugging)
        $order->update(['snap_token' => $snapToken]);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id'   => $order->order_id,
        ]);
    }

    /**
     * LANGKAH LANJUTAN (belum diimplementasi di starter ini):
     * Endpoint ini akan dipanggil OTOMATIS oleh server Midtrans (bukan oleh
     * browser user) setiap kali status pembayaran berubah. Di sinilah Anda
     * update status order jadi 'paid', 'failed', dsb — jangan mengandalkan
     * redirect di frontend saja karena user bisa menutup browser lebih awal.
     *
     * Daftarkan URL ini di dashboard Midtrans > Settings > Configuration >
     * Payment Notification URL.
     */
    public function notification(Request $request)
    {
        // TODO: validasi signature_key, lalu update Order berdasarkan order_id
        // dan transaction_status yang dikirim Midtrans.
        return response()->json(['message' => 'not implemented yet']);
    }
}

<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Mencatat semua request yang masuk untuk debugging
        Log::info('Midtrans Notification Received:', $request->all());

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notification = new Notification();
            
            $orderNumber = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::warning("Midtrans Callback: Order dengan nomor {$orderNumber} tidak ditemukan.");
                return response()->json(['message' => 'Order not found.'], 404);
            }

            if ($order->payment_status === 'paid') {
                return response()->json(['message' => 'Order already paid.'], 200);
            }

            if (($transactionStatus == 'capture' || $transactionStatus == 'settlement') && $fraudStatus == 'accept') {
                
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'preparing'
                ]);

                Transaction::create([
                    'order_id' => $order->id,
                    // ✅ PERBAIKAN: Membuat nomor transaksi baru yang lebih rapi
                    'transaction_number' => 'MT-' . date('ymd') . '-' . strtoupper(substr($order->id, 0, 4)),
                    'customer_name' => $order->customer_name,
                    'subtotal' => $order->subtotal,
                    'service_fee' => $order->service_fee,
                    'total' => $order->total,
                    'payment_method' => 'midtrans',
                    'status' => 'completed',
                    'transaction_date' => now(),
                    // Menyimpan ID asli Midtrans di kolom referensi untuk pelacakan
                    'payment_gateway_reference' => $notification->transaction_id,
                ]);

                Log::info("Callback Midtrans: Pembayaran untuk order {$orderNumber} BERHASIL diproses.");
            
            } else if ($transactionStatus == 'expire' || $transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                $order->update(['status' => 'cancelled']);
                Log::warning("Callback Midtrans: Pembayaran untuk order {$orderNumber} GAGAL/DIBATALKAN.");
            }
            
            return response()->json(['message' => 'Notifikasi berhasil diproses.']);

        } catch (\Exception $e) {
            Log::error("Error Callback Midtrans: " . $e->getMessage());
            return response()->json(['message' => 'Error memproses notifikasi.'], 500);
        }
    }
}
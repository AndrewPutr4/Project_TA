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
        Log::info('-----------------------------------------');
        Log::info('LANGKAH 1: Notifikasi Midtrans Diterima.');
        Log::info('Payload:', $request->all());

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Log::info('LANGKAH 2: Konfigurasi Midtrans Selesai.');

        try {
            $notification = new Notification();
            Log::info('LANGKAH 3: Objek Notifikasi Berhasil Dibuat. (Server Key BENAR)');
            
            $orderNumberFromMidtrans = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            Log::info("LANGKAH 4: Data Notifikasi Dibaca. Order ID: {$orderNumberFromMidtrans}, Status: {$transactionStatus}");

            // Pisahkan nomor order asli dari timestamp jika ada
            $parts = explode('-', $orderNumberFromMidtrans);
            if (count($parts) > 1) {
                // Cek jika bagian terakhir adalah angka (timestamp)
                if (is_numeric(end($parts))) {
                    array_pop($parts); // Hapus bagian terakhir (timestamp)
                }
            }
            $originalOrderNumber = implode('-', $parts);
            Log::info("LANGKAH 5: Mencari Order di DB dengan Nomor: {$originalOrderNumber}");

            $order = Order::where('order_number', $originalOrderNumber)->first();

            if (!$order) {
                Log::warning("GAGAL: Order dengan nomor {$originalOrderNumber} tidak ditemukan di database.");
                return response()->json(['message' => 'Order not found.'], 404);
            }
            Log::info("LANGKAH 6: Order #{$order->order_number} Ditemukan. Status saat ini: {$order->status}, Pembayaran: {$order->payment_status}");

            if ($order->payment_status === 'paid') {
                Log::info("INFO: Order #{$order->order_number} sudah lunas. Proses dihentikan.");
                return response()->json(['message' => 'Order already paid.'], 200);
            }

            if (($transactionStatus == 'capture' || $transactionStatus == 'settlement') && $fraudStatus == 'accept') {
                Log::info("LANGKAH 7: Transaksi Sukses. Memulai update status...");
                
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'preparing'
                ]);
                Log::info("LANGKAH 8: Status Order #{$order->order_number} BERHASIL di-update menjadi LUNAS & PREPARING.");

                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_number' => 'MT-' . date('ymd') . '-' . strtoupper(substr($order->id, 0, 4)),
                    'customer_name' => $order->customer_name,
                    'subtotal' => $order->subtotal,
                    'service_fee' => $order->service_fee,
                    'total' => $order->total,
                    'payment_method' => 'midtrans',
                    'status' => 'completed',
                    'transaction_date' => now(),
                    'payment_gateway_reference' => $notification->transaction_id,
                ]);
                Log::info("LANGKAH 9: Catatan Transaksi untuk Order #{$order->order_number} BERHASIL dibuat.");
            
            } else {
                Log::warning("INFO: Status transaksi '{$transactionStatus}' tidak memenuhi syarat untuk update.");
            }
            
            return response()->json(['message' => 'Notifikasi berhasil diproses.']);

        } catch (\Exception $e) {
            Log::error("!!! KRITIS: Terjadi Error di Callback Midtrans !!!");
            Log::error("Pesan Error: " . $e->getMessage());
            Log::error("Lokasi Error: " . $e->getFile() . " di baris " . $e->getLine());
            return response()->json(['message' => 'Error memproses notifikasi.'], 500);
        }
    }
}

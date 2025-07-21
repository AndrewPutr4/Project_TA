<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    /**
     * Mengatur konfigurasi Midtrans saat controller diinisialisasi.
     */
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Membuat Snap Token untuk pembayaran via Midtrans.
     * Ini dipanggil oleh JavaScript dari halaman pembayaran.
     */
    public function createMidtransSnapToken(Order $order)
    {
        // Mencegah pembuatan token jika order sudah lunas.
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order ini sudah lunas.'], 400);
        }

        $params = [
            'transaction_details' => [
                // Menggunakan order_number yang unik sebagai ID untuk Midtrans.
                'order_id' => $order->order_number,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat sesi pembayaran.'], 500);
        }
    }

    /**
     * Menampilkan halaman formulir pembayaran.
     * Alurnya: Kasir buat order di POS -> diarahkan ke route ini.
     */
    public function create(Order $order)
    {
        // Hanya order yang belum dibayar yang bisa masuk ke halaman ini.
        if ($order->payment_status === 'paid') {
            return redirect()->route('kasir.orders.index')
                             ->with('info', 'Order #' . $order->order_number . ' sudah lunas.');
        }

        $order->load('orderItems');
        
        return view('kasir.transactions.create', compact('order'));
    }

    /**
     * Menyimpan data transaksi setelah pembayaran (baik Tunai maupun Midtrans).
     * Ini adalah gerbang utama setelah tombol "Proses Pembayaran" ditekan.
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,cashless',
            'cash_received' => 'nullable|numeric|required_if:payment_method,cash|gte:0',
            'notes' => 'nullable|string|max:500',
            'midtrans_payload' => 'nullable|json'
        ]);

        DB::beginTransaction();
    try {
        if ($order->payment_status === 'paid' || Transaction::where('order_id', $order->id)->exists()) {
            DB::rollBack();
            return redirect()->route('kasir.orders.index')->with('info', 'Pembayaran untuk order ini sudah diproses.');
        }

        $totalAmount = $order->total;
        
        if ($request->payment_method === 'cashless') {
            $payload = json_decode($request->midtrans_payload, true);
            
            Transaction::create([
                'order_id' => $order->id,
                'kasir_id' => $request->user()->id,
                // ✅ PERBAIKAN: Membuat nomor transaksi baru yang lebih rapi
                'transaction_number' => 'MT-' . date('ymd') . '-' . strtoupper(substr($order->id, 0, 4)),
                'customer_name' => $order->customer_name,
                'subtotal' => $order->subtotal,
                'service_fee' => $order->service_fee,
                'total' => $totalAmount,
                'payment_method' => 'midtrans',
                'status' => 'completed',
                'notes' => $request->notes,
                'transaction_date' => now(),
                // Menyimpan ID asli Midtrans di kolom referensi
                'payment_gateway_reference' => $payload['transaction_id'],
            ]);
        } 
        else {
            // ... (logika untuk pembayaran 'cash' Anda biarkan seperti semula) ...
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'preparing'
        ]);

        DB::commit();

        return redirect()->route('kasir.orders.index')->with('success', 'Pembayaran berhasil!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
    }
}

    /**
     * Menampilkan daftar semua transaksi. (Tidak ada perubahan)
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order', 'kasir'])->latest();

        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        $transactions = $query->paginate(20);
        return view('kasir.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail satu transaksi. (Tidak ada perubahan)
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['order.orderItems', 'kasir']);
        return view('kasir.transactions.show', compact('transaction'));
    }

    /**
     * Menampilkan struk untuk dicetak. (Tidak ada perubahan)
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['order.orderItems', 'kasir']);
        return view('kasir.transactions.receipt', compact('transaction'));
    }

    /**
     * Memberikan data statistik transaksi hari ini. (Tidak ada perubahan)
     */
    public function todayStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'total_transactions' => Transaction::whereDate('transaction_date', $today)->count(),
            'total_revenue' => Transaction::whereDate('transaction_date', $today)
                                          ->where('status', 'completed')
                                          ->sum('total'),
            'cash_transactions' => Transaction::whereDate('transaction_date', $today)
                                              ->where('payment_method', 'cash')
                                              ->count(),
            'digital_transactions' => Transaction::whereDate('transaction_date', $today)
                                                 ->whereIn('payment_method', ['midtrans', 'card', 'qris', 'transfer'])
                                                 ->count(),
        ];

        return response()->json($stats);
    }
}
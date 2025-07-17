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
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createMidtransSnapToken(Order $order)
    {
        // Hitung total dengan pajak
        $subtotal = $order->subtotal;
        $serviceFee = $order->service_fee ?? 0;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $serviceFee + $tax;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->customer_phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order', 'kasir'])
                            ->orderBy('created_at', 'desc');

    if ($request->filled('date')) {
        $query->byDate($request->date);
    }

    if ($request->filled('payment_method')) {
        $query->byPaymentMethod($request->payment_method);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('transaction_number', 'like', "%{$search}%")
              ->orWhere('customer_name', 'like', "%{$search}%")
              ->orWhere('customer_phone', 'like', "%{$search}%");
        });
    }

    $transactions = $query->paginate(20);

    // Ini adalah variabel untuk view lama Anda
    $orders = Order::with('orderItems')->latest()->get();

    // ✅ PERBAIKAN UTAMA ADA DI SINI
    // Kirim 'transactions' DAN 'orders' ke view.
    return view('kasir.transactions.index', compact('transactions', 'orders'));
    }

    /**
     * Show the form for creating a new transaction (payment form)
     */
    public function create(Order $order)
    {
        // Pastikan order sudah ready dan belum dibayar
        if ($order->status !== 'ready' || $order->payment_status === 'paid') {
            return redirect()->route('kasir.orders.index')
                            ->with('error', 'Order tidak dapat diproses untuk pembayaran.');
        }

        $order->load('orderItems');
        
        // Hitung total dengan tax
        $subtotal = $order->subtotal;
        $serviceFee = $order->service_fee ?? 0;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $serviceFee + $tax;

        return view('kasir.transactions.create', compact('order', 'subtotal', 'serviceFee', 'tax', 'total'));
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,qris,transfer',
            'cash_received' => 'nullable|numeric|required_if:payment_method,cash|gte:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Kalkulasi nilai-nilai yang dibutuhkan
            $subtotal = $order->subtotal;
            $serviceFee = $order->service_fee ?? 0;
            $tax = $subtotal * 0.1; // Asumsi pajak 10%
            $discount = $request->discount ?? 0;
            $totalAmount = ($subtotal + $serviceFee + $tax) - $discount;

            // Validasi pembayaran tunai
            if ($request->payment_method === 'cash' && $request->cash_received < $totalAmount) {
                return back()->withErrors(['cash_received' => 'Uang tunai yang diterima kurang dari total.'])
                                     ->withInput();
            }

            // ✅ KODE YANG DIPERBAIKI SESUAI TABEL ANDA
            $transaction = Transaction::create([
                'order_id' => $order->id,
                'kasir_id' => $request->user()->id,
                'transaction_number' => 'TRX-' . date('ymd') . '-' . strtoupper(substr($order->id, 0, 4)),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'subtotal' => $subtotal, // Mengisi kolom subtotal
                'tax' => $tax, // Mengisi kolom tax
                'service_fee' => $serviceFee, // Mengisi kolom service_fee
                'discount' => $discount,
                'total' => $totalAmount,
                'cash_received' => $request->payment_method === 'cash' ? $request->cash_received : $totalAmount,
                'change_amount' => $request->payment_method === 'cash' ? $request->cash_received - $totalAmount : 0, // Menggunakan 'change_amount'
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            // Update status pesanan
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);

            DB::commit();

            return redirect()->route('kasir.transactions.receipt', $transaction)
                             ->with('success', 'Pembayaran berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memproses transaksi: ' . $e->getMessage() . ' di baris ' . $e->getLine());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi. Detail: ' . $e->getMessage())
                         ->withInput();
        }
    }
    /**
     * Display the specified transaction
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['order.orderItems', 'kasir']);
        return view('kasir.transactions.show', compact('transaction'));
    }

    /**
     * Show receipt for printing
     */
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['order.orderItems', 'kasir']);
        return view('kasir.transactions.receipt', compact('transaction'));
    }

    /**
     * Get today's transaction statistics
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
            // 👇 PERBAIKAN ADA DI SINI
            'digital_transactions' => Transaction::whereDate('transaction_date', $today)
                                             ->whereIn('payment_method', ['card', 'qris', 'transfer'])
                                             ->count(),
        ];

        return response()->json($stats);
    }
}
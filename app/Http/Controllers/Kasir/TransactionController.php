<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
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

        // Validation for cash payment
        if ($request->payment_method === 'cash') {
            $subtotal = $order->subtotal;
            $serviceFee = $order->service_fee ?? 0;
            $tax = $subtotal * 0.1;
            $discount = $request->discount ?? 0;
            $total = $subtotal + $serviceFee + $tax - $discount;

            if ($request->cash_received < $total) {
                return back()->withErrors(['cash_received' => 'Cash received is less than the total amount.'])
                             ->withInput();
            }
        }

         DB::beginTransaction();
        try {
            // ✅ THE FINAL FIX: Get the user ID directly from the request.
            // This is the standard, type-safe way to get the authenticated user in a controller.
            $kasirId = $request->user()->id;

            // Create the transaction using the corrected function call
            $transaction = Transaction::createFromOrder($order, $request->all(), $kasirId);

            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);

            DB::commit();

            return redirect()->route('kasir.transactions.receipt', $transaction)
                             ->with('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction processing error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while processing the payment: ' . $e->getMessage())
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
        ];

        return response()->json($stats);
    }

    public function storeFromCashier(Request $request)
    {
        // Validasi input dari kasir
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|uuid|exists:orders,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'cash_received' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,qris,transfer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $kasirId = $request->user()->id; // Mengambil ID kasir yang sedang login

            // Menghitung kembalian
            $changeAmount = $request->cash_received - $request->total;

            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'order_id' => $request->order_id,
                'kasir_id' => $kasirId,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'service_fee' => $request->service_fee,
                'discount' => $request->discount ?? 0,
                'total' => $request->total,
                'cash_received' => $request->cash_received,
                'change_amount' => $changeAmount > 0 ? $changeAmount : 0,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            // Update status order terkait
            $order = Order::find($request->order_id);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'completed'
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil dibuat!',
                'data' => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating transaction from cashier: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

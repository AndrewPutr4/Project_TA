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
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }
    
    /**
     * Menampilkan form pembayaran
     */
    public function create(Order $order)
    {
        // Cek apakah order sudah dibayar
        if ($order->payment_status === 'paid') {
            return redirect()->route('kasir.orders.index')
                ->with('info', 'Pesanan #' . $order->order_number . ' sudah lunas.');
        }
        
        $order->load(['orderItems']);
        
        return view('kasir.transactions.create', compact('order'));
    }
    
    /**
     * Proses pembayaran dan simpan transaksi
     */
    public function store(Request $request, Order $order)
{
    // Validasi request
    $request->validate([
        'payment_method' => 'required|in:cash,cashless',
        'cash_received' => 'nullable|numeric|required_if:payment_method,cash|gte:' . $order->total,
        'notes' => 'nullable|string|max:500',
        'midtrans_payload' => 'nullable|json'
    ]);
    
    Log::info('Processing payment', [
        'order_id' => $order->id,
        'payment_method' => $request->payment_method,
        'total' => $order->total,
        'user_id' => auth('kasir')->id() // <-- PERBAIKAN 1
    ]);
    
    // Cek apakah sudah ada transaksi
    if ($order->payment_status === 'paid') {
        return redirect()->route('kasir.orders.index')
            ->with('info', 'Pesanan sudah lunas.');
    }
    
    DB::beginTransaction();
    try {
        // Data dasar transaksi
        $transactionData = [
            'order_id' => $order->id,
            'kasir_id' => auth('kasir')->id(), // <-- PERBAIKAN 2
            'transaction_number' => Transaction::generateTransactionNumber(),
            'customer_name' => $order->customer_name,
            'subtotal' => $order->subtotal,
            'service_fee' => $order->service_fee,
            'total' => $order->total,
            'notes' => $request->notes,
            'transaction_date' => now(),
            'status' => 'completed',
        ];
            
            // Handle metode pembayaran
            if ($request->payment_method === 'cashless' && $request->has('midtrans_payload')) {
                // Pembayaran digital via Midtrans
                $payload = json_decode($request->midtrans_payload, true);
                $transactionData['payment_method'] = 'midtrans';
                $transactionData['payment_gateway_reference'] = $payload['transaction_id'] ?? null;
                $transactionData['cash_received'] = null;
                $transactionData['change_amount'] = null;
                
                Log::info('Processing digital payment', ['payload' => $payload]);
            } else {
                // Pembayaran tunai
                $transactionData['payment_method'] = 'cash';
                $transactionData['cash_received'] = $request->cash_received;
                $transactionData['change_amount'] = $request->cash_received - $order->total;
                $transactionData['payment_gateway_reference'] = null;
                
                Log::info('Processing cash payment', [
                    'cash_received' => $request->cash_received,
                    'change' => $transactionData['change_amount']
                ]);
            }
            
            // Buat transaksi
            $transaction = Transaction::create($transactionData);
            
            // Update status order
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);
            
            DB::commit();
            
            Log::info('Payment processed successfully', [
                'transaction_id' => $transaction->id,
                'order_id' => $order->id
            ]);
            
            // Redirect ke halaman struk
            return redirect()->route('kasir.transactions.receipt', $transaction->id)
                ->with('success', 'Pembayaran berhasil diproses!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Menampilkan struk untuk dicetak
     */
    public function receipt($transactionId)
    {
        try {
            $transaction = Transaction::with(['order.orderItems', 'kasir'])
                ->findOrFail($transactionId);
            
            Log::info('Receipt accessed', [
                'transaction_id' => $transaction->id,
                'order_number' => $transaction->order->order_number
            ]);
            
            return view('kasir.transactions.receipt', compact('transaction'));
            
        } catch (\Exception $e) {
            Log::error('Receipt access failed', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('kasir.orders.index')
                ->with('error', 'Struk tidak ditemukan: ' . $e->getMessage());
        }
    }
    
    /**
     * Membuat token Midtrans untuk pembayaran digital
     */
    public function createMidtransSnapToken(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Pesanan sudah lunas.'], 400);
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
            ],
            'item_details' => $order->orderItems->map(function($item) {
                return [
                    'id' => $item->menu_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => $item->menu_name,
                ];
            })->toArray(),
        ];
        
        try {
            $snapToken = Snap::getSnapToken($params);
            
            Log::info('Midtrans token created', [
                'order_id' => $order->id,
                'snap_token' => substr($snapToken, 0, 20) . '...'
            ]);
            
            return response()->json(['snap_token' => $snapToken]);
            
        } catch (\Exception $e) {
            Log::error('Midtrans token creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['error' => 'Gagal membuat sesi pembayaran.'], 500);
        }
    }
    
    /**
     * Daftar semua transaksi
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['order', 'kasir'])->latest('transaction_date');
        
        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        
        // Filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter pencarian
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
     * Detail transaksi
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['order.orderItems', 'kasir']);
        return view('kasir.transactions.show', compact('transaction'));
    }
    
    /**
     * Statistik transaksi hari ini
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

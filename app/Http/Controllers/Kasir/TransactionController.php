<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import
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
        // --- Langkah 1: Log semua data yang masuk dari request ---
        Log::info('Menerima request pembayaran untuk Order #' . $order->id, $request->all());

        // Validasi request
        $request->validate([
            'payment_method' => 'required|in:cash,cashless',
            'cash_received' => 'nullable|numeric|required_if:payment_method,cash|gte:' . $order->total,
            'notes' => 'nullable|string|max:500',
            'midtrans_payload' => 'nullable|json'
        ]);
        
        // Cek apakah sudah ada transaksi
        if ($order->payment_status === 'paid') {
            Log::warning('Percobaan pembayaran ganda untuk Order #' . $order->id);
            return redirect()->route('kasir.orders.index')
                ->with('info', 'Pesanan sudah lunas.');
        }
        
        DB::beginTransaction();
        try {
            // Data dasar transaksi
            $transactionData = [
                'order_id' => $order->id,
                'kasir_id' => auth('kasir')->id(),
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
            if ($request->payment_method === 'cashless') {
                Log::info('Memproses pembayaran cashless untuk Order #' . $order->id);

                if (!$request->has('midtrans_payload')) {
                    Log::error('Payload Midtrans tidak ditemukan untuk pembayaran cashless.', ['order_id' => $order->id]);
                    throw new \Exception('Payload Midtrans tidak ada dalam request.');
                }

                $payload = json_decode($request->midtrans_payload, true);
                Log::info('Payload Midtrans berhasil di-decode.', ['payload' => $payload]);
                
                $transactionData['payment_method'] = 'midtrans';
                $transactionData['payment_gateway_reference'] = $payload['transaction_id'] ?? null;
                // --- PERBAIKAN: Ubah null menjadi 0 ---
                $transactionData['cash_received'] = 0;
                $transactionData['change_amount'] = 0;

            } else { // Metode pembayaran 'cash'
                Log::info('Memproses pembayaran tunai untuk Order #' . $order->id);
                $transactionData['payment_method'] = 'cash';
                $transactionData['cash_received'] = $request->cash_received;
                $transactionData['change_amount'] = $request->cash_received - $order->total;
                $transactionData['payment_gateway_reference'] = null;
            }
            
            Log::info('Data yang akan disimpan ke tabel transactions:', $transactionData);

            // Buat transaksi
            $transaction = Transaction::create($transactionData);
            Log::info('Transaksi berhasil dibuat dengan ID: ' . $transaction->id);
            
            // Update status order
            $order->update([
                'payment_status' => 'paid',
                'status' => 'completed'
            ]);
            Log::info('Status Order #' . $order->id . ' berhasil diupdate menjadi paid & completed.');
            
            DB::commit();
            Log::info('Transaksi untuk Order #' . $order->id . ' berhasil di-commit ke database.');
            
            // Redirect ke halaman struk
            return redirect()->route('kasir.transactions.receipt', $transaction->id)
                ->with('success', 'Pembayaran berhasil diproses!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GAGAL MEMPROSES PEMBAYARAN!', [
                'order_id' => $order->id,
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Terjadi kesalahan fatal saat memproses pembayaran. Silakan cek log untuk detail.')
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
            
            return view('kasir.transactions.receipt', compact('transaction'));
            
        } catch (\Exception $e) {
            Log::error('Gagal mengakses struk', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('kasir.orders.index')
                ->with('error', 'Struk tidak ditemukan.');
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
            
            Log::info('Token Midtrans dibuat untuk Order #' . $order->id);
            
            return response()->json(['snap_token' => $snapToken]);
            
        } catch (\Exception $e) {
            Log::error('Gagal membuat token Midtrans', [
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

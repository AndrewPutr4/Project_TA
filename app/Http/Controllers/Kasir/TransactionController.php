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
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    /**
     * Menampilkan daftar transaksi
     */
    public function index(Request $request)
    {
        // Memuat relasi 'order' dan 'kasir' untuk setiap transaksi
        $query = Transaction::with(['order', 'kasir'])->latest('transaction_date');
        
        // Menerapkan filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        
        // Menerapkan filter berdasarkan metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Menerapkan filter pencarian berdasarkan nomor transaksi atau nama pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        
        // Mengambil data transaksi dengan paginasi
        $transactions = $query->paginate(20);
        
        return view('kasir.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan formulir pembayaran untuk order tertentu
     */
    public function create(Order $order)
    {
        // Jika pesanan sudah lunas, redirect kembali ke daftar pesanan
        if ($order->payment_status === 'paid') {
            return redirect()->route('kasir.orders.index')
                ->with('info', 'Pesanan #' . $order->order_number . ' sudah lunas.');
        }
        
        // Memuat relasi 'items' dari order untuk ditampilkan di halaman pembayaran
        // Perbaikan: Menggunakan 'items' yang merupakan nama relasi yang benar di model Order
        $order->load(['items']); 
        return view('kasir.transactions.create', compact('order'));
    }
    
    /**
     * Menyimpan transaksi pembayaran baru
     */
    public function store(Request $request, Order $order)
    {
        // Validasi data input dari form pembayaran
        $request->validate([
            'payment_method' => 'required|in:cash,cashless',
            'cash_received' => 'nullable|numeric|required_if:payment_method,cash|gte:' . $order->total,
            'notes' => 'nullable|string|max:500',
            'midtrans_payload' => 'nullable|json'
        ]);
        
        // Jika pesanan sudah lunas, batalkan proses dan redirect
        if ($order->payment_status === 'paid') {
            return redirect()->route('kasir.orders.index')->with('info', 'Pesanan sudah lunas.');
        }
        
        DB::beginTransaction(); // Memulai transaksi database
        try {
            // Menyiapkan data untuk pembuatan transaksi
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
                'status' => 'completed', // Status transaksi selesai setelah pembayaran
            ];
            
            // Logika berdasarkan metode pembayaran (tunai atau cashless)
            if ($request->payment_method === 'cashless') {
                if (!$request->has('midtrans_payload')) {
                    throw new \Exception('Payload Midtrans tidak ada dalam request.');
                }
                $payload = json_decode($request->midtrans_payload, true);
                $transactionData['payment_method'] = 'midtrans';
                $transactionData['payment_gateway_reference'] = $payload['transaction_id'] ?? null;
                $transactionData['cash_received'] = 0;
                $transactionData['change_amount'] = 0;
            } else { // Metode pembayaran tunai
                $transactionData['payment_method'] = 'cash';
                $transactionData['cash_received'] = $request->cash_received;
                $transactionData['change_amount'] = $request->cash_received - $order->total;
                $transactionData['payment_gateway_reference'] = null;
            }
            
            // Membuat entri transaksi baru di database
            $transaction = Transaction::create($transactionData);
            
            // Memperbarui status pembayaran dan status order
            $order->update(['payment_status' => 'paid', 'status' => 'completed']);
            
            DB::commit(); // Menyimpan perubahan ke database
            
            Log::info('Pembayaran berhasil diproses!', [
                'transaction_id' => $transaction->id,
                'order_id' => $order->id,
                'total_paid' => $transaction->total
            ]);
            
            // Redirect ke halaman struk pembayaran
            return redirect()->route('kasir.transactions.receipt', $transaction->id)
                ->with('success', 'Pembayaran berhasil diproses!');
            
        } catch (\Exception $e) {
            DB::rollBack(); // Mengembalikan perubahan jika terjadi error
            Log::error('GAGAL MEMPROSES PEMBAYARAN!', ['order_id' => $order->id, 'error_message' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan fatal saat memproses pembayaran.')->withInput();
        }
    }

    /**
     * Menampilkan detail transaksi
     */
    public function show(Transaction $transaction)
    {
        // Memuat relasi 'order.items' (item-item pesanan) dan 'kasir'
        // Perbaikan: Menggunakan 'order.items' yang merupakan nama relasi yang benar
        $transaction->load(['order.items', 'kasir']);
        return view('kasir.transactions.show', compact('transaction'));
    }
    
    /**
     * Menampilkan struk pembayaran
     */
    public function receipt($transactionId)
    {
        // Memuat transaksi beserta relasi 'order.items' dan 'kasir'
        // Perbaikan: Menggunakan 'order.items' yang merupakan nama relasi yang benar
        $transaction = Transaction::with(['order.items', 'kasir'])->findOrFail($transactionId);
        return view('kasir.transactions.receipt', compact('transaction'));
    }
    
    /**
     * Membuat token Midtrans Snap untuk pembayaran cashless
     */
    public function createMidtransSnapToken(Order $order)
    {
        // Jika pesanan sudah lunas, kembalikan error
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Pesanan sudah lunas.'], 400);
        }
        
        // Memuat relasi 'items' dari order
        // Perbaikan: Menggunakan 'items' yang merupakan nama relasi yang benar
        $order->load('items');

        $item_details = [];
        
        // Memastikan $order->items tidak null sebelum melakukan map
        if ($order->items) { 
            $item_details = $order->items->map(function($item) {
                return [
                    'id' => $item->menu_id, 
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity, 
                    'name' => $item->menu_name,
                ];
            })->toArray();
        }

        // Menambahkan biaya layanan sebagai item terpisah jika ada
        if ($order->service_fee > 0) {
            $item_details[] = [
                'id' => 'SERVICE_FEE', 
                'price' => (int) $order->service_fee,
                'quantity' => 1, 
                'name' => 'Biaya Layanan',
            ];
        }

        // Parameter untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number, 
                'gross_amount' => (int) $order->total
            ],
            'customer_details' => [
                'first_name' => $order->customer_name
            ],
            'item_details' => $item_details,
        ];
        
        try {
            // Mendapatkan snap token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat token Midtrans', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal membuat sesi pembayaran.'], 500);
        }
    }
    
    /**
     * Mendapatkan statistik transaksi hari ini
     */
    public function todayStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'total_transactions' => Transaction::whereDate('transaction_date', $today)->count(),
            'total_revenue' => Transaction::whereDate('transaction_date', $today)->where('status', 'completed')->sum('total'),
            'cash_transactions' => Transaction::whereDate('transaction_date', $today)->where('payment_method', 'cash')->count(),
            'digital_transactions' => Transaction::whereDate('transaction_date', $today)->whereIn('payment_method', ['midtrans', 'card', 'qris', 'transfer'])->count(),
        ];
        return response()->json($stats);
    }
}

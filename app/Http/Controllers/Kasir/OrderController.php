<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
// Use statement yang diperlukan untuk method baru
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Menu;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan untuk kasir.
     */
    public function index(Request $request)
    {
        $query = Order::with('orderItems.menu')
                        ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', "%{$searchTerm}%")
                  ->orWhere('order_number', 'like', "%{$searchTerm}%");
            });
        }

        $orders = $query->paginate(20);

        return view('kasir.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan untuk kasir.
     */
    public function show(Order $order)
    {
        $order->load('orderItems.menu');
        return view('kasir.orders.show', compact('order'));
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diupdate'
        ]);
    }

    /**
     * Get today's order statistics
     */
    public function todayStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'pending_orders' => Order::whereDate('created_at', $today)
                                        ->where('status', 'pending')
                                        ->count(),
            'total_orders' => Order::whereDate('created_at', $today)->count(),
            'completed_orders' => Order::whereDate('created_at', $today)
                                            ->where('status', 'completed')
                                            ->count(),
        ];

        return response()->json($stats);
    }
    
    /**
     * Menyimpan order takeaway baru yang dibuat dari dashboard kasir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeTakeawayOrder(Request $request)
    {
        // Validasi input dari request yang dikirim oleh JavaScript
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Memulai transaksi database untuk memastikan semua query berhasil atau gagal bersamaan
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $orderItemsData = [];

            // Iterasi setiap item dalam pesanan untuk menghitung subtotal
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['id']);
                $itemTotal = $menu->price * $item['quantity'];
                $subtotal += $itemTotal;

                // Menyiapkan data untuk disimpan ke tabel 'order_items'
                $orderItemsData[] = [
                    'menu_id'          => $menu->id,
                    'menu_name'        => $menu->name,
                    'menu_description' => $menu->description,
                    'price'            => $menu->price,
                    'quantity'         => $item['quantity'],
                    'subtotal'         => $itemTotal,
                ];
            }

            // Menghitung total akhir termasuk pajak 10%
            $tax = $subtotal * 0.10;
            $total = $subtotal + $tax;

            // Membuat record baru di tabel 'orders'
            $order = Order::create([
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'table_number'     => null, // Dibuat null untuk menandakan ini adalah pesanan takeaway
                'subtotal'         => $subtotal,
                'service_fee'      => $tax, // Anda bisa menggunakan kolom 'service_fee' untuk menyimpan nilai pajak
                'total'            => $total,
                'status'           => 'confirmed', // Langsung 'confirmed' karena dibuat oleh kasir
                'payment_status'   => 'unpaid',    // Status pembayaran awal
                'order_date'       => now(),
            ]);

            // Menyimpan semua item pesanan yang terhubung dengan order utama
            $order->orderItems()->createMany($orderItemsData);

            // Jika semua proses di atas berhasil, simpan perubahan ke database
            DB::commit();

            // Mengembalikan response JSON ke JavaScript yang berisi URL untuk redirect
            return response()->json([
                'success'      => true,
                'message'      => 'Order berhasil dibuat!',
                'redirect_url' => route('kasir.transactions.create', $order) // Arahkan ke halaman pembayaran
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error di tengah proses, batalkan semua perubahan di database
            DB::rollBack();
            // Catat error ke log untuk debugging
            Log::error('Takeaway Order Creation Error: ' . $e->getMessage());
            // Kirim response error ke JavaScript
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal saat membuat pesanan.'
            ], 500);
        }
    }
}

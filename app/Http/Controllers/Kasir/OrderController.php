<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Menu;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan untuk kasir dengan pagination 5 per halaman.
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

        // Filter berdasarkan order type
        if ($request->filled('order_type')) {
            if ($request->order_type === 'dine_in') {
                $query->whereNotNull('table_number');
            } elseif ($request->order_type === 'takeaway') {
                $query->whereNull('table_number');
            }
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

        // Pagination dengan 5 items per halaman
        $orders = $query->paginate(5);

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
     * Konfirmasi order (untuk AJAX dari view)
     */
    public function confirm(Order $order)
    {
        try {
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Order sudah dikonfirmasi atau tidak dapat dikonfirmasi'
                ], 400);
            }

            $order->update(['status' => 'confirmed']);
            
            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dikonfirmasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Order confirmation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batalkan order (untuk AJAX dari view)
     */
    public function cancel(Order $order)
    {
        try {
            if (in_array($order->status, ['completed', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak dapat dibatalkan'
                ], 400);
            }

            $order->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibatalkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Order cancellation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        try {
            $order->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status order berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            Log::error('Order status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status order'
            ], 500);
        }
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
            'ready_orders' => Order::whereDate('created_at', $today)
                                   ->where('status', 'ready')
                                   ->count(),
        ];

        return response()->json($stats);
    }
        
    /**
     * Menyimpan order takeaway/dine-in baru yang dibuat dari dashboard kasir.
     */
    public function storeTakeawayOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'order_type'     => 'required|in:takeaway,dine_in',
            'table_number'   => 'required_if:order_type,dine_in|nullable|integer|min:1|max:50',
            'notes'          => 'nullable|string|max:500',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];

            // Hitung subtotal dan siapkan data order items
            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['id']);
                if (!$menu) {
                    throw new \Exception('Menu tidak ditemukan: ' . $item['id']);
                }

                $itemTotal = $menu->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItemsData[] = [
                    'menu_id'          => $menu->id,
                    'menu_name'        => $menu->name,
                    'menu_description' => $menu->description ?? '',
                    'price'            => $menu->price,
                    'quantity'         => $item['quantity'],
                    'subtotal'         => $itemTotal,
                ];
            }

            // Hitung pajak/service fee (10%)
            $serviceFee = $subtotal * 0.10;
            $total = $subtotal + $serviceFee;

            // Buat order baru (tanpa customer_phone dan customer_address)
            $order = Order::create([
                'customer_name'    => $validated['customer_name'],
                'table_number'     => $validated['order_type'] === 'dine_in' ? $validated['table_number'] : null,
                'notes'            => $validated['notes'] ?? null,
                'subtotal'         => $subtotal,
                'service_fee'      => $serviceFee,
                'total'            => $total,
                'status'           => 'confirmed',
                'payment_status'   => 'unpaid',
                'order_date'       => now(),
            ]);

            // Buat order items
            $order->orderItems()->createMany($orderItemsData);

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Order berhasil dibuat!',
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'redirect_url' => route('kasir.transactions.create', $order)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Takeaway/DineIn Order Creation Error: ' . $e->getMessage(), [
                'request_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store regular order (dari customer)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'items'            => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $menu = Menu::find($item['menu_id']);
                $itemTotal = $menu->price * $item['quantity'];
                $subtotal += $itemTotal;

                $orderItemsData[] = [
                    'menu_id'          => $menu->id,
                    'menu_name'        => $menu->name,
                    'menu_description' => $menu->description ?? '',
                    'price'            => $menu->price,
                    'quantity'         => $item['quantity'],
                    'subtotal'         => $itemTotal,
                ];
            }

            $serviceFee = $subtotal * 0.10;
            $total = $subtotal + $serviceFee;

            // Buat order tanpa customer_phone dan customer_address
            $order = Order::create([
                'customer_name'    => $validated['customer_name'],
                'notes'            => $validated['notes'] ?? null,
                'subtotal'         => $subtotal,
                'service_fee'      => $serviceFee,
                'total'            => $total,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'order_date'       => now(),
            ]);

            $order->orderItems()->createMany($orderItemsData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat order'
            ], 500);
        }
    }
}

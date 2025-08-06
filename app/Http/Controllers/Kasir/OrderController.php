<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan
     */
    public function index(Request $request)
    {
        // Menggunakan relasi 'items'
        $query = Order::with(['items'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->filled('order_type')) {
            if ($request->order_type === 'dine_in') {
                $query->whereNotNull('table_number');
            } else {
                $query->whereNull('table_number');
            }
        }
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%");
            });
        }
        
        // Menggunakan $query yang sudah difilter
        $orders = $query->paginate(15);
        
        return view('kasir.orders.index', compact('orders'));
    }
    
    /**
     * Menampilkan detail pesanan
     */
    public function show(Order $order)
    {
        $order->load(['items']);
        return view('kasir.orders.show', compact('order'));
    }
    
    /**
     * Menyimpan pesanan baru dari POS
     */
    public function storeTakeawayOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type' => 'required|in:takeaway,dine_in',
            'table_number' => 'required_if:order_type,dine_in|nullable|integer|min:1|max:50',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);
        
        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];
            
            // Hitung total dan siapkan data items
            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['id']);
                $itemTotal = $menu->price * $item['quantity'];
                $subtotal += $itemTotal;
                
                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'menu_description' => $menu->description ?? '',
                    'price' => $menu->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal,
                ];
            }
            
            $serviceFee = 0.10 * $subtotal; // 10% biaya layanan
            $total = $subtotal + $serviceFee;
            
            // Buat order baru
            $order = Order::create([
                'customer_name'  => $validated['customer_name'],
                'table_number'   => $validated['order_type'] === 'dine_in' ? $validated['table_number'] : null,
                'notes'          => $validated['notes'] ?? null,
                'subtotal'       => $subtotal,
                'service_fee'    => $serviceFee,
                'total'          => $total,
                'status'         => 'preparing', // Langsung confirmed untuk kasir
                'payment_status' => 'unpaid',
                'order_date'     => now(),
                'kasir_id'       => auth('kasir')->id(),
            ]);
            
            // Simpan order items
            $order->items()->createMany($orderItemsData);
            
            DB::commit();
            
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $total
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'redirect_url' => route('kasir.transactions.create', $order)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $validated
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Konfirmasi pesanan
     */
    public function confirm(Order $order)
    {
        try {
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan sudah dikonfirmasi atau tidak dapat dikonfirmasi'
                ], 400);
            }
            
            $order->update(['status' => 'confirmed']);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi',
                'redirect_url' => route('kasir.transactions.create', $order)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Order confirmation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pesanan'
            ], 500);
        }
    }
    
    /**
     * Batalkan pesanan
     */
    public function cancel(Order $order)
    {
        try {
            if (in_array($order->status, ['completed', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat dibatalkan'
                ], 400);
            }
            
            $order->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Order cancellation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan'
            ], 500);
        }
    }
    
    /**
     * Update status pesanan
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
                'message' => 'Status pesanan berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Order status update failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status pesanan'
            ], 500);
        }
    }
    
    /**
     * Statistik pesanan hari ini
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
}

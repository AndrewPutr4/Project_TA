<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
            $query->byStatus($request->status);
        }

        // Filter berdasarkan payment status
        if ($request->filled('payment_status')) {
            $query->byPaymentStatus($request->payment_status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
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
}

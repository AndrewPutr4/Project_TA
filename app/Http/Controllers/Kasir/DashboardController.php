<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard kasir dengan daftar produk untuk POS
     */
    public function index(Request $request)
    {
        // Pastikan shift sudah dimulai
        if (!session('shift_active')) {
            return redirect()->route('kasir.shift')
                ->with('warning', 'Silakan mulai shift terlebih dahulu sebelum melakukan transaksi.');
        }

            
        $foodsQuery = Menu::where('is_available', true);
        
        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $foodsQuery->where('category_id', $request->category);
        }
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $foodsQuery->where('name', 'like', '%' . $request->search . '%');
        }
        
        $foods = $foodsQuery->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        // Statistik hari ini
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');
        
        return view('kasir.dashboard', compact(
            'foods', 
            'categories', 
            'todayOrders', 
            'pendingOrders',
            'todayRevenue'
        ));
    }
}

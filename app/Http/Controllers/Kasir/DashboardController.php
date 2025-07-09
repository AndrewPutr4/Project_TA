<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard kasir dengan daftar produk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pastikan shift sudah dimulai sebelum mengakses dashboard
        if (!session('shift_active')) {
            return redirect()->route('kasir.shift')->with('message', 'Silakan mulai shift terlebih dahulu.');
        }

        // Query dasar untuk menu
        $foodsQuery = Menu::query();

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $foodsQuery->where('category_id', $request->category);
        }

        // Filter berdasarkan pencarian jika ada
        if ($request->filled('search')) {
            $foodsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Ambil data
        $foods = $foodsQuery->get();
        $categories = Category::all();
        
        // Kirim data ke view
        return view('kasir.dashboard', [
            'categories' => $categories,
            'foods' => $foods,
            'selectedCategory' => $request->category, // Untuk menandai kategori aktif
        ]);
    }
}

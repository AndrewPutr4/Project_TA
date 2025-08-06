<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil kategori yang aktif
        $categories = Category::active()->get();
        
        // Query dasar untuk menu
        $menusQuery = Menu::with('category')->available();
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category) {
            $menusQuery->where('category_id', $request->category);
            $selectedCategory = Category::find($request->category);
        } else {
            $selectedCategory = null;
        }
        
        // Fungsi pencarian
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $menusQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $menus = $menusQuery->orderBy('name')->get();
        
        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        // ✅ Mengambil makanan dan minuman berdasarkan nama kategori
        $foods = Menu::where('is_available', true) // <-- TAMBAHKAN INI
            ->whereHas('category', function ($query) {
                $query->where('name', 'Makanan');
            })->get();

        $drinks = Menu::where('is_available', true) // <-- TAMBAHKAN INI
            ->whereHas('category', function ($query) {
                $query->where('name', 'Minuman');
            })->get();

        return view('customer.welcome', compact('categories', 'menus', 'selectedCategory', 'cartCount', 'foods', 'drinks'));
    }

    public function search(Request $request)
    {
        return redirect()->route('home', ['search' => $request->q]);
    }
}
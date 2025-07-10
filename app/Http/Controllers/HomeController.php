<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->get();
        
        $menusQuery = Menu::with('category')->available();
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $menusQuery->where('category_id', $request->category);
            $selectedCategory = Category::find($request->category);
        } else {
            $selectedCategory = null;
        }
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $menusQuery->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        $menus = $menusQuery->orderBy('name')->get();
        
        // Get cart from session
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        
        // Misal kategori makanan id=1, minuman id=2 (ubah sesuai data Anda)
        $foods = Menu::where('category_id', 1)->get();
        $drinks = Menu::where('category_id', 2)->get();

        return view('customer.welcome', compact('categories', 'menus', 'selectedCategory', 'cartCount', 'foods', 'drinks'));
    }

    public function search(Request $request)
    {
        return redirect()->route('home', ['search' => $request->q]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cartItems = array_values(session('cart', []));
        $subtotal = array_sum(array_map(function ($item) {
            return $item['menu']->price * $item['quantity'];
        }, $cartItems));

        $deliveryFee = 5000;
        $serviceFee = 2000;
        $total = $subtotal + $deliveryFee + $serviceFee;

        return view('customer.cart', compact('cartItems', 'subtotal', 'deliveryFee', 'serviceFee', 'total'));
    }

    /**
     * ✅ FUNGSI BARU: Menambah/Update item ke cart via AJAX
     */
    public function addApi(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $menuId = $request->menu_id;
        $quantity = $request->quantity;
        
        $menu = Menu::find($menuId);
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan']);
        }
        
        $cart = session()->get('cart', []);
        
        if ($quantity > 0) {
            $cart[$menuId] = [
                'id' => $menu->id,
                'quantity' => $quantity,
                'menu' => $menu
            ];
        } else {
            // Jika quantity 0, hapus dari cart
            unset($cart[$menuId]);
        }
        
        session()->put('cart', $cart);
        
        // Hitung total item dan total harga
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $cartTotal = array_sum(array_map(function($item) {
            return $item['menu']->price * $item['quantity'];
        }, $cart));
        
        return response()->json([
            'success' => true, 
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'message' => $quantity > 0 ? $menu->name . ' ditambahkan!' : $menu->name . ' dihapus!'
        ]);
    }

    /**
     * ✅ FUNGSI BARU: Get cart count untuk update tampilan
     */
    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        $total = array_sum(array_map(function($item) {
            return $item['menu']->price * $item['quantity'];
        }, $cart));
        
        return response()->json([
            'count' => $count,
            'total' => $total
        ]);
    }

    /**
     * Menambah item ke keranjang dan REDIRECT.
     */
    public function add(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);
        $this->addToCartSession($request->menu_id);
        return redirect()->route('cart.index')->with('success', 'Item berhasil ditambahkan!');
    }

    /**
     * Mengupdate kuantitas item di keranjang.
     */
    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session('cart', []);
        if ($request->quantity == 0) {
            unset($cart[$request->menu_id]);
        } else if (isset($cart[$request->menu_id])) {
            $cart[$request->menu_id]['quantity'] = $request->quantity;
        }

        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->menu_id]);
        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Mengosongkan keranjang.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Logika internal untuk menambah item ke session.
     */
    private function addToCartSession($menuId, $quantity = 1)
    {
        $cart = session('cart', []);
        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += $quantity;
        } else {
            $menu = Menu::findOrFail($menuId);
            $cart[$menuId] = [
                'id' => $menu->id,
                'quantity' => $quantity,
                'menu' => $menu,
            ];
        }
        session(['cart' => $cart]);
        return $cart;
    }
}
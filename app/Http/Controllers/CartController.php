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

        $deliveryFee = 5000; // Contoh biaya, bisa diatur
        $serviceFee = 2000; // Contoh biaya, bisa diatur
        $total = $subtotal + $deliveryFee + $serviceFee;

        // ✅ PASTIKAN NAMA VIEW BENAR: 'cart' bukan 'cart.blade.php'
        return view('cart', compact('cartItems', 'subtotal', 'deliveryFee', 'serviceFee', 'total'));
    }

    /**
     * Menambah item ke keranjang dan REDIRECT.
     * (Digunakan sebagai fallback jika JavaScript tidak aktif)
     */
    public function add(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);
        $this->addToCartSession($request->menu_id);
        return redirect()->route('cart.index')->with('success', 'Item berhasil ditambahkan!');
    }

    /**
     * ✅ FUNGSI API: Menambah item dan mengembalikan JSON untuk JavaScript.
     */
    public function addApi(Request $request)
    {
        $request->validate(['menu_id' => 'required|exists:menus,id']);
        $cart = $this->addToCartSession($request->menu_id);
        
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $menuName = $cart[$request->menu_id]['menu']->name;

        return response()->json([
            'success' => true,
            'message' => "{$menuName} ditambahkan!",
            'cart_count' => $cartCount
        ]);
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
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $cartItems = array_values($cart);

        // Hitung subtotal, deliveryFee, serviceFee, total
        $subtotal = array_sum(array_map(function($item) {
            return $item['menu']->price * $item['quantity'];
        }, $cartItems));
        $deliveryFee = 0;
        $serviceFee = 0;
        $total = $subtotal + $deliveryFee + $serviceFee;

        return view('cart', compact('cartItems', 'subtotal', 'deliveryFee', 'serviceFee', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $menuId = $request->input('menu_id'); // perbaiki di sini
        $quantity = $request->input('quantity', 1);

        // Cek apakah sudah ada di cart
        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += $quantity;
        } else {
            $menu = \App\Models\Menu::findOrFail($menuId);
            $cart[$menuId] = [
                'id' => $menu->id,
                'quantity' => $quantity,
                'menu' => $menu,
            ];
        }

        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session()->get('cart', []);
        
        if ($request->quantity == 0) {
            unset($cart[$request->menu_id]);
        } else {
            if (isset($cart[$request->menu_id])) {
                $cart[$request->menu_id]['quantity'] = $request->quantity;
            }
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'menu_id' => 'required'
        ]);

        $cart = session()->get('cart', []);
        $foodName = $cart[$request->menu_id]['menu']->name ?? 'Item';

        unset($cart[$request->menu_id]);
        session()->put('cart', $cart);

        return redirect()->back()->with('success', $foodName . ' dihapus dari keranjang');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan');
    }
}
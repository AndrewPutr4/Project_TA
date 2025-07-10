<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Memproses checkout dari pelanggan dan menyimpan pesanan ke database.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsForDb = [];
            
            foreach ($cart as $id => $item) {
                if (isset($item['menu'])) {
                    $menu = $item['menu'];
                    $itemTotal = $menu->price * $item['quantity'];
                    $subtotal += $itemTotal;
                    
                    $itemsForDb[] = [
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'menu_description' => $menu->description,
                        'price' => $menu->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $itemTotal,
                    ];
                }
            }
            
            $serviceFee = 2000;
            $total = $subtotal + $serviceFee;
            
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'table_number' => $validated['table_number'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'order_date' => now(),
            ]);

            $order->orderItems()->createMany($itemsForDb);
            
            DB::commit();
            session()->forget('cart');
            
            return redirect()->route('order.success')->with([
                'success' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan internal. Silakan coba lagi.')->withInput();
        }
    }

    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        
        return view('customer.order-success');
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('customer.order-show', compact('order'));
    }
}
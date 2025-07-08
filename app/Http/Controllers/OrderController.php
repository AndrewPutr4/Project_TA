<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Anda tidak perlu lagi 'Str' di sini

class OrderController extends Controller
{
    /**
     * Memproses checkout dari pelanggan dan menyimpan pesanan ke database.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'table_number' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
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
            
            // ❌ HAPUS KODE PEMBUATAN NOMOR PESANAN DARI SINI
            // $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            $order = Order::create([
                // ❌ 'order_number' juga dihapus dari sini
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
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

            // Redirect tetap sama dan akan berfungsi karena model sudah mengisi 'order_number'
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

    // ... sisa method success() dan show() Anda sudah benar ...
    
    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        return view('order.success');
    }

    public function show($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return view('order.show', compact('order'));
    }
}
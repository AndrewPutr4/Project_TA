<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function processCheckout(Request $request)
    {
        // 1. Validasi data yang masuk dari form
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number' => 'nullable|integer',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cash,cashless',
        ]);

        // 2. Ambil data dari keranjang (cart) di session
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        // Hitung total keseluruhan
        $subtotal = 0;
        foreach ($cart as $id => $details) {
            $subtotal += $details['menu']->price * $details['quantity'];
        }
        $serviceFee = 2000;
        $total = $subtotal + $serviceFee;

        // 3. Logika utama: Cek Metode Pembayaran
        if ($request->payment_method == 'cash') {
            // ===============================================
            //  LOGIKA UNTUK PEMBAYARAN TUNAI (CASH)
            // ===============================================
            $order = Order::create([
                'customer_name'  => $request->customer_name,
                'table_number'   => $request->table_number,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'service_fee'    => $serviceFee,
                'total'          => $total,
                'status'         => 'confirmed',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
            ]);

            // Simpan item-item yang dipesan
            foreach ($cart as $id => $details) {
                // ✅ Hitung subtotal untuk setiap item
                $itemSubtotal = $details['quantity'] * $details['menu']->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'menu_id'    => $id,
                    'menu_name'  => $details['menu']->name,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['menu']->price,
                    'subtotal'   => $itemSubtotal, // <-- INI YANG KURANG PADA KODE ANDA
                ]);
            }

            session()->forget('cart');
            return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } elseif ($request->payment_method == 'cashless') {
            // ===============================================
            //  LOGIKA UNTUK PEMBAYARAN NON-TUNAI (MIDTRANS)
            // ===============================================
            $order = Order::create([
                'customer_name'  => $request->customer_name,
                'table_number'   => $request->table_number,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'service_fee'    => $serviceFee,
                'total'          => $total,
                'status'         => 'pending',
                'payment_method' => 'cashless',
                'payment_status' => 'unpaid',
            ]);

            // Simpan item-item yang dipesan
            foreach ($cart as $id => $details) {
                $itemSubtotal = $details['quantity'] * $details['menu']->price;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'menu_id'    => $id,
                    'menu_name'  => $details['menu']->name,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['menu']->price,
                    'subtotal'   => $itemSubtotal,
                ]);
            }
            
            // ===============================================
            // ✅ KONFIGURASI MIDTRANS MENGGUNAKAN HELPER CONFIG
            // ===============================================
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Siapkan parameter untuk Midtrans
            $midtrans_params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => 'customer@example.com',
                    'phone' => '08123456789',
                ],
                'item_details' => $this->prepareItemDetails($cart, $serviceFee),
            ];

            try {
                $snapToken = Snap::getSnapToken($midtrans_params);
                session()->forget('cart');
                return view('order.payment', ['snapToken' => $snapToken, 'order' => $order]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
    }

    public function success(Order $order)
    {
        // The 'Order $order' part automatically finds the order by its ID from the URL.
        // This is called Route Model Binding.

        return view('order.success', compact('order'));
    }

    private function prepareItemDetails($cart, $serviceFee)
    {
        $items = [];
        foreach ($cart as $id => $details) {
            $items[] = [
                'id' => $id,
                'price' => $details['menu']->price,
                'quantity' => $details['quantity'],
                'name' => substr($details['menu']->name, 0, 50),
            ];
        }

        $items[] = [
            'id' => 'SERVICE_FEE',
            'price' => $serviceFee,
            'quantity' => 1,
            'name' => 'Biaya Layanan',
        ];
        
        return $items;
    }

    public function history(Request $request)
    {
        // Get order IDs from the session
        $orderIds = $request->session()->get('customer_order_ids', []);

        if (empty($orderIds)) {
            // If no orders, show an empty history page
            return view('customer.orders.history', ['orders' => collect()]);
        }

        // Fetch orders from the database using the stored IDs
        $orders = Order::whereIn('id', $orderIds)
                        ->with('orderItems.menu') // Eager load items and their menus
                        ->latest() // Show the newest orders first
                        ->get();

        return view('customer.orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        // Make sure the requested order is one that the user actually made in this session
        $orderIds = session()->get('customer_order_ids', []);
        if (!in_array($order->id, $orderIds)) {
            // If not, abort and show a 403 Forbidden error
            abort(403, 'Unauthorized action.');
        }

        return view('customer.orders.show', compact('order'));
    }
}
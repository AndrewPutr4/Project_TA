<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    /**
     * Memproses checkout dari keranjang belanja untuk pelanggan tanpa login.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'table_number'   => 'nullable|integer',
            'notes'          => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,cashless',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = []; // Siapkan array untuk item

            // 2. Kalkulasi Total dan siapkan data item dalam satu loop
            foreach ($cart as $id => $details) {
                if (isset($details['menu']) && is_object($details['menu'])) {
                    $itemTotal = $details['menu']->price * $details['quantity'];
                    $subtotal += $itemTotal;
                    
                    // Tambahkan data item ke array
                    $orderItemsData[] = [
                        'menu_id'   => $id,
                        'menu_name' => $details['menu']->name,
                        'quantity'  => $details['quantity'],
                        'price'     => $details['menu']->price,
                        'subtotal'  => $itemTotal,
                    ];
                }
            }
            $serviceFee = 2000;
            $total = $subtotal + $serviceFee;

            // 3. Buat Pesanan
            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'customer_name'  => $request->customer_name,
                'table_number'   => $request->table_number,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'service_fee'    => $serviceFee,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'status'         => 'pending',
                'order_date'     => now(),
            ]);

            // 4. PERBAIKAN: Simpan semua item sekaligus menggunakan relasi
            $order->items()->createMany($orderItemsData);

            // 5. Simpan ID pesanan ke session
            $orderIds = $request->session()->get('customer_order_ids', []);
            $orderIds[] = $order->id;
            $request->session()->put('customer_order_ids', $orderIds);

            session()->forget('cart');
            DB::commit();

            if ($order->payment_method == 'cashless') {
                $snapToken = $this->createMidtransSnapToken($order, $request);
                return view('order.payment', compact('snapToken', 'order'));
            }

            return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order Checkout Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    /**
     * Menampilkan riwayat pesanan berdasarkan ID yang tersimpan di session.
     */
    public function history(Request $request)
    {
        $orderIds = $request->session()->get('customer_order_ids', []);
        if (empty($orderIds)) {
            return view('customer.orders.history', ['orders' => collect()]);
        }
        $orders = Order::whereIn('id', $orderIds)
                       ->with('items.menu')
                       ->latest()
                       ->get();
        return view('customer.orders.history', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Order $order)
    {
        $orderIds = session()->get('customer_order_ids', []);
        if (!in_array($order->id, $orderIds)) {
            abort(403, 'AKSES DITOLAK.');
        }
        $order->load('items');
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Halaman sukses setelah checkout.
     */
    public function success(Order $order)
    {
        $orderIds = session()->get('customer_order_ids', []);
        if (!in_array($order->id, $orderIds)) {
            abort(403);
        }
        $order->load('items');
        return view('order.success', compact('order'));
    }

    /**
     * Membuat Snap Token Midtrans.
     */
    private function createMidtransSnapToken(Order $order, Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ✅ PERBAIKAN: Sertakan detail item untuk Midtrans
        $order->load('items');
        $item_details = $order->items->map(function($item) {
            return [
                'id'       => $item->menu_id,
                'price'    => (int) $item->price,
                'quantity' => $item->quantity,
                'name'     => $item->menu_name,
            ];
        })->toArray();

        if ($order->service_fee > 0) {
            $item_details[] = [
                'id' => 'SERVICE_FEE', 'price' => (int) $order->service_fee,
                'quantity' => 1, 'name' => 'Biaya Layanan',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => 'guest@example.com',
                'phone'      => '081234567890',
            ],
            'item_details' => $item_details,
        ];

        return Snap::getSnapToken($params);
    }
}

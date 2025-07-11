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
        // 1. Validasi Input Manual
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
            // 2. Kalkulasi Total
            $subtotal = 0;
            foreach ($cart as $id => $details) {
                if (isset($details['menu']) && is_object($details['menu'])) {
                    $subtotal += $details['menu']->price * $details['quantity'];
                }
            }
            $serviceFee = 2000;
            $total = $subtotal + $serviceFee;

            // 3. Buat Pesanan
            $order = Order::create([
                'user_id'        => null,
                'order_number'   => 'ORD-' . strtoupper(uniqid()),
                'customer_name'  => $request->customer_name,
                'table_number'   => $request->table_number,
                'notes'          => $request->notes,
                'subtotal'       => $subtotal,
                'service_fee'    => $serviceFee,
                'total'          => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => ($request->payment_method == 'cash') ? 'paid' : 'unpaid',
                'status'         => ($request->payment_method == 'cash') ? 'completed' : 'pending',
                'order_date'     => now(),
            ]);

            // 4. Simpan Item Pesanan
            foreach ($cart as $id => $details) {
                if (isset($details['menu']) && is_object($details['menu'])) {
                    OrderItem::create([
                        'order_id'  => $order->id,
                        'menu_id'   => $id,
                        'menu_name' => $details['menu']->name,
                        'quantity'  => $details['quantity'],
                        'price'     => $details['menu']->price,
                        'subtotal'  => $details['menu']->price * $details['quantity'],
                    ]);
                }
            }

            // 5. ✅ PERBAIKAN: Cara yang benar untuk menyimpan ID ke session
            // Ambil array yang sudah ada, atau buat array baru jika belum ada.
            $orderIds = $request->session()->get('customer_order_ids', []);
            // Tambahkan ID baru ke array tersebut.
            $orderIds[] = $order->id;
            // Simpan kembali array yang sudah diperbarui ke dalam session.
            $request->session()->put('customer_order_ids', $orderIds);


            // 6. Selesaikan Proses
            session()->forget('cart');
            DB::commit();

            if ($order->payment_method == 'cashless') {
                $snapToken = $this->createMidtransSnapToken($order, $request);
                return view('order.payment', compact('snapToken', 'order'));
            }

            return redirect()->route('order.success', $order->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order Checkout Error: " . $e->getMessage() . " at line " . $e->getLine());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses pesanan.');
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
                       ->with('orderItems.menu')
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
        return view('order.success', compact('order'));
    }

    /**
     * Menyiapkan item untuk Midtrans.
     */
    private function prepareItemDetails($cart, $serviceFee)
    {
        $items = [];
        foreach ($cart as $id => $details) {
            if (isset($details['menu']) && is_object($details['menu'])) {
                $items[] = [
                    'id'       => $id,
                    'price'    => $details['menu']->price,
                    'quantity' => $details['quantity'],
                    'name'     => substr($details['menu']->name, 0, 50),
                ];
            }
        }
        $items[] = [
            'id' => 'SERVICE_FEE', 'price' => $serviceFee, 'quantity' => 1, 'name' => 'Biaya Layanan',
        ];
        return $items;
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

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => 'guest@example.com',
                'phone'      => '081234567890',
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
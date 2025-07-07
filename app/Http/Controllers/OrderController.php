<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Memproses checkout dari pelanggan dan menyimpan pesanan ke database.
     */
    public function checkout(Request $request)
    {
        // 1. Validasi data yang dikirim dari form di halaman keranjang
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'table_number' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        // 2. Ambil item dari keranjang (session)
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // 3. Gunakan Database Transaction
        DB::beginTransaction();

        try {
            // 4. Hitung subtotal dan siapkan data item
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
            
            // 5. Buat entri baru di tabel 'orders'
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'table_number' => $validated['table_number'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                //'delivery_fee' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // 6. Buat entri untuk setiap item di tabel 'order_items'
            foreach ($itemsForDb as $itemData) {
                $order->itemsRelation()->create($itemData);
            }
            
            // 7. Commit transaksi
            DB::commit();

            // 8. Kosongkan keranjang
            session()->forget('cart');

            // 9. Redirect ke halaman sukses
            return redirect()->route('order.success')->with([
                'success' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (ValidationException $e) {
            // Jika validasi gagal, kembali dengan error validasi
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        
        } catch (\Exception $e) {
            // Jika terjadi error lain, batalkan semua query
            DB::rollBack();

            // Log error untuk developer (selalu penting)
            Log::error('Checkout Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());

            // ✅ PERBAIKAN: Tampilkan error detail jika dalam mode debug
            // Cek file .env Anda, pastikan APP_DEBUG=true
            if (config('app.debug')) {
                // Saat development, tampilkan pesan error yang jelas
                return back()->with('error', 'DEBUG MODE: ' . $e->getMessage())->withInput();
            } else {
                // Saat production, tampilkan pesan umum yang aman
                return back()->with('error', 'Terjadi kesalahan internal. Silakan coba lagi nanti.')->withInput();
            }
        }
    }

    /**
     * Menampilkan halaman sukses setelah checkout.
     */
    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        return view('order.success');
    }

    /**
     * Menampilkan detail pesanan.
     */
    public function show($id)
    {
        $order = Order::with('itemsRelation')->findOrFail($id);
        return view('order.show', compact('order'));
    }
}

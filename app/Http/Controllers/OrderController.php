<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data yang diterima dari kasir
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'total' => 'required|numeric',
            'table_number' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // 1. Buat pesanan utama (Order)
            $order = Order::create([
                'order_date' => now(),
                'table_number' => $request->table_number,
                'total' => $request->total,
                'status' => 'PAID', // Asumsi status langsung lunas
            ]);

            // 2. Simpan setiap item pesanan (Order Items)
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menus_id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'order_date' => $order->order_date,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Pesanan berhasil disimpan!', 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()], 500);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some orders to create transactions for
        $orders = Order::where('status', 'ready')
                      ->orWhere('status', 'completed')
                      ->limit(10)
                      ->get();

        $kasir = User::first(); // Assuming first user is kasir

        foreach ($orders as $order) {
            // Skip if order already has transaction
            if ($order->transactions()->exists()) {
                continue;
            }

            $subtotal = $order->subtotal;
            $serviceFee = $order->service_fee;
            $tax = $subtotal * 0.1;
            $discount = rand(0, 1) ? rand(5000, 20000) : 0;
            $total = $subtotal + $serviceFee + $tax - $discount;

            $paymentMethods = ['cash', 'card', 'qris', 'transfer'];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            $cashReceived = $paymentMethod === 'cash' ? $total + rand(0, 50000) : $total;
            $changeAmount = $paymentMethod === 'cash' ? $cashReceived - $total : 0;

            Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber(),
                'order_id' => $order->id,
                'kasir_id' => $kasir->id,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'service_fee' => $serviceFee,
                'discount' => $discount,
                'total' => $total,
                'cash_received' => $cashReceived,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'notes' => rand(0, 1) ? 'Pembayaran lancar' : null,
                'transaction_date' => Carbon::now()->subDays(rand(0, 7)),
            ]);

            // Update order status
            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid'
            ]);
        }
    }
}

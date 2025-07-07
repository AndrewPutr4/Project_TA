<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Get all menus to create sample orders
        $menus = Menu::all();
        
        if ($menus->isEmpty()) {
            $this->command->warn('⚠️ No menus found. Please run the MenuSeeder first.');
            return;
        }

        $this->command->info('Creating 10 sample orders...');

        // 2. Loop to create 10 sample orders
        for ($i = 1; $i <= 10; $i++) {
            // Create the order.
            $order = Order::create([
                'customer_name' => 'Customer ' . $i,
                'customer_phone' => '08123456789' . $i,
                'customer_address' => 'Jl. Contoh No. ' . $i . ', Denpasar, Bali',
                'subtotal' => 0, // Will be calculated later
                'service_fee' => 2000,
                'total' => 0, // Will be calculated later
                'status' => collect(['pending', 'confirmed', 'preparing', 'delivered'])->random(),
                'notes' => $i % 3 == 0 ? 'Request sambal dipisah' : null,
                'order_date' => now()->subDays(rand(0, 7)),
            ]);

            // 3. Add random order items to the order
            $selectedMenus = $menus->random(rand(1, 3));
            $subtotal = 0;

            foreach ($selectedMenus as $menu) {
                $quantity = rand(1, 2);
                $itemSubtotal = $menu->price * $quantity;
                $subtotal += $itemSubtotal;

                // ✅ FIX IS HERE: Changed 'unit_price' to 'price'
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'menu_description' => $menu->description,
                    'price' => $menu->price, // Corrected column name
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // 4. Update the order with the final calculated totals
            $total = $subtotal + $order->service_fee;
            $order->update([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }

        $this->command->info('✅ Successfully created 10 sample orders with order items.');
    }
}
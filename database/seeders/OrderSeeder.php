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
        // Sample orders for testing
        $menus = Menu::all();
        
        if ($menus->isEmpty()) {
            $this->command->warn('No menus found. Please run MenuSeeder first.');
            return;
        }

        // Create sample orders
        for ($i = 1; $i <= 10; $i++) {
            $order = Order::create([
                'customer_name' => 'Customer ' . $i,
                'customer_phone' => '08123456789' . $i,
                'customer_address' => 'Jl. Contoh No. ' . $i . ', Denpasar, Bali',
                'subtotal' => 0, // Will be calculated
                'delivery_fee' => 5000,
                'service_fee' => 2000,
                'total' => 0, // Will be calculated
                'status' => collect(['pending', 'confirmed', 'preparing', 'delivered'])->random(),
                'notes' => $i % 3 == 0 ? 'Pedas sedang, tanpa bawang' : null,
                'order_date' => now()->subDays(rand(0, 7)),
                'items' => [] // Will be populated
            ]);

            // Generate order number
            $order->update(['order_number' => $order->generateOrderNumber()]);

            // Add random order items
            $selectedMenus = $menus->random(rand(1, 4));
            $orderItems = [];
            $subtotal = 0;

            foreach ($selectedMenus as $menu) {
                $quantity = rand(1, 3);
                $itemSubtotal = $menu->price * $quantity;
                $subtotal += $itemSubtotal;

                // Create order item record
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'menu_description' => $menu->description,
                    'unit_price' => $menu->price,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                    'menu_options' => $menu->name == 'Bakso Pedas' ? ['spice_level' => 'medium'] : null,
                    'special_instructions' => $quantity > 2 ? 'Extra porsi' : null
                ]);

                // Add to items JSON for backward compatibility
                $orderItems[] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => $quantity,
                    'total' => $itemSubtotal
                ];
            }

            // Update order totals
            $total = $subtotal + $order->delivery_fee + $order->service_fee;
            $order->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'items' => $orderItems
            ]);
        }

        $this->command->info('Created 10 sample orders with order items.');
    }
}

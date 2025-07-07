<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu; // You only need the Menu model here
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Processes the checkout from the customer and saves the order to the database.
     */
    public function checkout(Request $request)
    {
        // 1. Validate data from the cart form
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'table_number' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        // 2. Get items from the cart session
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // 3. Use a Database Transaction for safety
        DB::beginTransaction();

        try {
            // 4. Calculate subtotal and prepare item data
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
            
            // Define fees and calculate total
            $serviceFee = 2000;
            $total = $subtotal + $serviceFee;
            
            // 5. Create a new entry in the 'orders' table
            $order = Order::create([
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

            // 6. ✅ FIX: Use the correct 'orderItems' relationship to create multiple items at once.
            $order->orderItems()->createMany($itemsForDb);
            
            // 7. Commit the transaction
            DB::commit();

            // 8. Clear the cart
            session()->forget('cart');

            // 9. Redirect to the success page with order details
            return redirect()->route('order.success')->with([
                'success' => 'Order created successfully!',
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            // If any error occurs, roll back all database queries
            DB::rollBack();

            // Log the detailed error for debugging
            Log::error('Checkout Error: ' . $e->getMessage());

            // Show a user-friendly error message
            return back()->with('error', 'An internal error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Shows the success page after checkout.
     */
    public function success()
    {
        if (!session('order_id')) {
            return redirect()->route('home');
        }
        return view('order.success');
    }

    /**
     * Shows the details of a specific order for the customer.
     */
    public function show($id)
    {
        // ✅ FIX: Use the correct 'orderItems' relationship for eager loading.
        $order = Order::with('orderItems')->findOrFail($id);
        return view('order.show', compact('order'));
    }
}
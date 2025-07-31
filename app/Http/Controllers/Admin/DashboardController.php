<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // FIXED: Added import for DB Facade
use App\Models\Order;                 // FIXED: Added import for Order Model
use App\Models\User;                  // FIXED: Added import for User Model
use App\Models\Shift;                 // This was already correct
use App\Models\Transaction;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Fetch Total Sales
        // Assumes you have a 'transactions' table with a numeric 'amount' column
        $totalSales = DB::table('transactions')->sum('total');

        // Fetch New Orders
        // Assumes you have an 'orders' table
        $newOrdersCount = Order::count();

        // Fetch Visitors
        // This example counts all registered users. Adjust if you have a different metric.
        $visitorsCount = User::count();

        // Fetch Active Shifts
        // Assumes 'end_time' is NULL for a shift that is currently active
        $activeShifts = Shift::whereNull('end_time')->latest()->get();

        // Pass all the data to the view
        return view('admin.dashboard', [
            'totalSales' => $totalSales,
            'newOrdersCount' => $newOrdersCount,
            'visitorsCount' => $visitorsCount,
            'activeShifts' => $activeShifts,
        ]);
    }
}
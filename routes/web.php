<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;
use App\Http\Middleware\KasirMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// RUTE UNTUK PENGUNJUNG (CUSTOMER)
//======================================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk Keranjang Belanja
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Rute untuk Proses Checkout
Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::get('/checkout', function() {
    return view('customer.checkout'); // Tambahkan 'customer.' di depan
})->name('checkout');



//======================================================================
// RUTE API (UNTUK JAVASCRIPT/AJAX)
//======================================================================
Route::prefix('api')->name('api.')->group(function () {
    Route::post('cart/add', [CartController::class, 'addApi'])->name('cart.add');
    Route::get('cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
});

//======================================================================
// RUTE UNTUK ADMIN
//======================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute untuk otentikasi (login, logout)
    Route::get('login', [Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login']);
    Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
    Route::get('register', [Admin\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [Admin\AuthController::class, 'register']);

    // Rute yang dilindungi otentikasi admin
    Route::middleware('auth:web')->group(function () {
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('menus', Admin\MenuController::class);
        Route::resource('shifts', Admin\ShiftController::class);
        Route::resource('transactions', Admin\TransactionController::class);
        
        // Manajemen Kasir
        Route::get('kasir/register', [Admin\KasirController::class, 'showRegisterForm'])->name('kasir.register');
        Route::post('kasir/register', [Admin\KasirController::class, 'register']);
        Route::resource('kasir', Admin\KasirController::class)->except(['show', 'edit']);
    });
});

//======================================================================
// RUTE UNTUK KASIR
//======================================================================
Route::prefix('kasir')->name('kasir.')->group(function () {
    // Kasir Auth
    Route::get('login', [Kasir\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Kasir\AuthController::class, 'login']);
    Route::post('logout', [Kasir\AuthController::class, 'logout'])->name('logout');

    // Kasir Protected Routes
    Route::middleware([KasirMiddleware::class])->group(function () {
        // Dashboard (Menggunakan Controller baru)
        Route::get('dashboard', [Kasir\DashboardController::class, 'index'])->name('dashboard');

        // Shift Management
        Route::get('shift', function () {
            return view('kasir.shift');
        })->name('shift');

        Route::post('shift/start', function () {
            session(['shift_active' => true]);
            return redirect()->route('kasir.shift')->with('message', 'Shift dimulai.');
        })->name('shift.start');

        Route::post('shift/end', function () {
            session()->forget('shift_active');
            return redirect()->route('kasir.shift')->with('message', 'Shift diakhiri.');
        })->name('shift.end');

        // Order Management
        Route::get('orders', [Kasir\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [Kasir\OrderController::class, 'show'])->name('orders.show');
        Route::post('orders', [Kasir\OrderController::class, 'store'])->name('orders.store');
        Route::post('orders/{order}/status', [Kasir\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('api/orders/stats', [Kasir\OrderController::class, 'todayStats'])->name('orders.stats');
        
        // == RUTE BARU UNTUK TAKEAWAY ==
        Route::post('orders/takeaway', [Kasir\OrderController::class, 'storeTakeawayOrder'])->name('orders.storeTakeaway');

        // Transaction Management
        Route::get('transactions', [Kasir\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [Kasir\TransactionController::class, 'show'])->name('transactions.show');
        Route::get('orders/{order}/payment', [Kasir\TransactionController::class, 'create'])->name('transactions.create');
        Route::post('orders/{order}/payment', [Kasir\TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}/receipt', [Kasir\TransactionController::class, 'receipt'])->name('transactions.receipt');
        Route::get('api/transactions/stats', [Kasir\TransactionController::class, 'todayStats'])->name('transactions.stats');
        
        // Midtrans Snap Token
        Route::post('orders/{order}/midtrans-snap-token', [Kasir\TransactionController::class, 'createMidtransSnapToken'])->name('transactions.createMidtransSnapToken');
    });
});

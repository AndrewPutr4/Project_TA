<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;
use App\Http\Middleware\KasirMiddleware;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\Kasir\AuthController as KasirAuthController;
use App\Http\Controllers\Kasir\ShiftController as KasirShiftController;
use App\Http\Controllers\Kasir\MidtransCallbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// RUTE UNTUK PENGUNJUNG (CUSTOMER)
//======================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'index'])->name('customer.welcome');

// Rute untuk Keranjang Belanja
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Rute untuk Proses Checkout
Route::post('/order/checkout', [OrderController::class, 'processCheckout'])->name('order.checkout');
Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/my-orders', [OrderController::class, 'history'])->name('orders.history');
Route::get('/checkout', function() {
    return view('customer.checkout');
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
Route::prefix('admin')->group(function () {
    // Authentication routes
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Rute yang dilindungi otentikasi admin
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::resource('menus', Admin\MenuController::class)->names([
            'index' => 'admin.menus.index',
            'create' => 'admin.menus.create',
            'store' => 'admin.menus.store',
            'show' => 'admin.menus.show',
            'edit' => 'admin.menus.edit',
            'update' => 'admin.menus.update',
            'destroy' => 'admin.menus.destroy'
        ]);

        Route::resource('shifts', Admin\ShiftController::class)->names([
            'index' => 'admin.shifts.index',
            'create' => 'admin.shifts.create',
            'store' => 'admin.shifts.store',
            'show' => 'admin.shifts.show',
            'edit' => 'admin.shifts.edit',
            'update' => 'admin.shifts.update',
            'destroy' => 'admin.shifts.destroy'
        ]);

        Route::resource('transactions', Admin\TransactionController::class)->names([
            'index' => 'admin.transactions.index',
            'create' => 'admin.transactions.create',
            'store' => 'admin.transactions.store',
            'show' => 'admin.transactions.show',
            'edit' => 'admin.transactions.edit',
            'update' => 'admin.transactions.update',
            'destroy' => 'admin.transactions.destroy'
        ]);

        // Manajemen Kasir
        Route::resource('kasir', Admin\KasirController::class)
            ->except(['show', 'edit'])
            ->names([
                'index' => 'admin.kasir.index',
                'create' => 'admin.kasir.create',
                'store' => 'admin.kasir.store',
                'update' => 'admin.kasir.update',
                'destroy' => 'admin.kasir.destroy'
            ]);
    });
});

//======================================================================
// RUTE UNTUK KASIR
//======================================================================
Route::prefix('kasir')->group(function() {
    // Auth
    Route::get('login', [KasirAuthController::class, 'showLoginForm'])->name('kasir.login');
    Route::post('login', [KasirAuthController::class, 'login'])->name('kasir.login.submit');
    Route::post('logout', [KasirAuthController::class, 'logout'])->name('kasir.logout');
    
    // Protected Routes - Menggunakan middleware kasir
    Route::middleware(['auth', 'kasir'])->group(function () {
        // Dashboard POS
        Route::get('dashboard', [DashboardController::class, 'index'])->name('kasir.dashboard');
        Route::get('pos', [DashboardController::class, 'index'])->name('kasir.pos');
        
        // Shift Management
        Route::get('shift', [KasirShiftController::class, 'showShiftPage'])->name('kasir.shift');
        Route::post('shift/start', [KasirShiftController::class, 'startShift'])->name('kasir.shift.start');
        Route::post('shift/end', [KasirShiftController::class, 'endShift'])->name('kasir.shift.end');
        
        // Order Management
        Route::get('orders', [OrderController::class, 'index'])->name('kasir.orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('kasir.orders.show');
        Route::post('orders/create', [OrderController::class, 'storeTakeawayOrder'])->name('kasir.orders.storeTakeaway');
        Route::post('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('kasir.orders.confirm');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('kasir.orders.cancel');
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('kasir.orders.updateStatus');
        
        // Transaction Management
        Route::get('transactions', [TransactionController::class, 'index'])->name('kasir.transactions.index');
        Route::get('orders/{order}/payment', [TransactionController::class, 'create'])->name('kasir.transactions.create');
        Route::post('orders/{order}/payment', [TransactionController::class, 'store'])->name('kasir.transactions.store');
        Route::get('transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('kasir.transactions.receipt');
        Route::post('orders/{order}/midtrans-token', [TransactionController::class, 'createMidtransSnapToken'])->name('kasir.transactions.createMidtransSnapToken');
        
        // API Routes
        Route::get('api/orders/stats', [OrderController::class, 'todayStats'])->name('kasir.api.orders.stats');
        Route::get('api/transactions/stats', [TransactionController::class, 'todayStats'])->name('kasir.api.transactions.stats');
    });
});
/*
|--------------------------------------------------------------------------
| RUTE UNTUK CALLBACK / WEBHOOK DARI PIHAK KETIGA
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;
use App\Http\Middleware\KasirMiddleware;
use App\Http\Controllers\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Kasir\AuthController as KasirAuthController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Kasir\ShiftController as KasirShiftController;
use App\Http\Controllers\Kasir\MidtransCallbackController;
// ✅ PERBAIKAN: Tambahkan use statement untuk Admin ProfileController
use App\Http\Controllers\Admin\ProfileController; // Pastikan ini mengacu pada ProfileController yang diperbarui
use App\Http\Controllers\Admin\TransactionExportController;
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
Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.show'); // Rute ini sudah ada
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

    // Rute password reset
    Route::get('/password/forgot', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('/password/email', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset.form');
    Route::get('/password/reset', [AdminForgotPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/password/reset', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin.password.update');


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

        Route::post('/menus/{menu}/toggle-availability', [Admin\MenuController::class, 'toggleAvailability'])
    ->name('admin.menus.toggle-availability');

        Route::resource('shifts', Admin\ShiftController::class)->names([
            'index' => 'admin.shifts.index',
            'create' => 'admin.shifts.create',
            'store' => 'admin.shifts.store',
            'show' => 'admin.shifts.show',
            'edit' => 'admin.shifts.edit',
            'update' => 'admin.shifts.update',
            'destroy' => 'admin.shifts.destroy'
        ]);

        Route::get('transactions/export', [Admin\TransactionController::class, 'export'])->name('admin.transactions.export');

        Route::resource('transactions', Admin\TransactionController::class)->names([
            'index' => 'admin.transactions.index',
            'create' => 'admin.transactions.create',
            'store' => 'admin.transactions.store',
            'show' => 'admin.transactions.show',
            'edit' => 'admin.transactions.edit',
            'update' => 'admin.transactions.update',
            'destroy' => 'admin.transactions.destroy'
        ]);

        // ✅ PERBAIKAN: Tambahkan rute untuk Admin Profile
        Route::prefix('profile')->name('admin.profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        });

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
            Route::get('/admin/transactions/export', [TransactionExportController::class, 'export'])->name('admin.transactions.export');

            Route::post('/admin/menus/{id}/toggle-availability', [Admin\MenuController::class, 'toggleAvailability'])
         ->name('admin.menus.toggle-availability');
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
    Route::middleware(['auth:kasir', KasirMiddleware::class])->group(function () {
        // Dashboard POS
        Route::get('dashboard', [DashboardController::class, 'index'])->name('kasir.dashboard');
        Route::get('pos', [DashboardController::class, 'index'])->name('kasir.pos');
        
        // Shift Management
        Route::get('shift', [KasirShiftController::class, 'showShiftPage'])->name('kasir.shift');
        Route::post('shift/start', [KasirShiftController::class, 'startShift'])->name('kasir.shift.start');
        Route::post('shift/end', [KasirShiftController::class, 'endShift'])->name('kasir.shift.end');
        
        // Order Management
        Route::get('orders', [Kasir\OrderController::class, 'index'])->name('kasir.orders.index');
        Route::get('orders/{order}', [Kasir\OrderController::class, 'show'])->name('kasir.orders.show');
        Route::post('orders/create', [Kasir\OrderController::class, 'storeTakeawayOrder'])->name('kasir.orders.storeTakeaway');
        Route::post('orders/{order}/confirm', [Kasir\OrderController::class, 'confirm'])->name('kasir.orders.confirm');
        Route::post('orders/{order}/cancel', [Kasir\OrderController::class, 'cancel'])->name('kasir.orders.cancel');
        Route::post('orders/{order}/status', [Kasir\OrderController::class, 'updateStatus'])->name('kasir.orders.updateStatus');
        
        // Transaction Management
        Route::get('transactions', [Kasir\TransactionController::class, 'index'])->name('kasir.transactions.index');
        Route::get('transactions/{transaction}', [Kasir\TransactionController::class, 'show'])->name('kasir.transactions.show');
        Route::get('orders/{order}/payment', [Kasir\TransactionController::class, 'create'])->name('kasir.transactions.create');
        Route::post('orders/{order}/payment', [Kasir\TransactionController::class, 'store'])->name('kasir.transactions.store');
        Route::get('transactions/{transaction}/receipt', [Kasir\TransactionController::class, 'receipt'])->name('kasir.transactions.receipt');
        Route::post('orders/{order}/midtrans-token', [Kasir\TransactionController::class, 'createMidtransSnapToken'])->name('kasir.transactions.createMidtransSnapToken');
        
        // API Routes
        Route::get('api/orders/stats', [Kasir\OrderController::class, 'todayStats'])->name('kasir.api.orders.stats');
        Route::get('api/transactions/stats', [Kasir\TransactionController::class, 'todayStats'])->name('kasir.api.transactions.stats');
    });
});
/*
|--------------------------------------------------------------------------
| RUTE UNTUK CALLBACK / WEBHOOK DARI PIHAK KETIGA
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');

Route::post('/test-csrf', function () {
    Log::info('CSRF TEST SUKSES! Notifikasi bisa masuk.');
    return response()->json(['message' => 'CSRF Berhasil dilewati!']);
});

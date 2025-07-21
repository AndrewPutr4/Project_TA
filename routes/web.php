<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Import Auth
use App\Http\Controllers\HomeController;
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
// IMPORT CONTROLLER BARU UNTUK SHIFT KASIR
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

// RUTE BARU UNTUK RIWAYAT PESANAN
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
    Route::get('/register', [App\Http\Controllers\Admin\Auth\AuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [App\Http\Controllers\Admin\Auth\AuthController::class, 'register'])->name('admin.register.submit');
    Route::get('/login', [App\Http\Controllers\Admin\Auth\AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\AuthController::class, 'logout'])->name('admin.logout');

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
        Route::get('transactions/export', [Admin\TransactionExportController::class, 'export'])
            ->name('admin.transactions.export');
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
        Route::get('kasir/register', [Admin\KasirController::class, 'showRegisterForm'])->name('kasir.register');
        Route::post('kasir/register', [Admin\KasirController::class, 'register']);
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
    // Kasir Auth
    Route::get('login', [KasirAuthController::class, 'showLoginForm'])->name('kasir.login');
    Route::post('login', [KasirAuthController::class, 'login'])->name('kasir.login.submit');
    Route::post('logout', [KasirAuthController::class, 'logout'])->name('kasir.logout');
    
    // Kasir Protected Routes
    Route::middleware('auth:kasir')->group(function () {
        // Dashboard (Menggunakan Controller baru)
        Route::get('dashboard', [Kasir\DashboardController::class, 'index'])->name('kasir.dashboard');

        // Shift Management (MENGGUNAKAN CONTROLLER BARU)
        Route::get('shift', [KasirShiftController::class, 'showShiftPage'])->name('kasir.shift');
        Route::post('shift/start', [KasirShiftController::class, 'startShift'])->name('kasir.shift.start');
        Route::post('shift/end', [KasirShiftController::class, 'endShift'])->name('kasir.shift.end');

        // Order Management
        Route::get('orders', [Kasir\OrderController::class, 'index'])->name('kasir.orders.index');
        Route::get('orders/pos', [Kasir\OrderController::class, 'pos'])->name('kasir.pos');  // Add this line
        Route::get('orders/{order}', [Kasir\OrderController::class, 'show'])->name('kasir.orders.show');
        Route::post('orders', [Kasir\OrderController::class, 'store'])->name('orders.store');
        Route::post('orders/{order}/status', [Kasir\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::get('api/orders/stats', [Kasir\OrderController::class, 'todayStats'])->name('orders.stats');
        
        // == RUTE BARU UNTUK TAKEAWAY ==
        Route::post('orders/takeaway', [Kasir\OrderController::class, 'storeTakeawayOrder'])->name('kasir.orders.storeTakeaway');

        // Transaction Management
        Route::get('transactions', [Kasir\TransactionController::class, 'index'])->name('kasir.transactions.index');
        Route::get('transactions/{transaction}', [Kasir\TransactionController::class, 'show'])->name('kasir.transactions.show');
        Route::get('orders/{order}/payment', [Kasir\TransactionController::class, 'create'])->name('kasir.transactions.create');
        Route::post('orders/{order}/payment', [Kasir\TransactionController::class, 'store'])->name('kasir.transactions.store');
        Route::get('transactions/{transaction}/receipt', [Kasir\TransactionController::class, 'receipt'])->name('kasir.transactions.receipt');
        Route::post('orders/{order}/midtrans-token', [Kasir\TransactionController::class, 'createMidtransSnapToken'])
            ->name('kasir.transactions.createMidtransSnapToken');
        Route::get('api/transactions/stats', [Kasir\TransactionController::class, 'todayStats'])->name('kasir.transactions.todayStats');
    });
});

// Tambahkan di dalam group admin (atau sesuaikan middleware/namespace)
Route::post('/admin/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');

// Tambahkan route untuk forgot password admin (form permintaan reset password)
// Forgot password form
Route::get('/admin/password/forgot', function () {
    return view('admin.auth.forgot-password');
})->name('admin.password.request');

// Proses cek email
Route::post('/admin/password/email', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('admin.password.email');

// Form input password baru (email via query string)
Route::get('/admin/password/reset', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset.form');

// Proses ganti password
Route::post('/admin/password/reset', [\App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'resetPassword'])
    ->name('admin.password.reset');

// Alias agar tidak error jika ada view/redirect ke route('password.reset')
Route::get('/password/reset', function () {
    return redirect()->route('admin.password.reset.form');
})->name('password.reset');
Route::get('/password/reset', function () {
    return redirect()->route('admin.password.reset.form');
})->name('password.reset');
/*
|--------------------------------------------------------------------------
| RUTE UNTUK CALLBACK / WEBHOOK DARI PIHAK KETIGA
|--------------------------------------------------------------------------
| Letakkan di sini agar tidak terkena middleware otentikasi.
*/
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');

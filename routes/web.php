<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;
use App\Models\Category;
use App\Models\Menu;
use App\Http\Middleware\KasirMiddleware;
use Illuminate\Http\Request;

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
Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');

//======================================================================
// RUTE API (UNTUK JAVASCRIPT/AJAX)
//======================================================================
Route::prefix('api')->name('api.')->group(function () {
    Route::post('cart/add', [CartController::class, 'addApi'])->name('cart.add');
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
// RUTE UNTUK KASIR (DARI FILE LAMA + YANG BARU DIADAPTASI)
//======================================================================
Route::prefix('kasir')->name('kasir.')->group(function () {
    // Kasir Auth
    Route::get('login', [Kasir\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Kasir\AuthController::class, 'login']);
    Route::post('logout', [Kasir\AuthController::class, 'logout'])->name('logout');

    // Kasir Protected Routes
    Route::middleware([KasirMiddleware::class])->group(function () {
        // Dashboard
        Route::get('dashboard', function () {
            if (!session('shift_active')) {
                return redirect()->route('kasir.shift')->with('message', 'Silakan mulai shift terlebih dahulu.');
            }

            $categories = \App\Models\Category::all();
            $selectedCategory = null;
            $foods = \App\Models\Menu::all();

            return view('kasir.dashboard', compact('categories', 'foods', 'selectedCategory'));
        })->name('dashboard');

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
        Route::get('api/orders/stats', [Kasir\OrderController::class, 'todayStats'])->name('orders.stats');

        // Transaction Management (SUDAH ADA DI VERSI LAMA)
        Route::get('transactions', [Kasir\TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [Kasir\TransactionController::class, 'show'])->name('transactions.show');
        Route::get('orders/{order}/payment', [Kasir\TransactionController::class, 'create'])->name('transactions.create');
        Route::post('orders/{order}/payment', [Kasir\TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}/receipt', [Kasir\TransactionController::class, 'receipt'])->name('transactions.receipt');
        Route::get('api/transactions/stats', [Kasir\TransactionController::class, 'todayStats'])->name('transactions.stats');
        Route::post('transactions/create', [Kasir\TransactionController::class, 'storeFromCashier'])->name('transactions.storeFromCashier');
    });
});

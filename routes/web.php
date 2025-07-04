<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Kasir;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//======================================================================
// RUTE UNTUK PENGUNJUNG (CUSTOMER)
//======================================================================

// Halaman Utama, sekarang ditangani oleh HomeController untuk kerapian
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute untuk Keranjang Belanja (diakses via browser)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); // Fallback jika JS mati
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
// ✅ PERBAIKAN: Menghapus '/' di depan URL dan 'api.' di dalam nama rute
Route::prefix('api')->name('api.')->group(function () {
    // URL akan menjadi: /api/cart/add
    // Nama rute akan menjadi: api.cart.add
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
    Route::middleware('auth:web')->group(function () { // Pastikan menggunakan guard 'web' untuk admin
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
    // Rute otentikasi kasir
    Route::get('login', [Kasir\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [Kasir\AuthController::class, 'login']);
    Route::post('logout', [Kasir\AuthController::class, 'logout'])->name('logout');

    // Rute yang dilindungi otentikasi kasir
    Route::middleware('auth:kasir')->group(function () { // Gunakan guard 'kasir'
        Route::get('dashboard', [Kasir\AuthController::class, 'index'])->name('dashboard');
        // ... rute kasir lainnya
    });
});


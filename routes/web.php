<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryMenuController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\KasirMiddleware;

// Halaman utama
Route::get('/', function (\Illuminate\Http\Request $request) {
    $categories = Category::all();
    $selectedCategory = null;
    $foods = Menu::query();

    if ($request->has('category')) {
        $selectedCategory = Category::find($request->category);
        if ($selectedCategory) {
            $foods = $foods->where('category_id', $selectedCategory->id);
        }
    }

    $foods = $foods->get();

    return view('welcome', compact('categories', 'foods', 'selectedCategory'));
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::get('register', [App\Http\Controllers\Admin\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Group admin dengan middleware auth
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('menus', App\Http\Controllers\Admin\MenuController::class);
    Route::resource('shifts', App\Http\Controllers\Admin\ShiftController::class);
    Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);

    // Tambahkan route register kasir
    Route::get('kasir/register', [App\Http\Controllers\Admin\KasirController::class, 'showRegisterForm'])->name('kasir.register');
    Route::post('kasir/register', [App\Http\Controllers\Admin\KasirController::class, 'register'])->name('kasir.register');

    // CRUD Kasir (admin) - gunakan User model dengan role kasir
    Route::resource('kasir', App\Http\Controllers\Admin\KasirController::class)
        ->except(['show', 'edit']); // Hapus 'create' dari except agar route register kasir tersedia
});

// Kasir Auth Routes
Route::prefix('kasir')->name('kasir.')->group(function () {
    // Login Kasir
    Route::get('login', [App\Http\Controllers\Kasir\AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('login', [App\Http\Controllers\Kasir\AuthController::class, 'login']);

    // Logout Kasir
    Route::post('logout', [App\Http\Controllers\Kasir\AuthController::class, 'logout'])
        ->name('logout');

    // Halaman kasir (hanya untuk kasir yang sudah login)
    Route::middleware([KasirMiddleware::class])->group(function () {
        Route::get('dashboard', function () {
            if (!session('shift_active')) {
                return redirect()->route('kasir.shift')->with('message', 'Silakan mulai shift terlebih dahulu.');
            }
            $categories = \App\Models\Category::all();
            $selectedCategory = null;
            $foods = \App\Models\Menu::all();
            return view('kasir.dashboard', compact('categories', 'foods', 'selectedCategory'));
        })->name('dashboard');

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
    });
});

// Customer Auth Routes
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('dashboard', function () {
        return view('customer.dashboard');
    })->middleware(['auth', 'customer'])->name('dashboard');
    Route::get('menu', function () {
        return view('customer.menu');
    })->name('menu');
    Route::get('transactions', function () {
        return view('customer.transactions');
    })->name('transactions');
});

Route::get('/category-menu', [CategoryMenuController::class, 'index']);
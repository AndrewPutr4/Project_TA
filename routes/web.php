<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryMenuController;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\KasirMiddleware;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes - Food Delivery Application
|--------------------------------------------------------------------------
*/

// ========== CUSTOMER ROUTES (Public) ==========

// Halaman utama / Home
Route::get('/', function (\Illuminate\Http\Request $request) {
    $categories = Category::all();
    $selectedCategory = null;
    $foods = Menu::query();

    // Filter by category
    if ($request->has('category')) {
        $selectedCategory = Category::find($request->category);
        if ($selectedCategory) {
            $foods = $foods->where('category_id', $selectedCategory->id);
        }
    }

    // Search functionality
    if ($request->has('search') && $request->search) {
        $search = $request->search;
        $foods->where(function($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    $foods = $foods->get();
    
    // Get cart from session
    $cart = session()->get('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));

    return view('welcome', compact('categories', 'foods', 'selectedCategory', 'cartCount'));
})->name('home');

// Search route
Route::get('/search', function(Request $request) {
    return redirect()->route('home', ['search' => $request->q]);
})->name('search');

// Cart routes - FIXED
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Order routes
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
Route::get('/order-success', [OrderController::class, 'success'])->name('order.success');

// Category Menu route
Route::get('/category-menu', [CategoryMenuController::class, 'index']);

// ========== ADMIN ROUTES ==========

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login & Register
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::get('register', [App\Http\Controllers\Admin\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Admin\AuthController::class, 'register']);
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Controllers
    Route::resource('menus', App\Http\Controllers\Admin\MenuController::class);
    Route::resource('shifts', App\Http\Controllers\Admin\ShiftController::class);
    Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);
    
    // Kasir Management
    Route::get('kasir/register', [App\Http\Controllers\Admin\KasirController::class, 'showRegisterForm'])->name('kasir.register');
    Route::post('kasir/register', [App\Http\Controllers\Admin\KasirController::class, 'register'])->name('kasir.register');
    Route::resource('kasir', App\Http\Controllers\Admin\KasirController::class)->except(['show', 'edit']);
});

// ========== KASIR ROUTES ==========

Route::prefix('kasir')->name('kasir.')->group(function () {
    // Kasir Auth
    Route::get('login', [App\Http\Controllers\Kasir\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Kasir\AuthController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Kasir\AuthController::class, 'logout'])->name('logout');

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
    });
});

// ========== CUSTOMER ROUTES (Authenticated) ==========

Route::prefix('customer')->name('customer.')->group(function () {
    // Customer Dashboard
    Route::get('dashboard', function () {
        return view('customer.dashboard');
    })->middleware(['auth', 'customer'])->name('dashboard');

    // Customer Menu
    Route::get('menu', function () {
        $categories = Category::all();
        $foods = Menu::all();
        return view('customer.menu', compact('categories', 'foods'));
    })->name('menu');

    // Customer Transactions
    Route::get('transactions', function () {
        return view('customer.transactions');
    })->name('transactions');

    // Selected Menu
    Route::get('selected-menu', function (Request $request) {
        $selectedMenus = [];
        if ($request->has('items')) {
            $selectedMenus = json_decode($request->input('items'), true) ?? [];
        }
        return view('customer-selected-menu', compact('selectedMenus'));
    })->name('selected-menu');
});

// ========== API ROUTES (AJAX) ==========

Route::prefix('api')->name('api.')->group(function () {
    // Cart API
    Route::post('/cart/add', [CartController::class, 'addApi'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateApi'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeApi'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearApi'])->name('cart.clear');
    
    // Order API
    Route::post('/order/checkout', [OrderController::class, 'checkoutApi'])->name('order.checkout');
});

// ========== FALLBACK ROUTES ==========

// Redirect old routes for compatibility
Route::get('/home', function() {
    return redirect()->route('home');
});

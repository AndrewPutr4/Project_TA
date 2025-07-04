<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    
    // Pastikan alias untuk middleware Anda terdaftar di sini
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'kasir' => \App\Http\Middleware\KasirMiddleware::class,
    'customer' => \App\Http\Middleware\CustomerMiddleware::class,
    ];

    // ...existing code...
}
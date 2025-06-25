<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // ...existing code...
        'kasir' => \App\Http\Middleware\KasirMiddleware::class,
        // ...existing code...
    ];

    // ...existing code...
}
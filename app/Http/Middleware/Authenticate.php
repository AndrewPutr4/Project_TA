<?php

namespace App\Http\Middleware;

// Make sure to extend the base Authenticate middleware
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        dd('Middleware Saya Dijalankan');
            if (! $request->expectsJson()) {
        
        // Jika mencoba akses rute admin, arahkan ke login admin
        if ($request->routeIs('admin.*')) {
            return route('admin.login');
        }

        // Jika mencoba akses rute kasir, arahkan ke login kasir
        if ($request->routeIs('kasir.*')) {
            return route('kasir.login');
        }
    }
        // For any other case, or for JSON requests, do nothing.
        return route('login');
    }
}
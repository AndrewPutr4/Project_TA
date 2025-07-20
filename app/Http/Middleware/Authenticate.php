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
        // If the request doesn't expect a JSON response...
        if (! $request->expectsJson()) {
            
            // If the user is trying to access an admin route...
            if ($request->routeIs('admin.*')) {
                // ...redirect them to the admin login page.
                return route('admin.login');
            }

            // If the user is trying to access a cashier route...
            if ($request->routeIs('kasir.*')) {
                // ...redirect them to the cashier login page.
                return route('kasir.login');
            }
        }

        // For any other case, or for JSON requests, do nothing.
        return null;
    }
}
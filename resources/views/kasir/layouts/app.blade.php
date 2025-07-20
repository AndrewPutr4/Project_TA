<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift; // Tambahkan ini

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('kasir.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('kasir')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Get the authenticated kasir user
            $kasir = Auth::guard('kasir')->user();
            
            // Buat shift baru saat login
            Shift::create([
                'kasir_name' => $kasir->name,
                'start_time' => now()->format('H:i:s'),
                // end_time bisa null atau diisi saat logout
                'end_time' => null 
            ]);

            session(['shift_active' => true]);
            return redirect()->intended('kasir/dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        // Update end_time saat logout
        $shift = Shift::where('kasir_name', Auth::guard('kasir')->user()->name)->whereNull('end_time')->first();
        if ($shift) {
            $shift->update(['end_time' => now()->format('H:i:s')]);
        }
        
        session()->forget('shift_active');
        Auth::guard('kasir')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('kasir.login');
    }
}
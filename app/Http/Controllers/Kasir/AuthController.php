<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('kasir.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'kasir';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect langsung ke dashboard kasir
            return redirect()->route('kasir.dashboard');
        }
        return back()->withErrors(['Email atau password salah, atau bukan akun kasir.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('shift_active');
        return redirect()->route('kasir.login');
    }
}

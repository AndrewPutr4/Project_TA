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
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'kasir';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Buat shift baru saat login
            Shift::create([
                'kasir_name' => Auth::user()->name,
                'start_time' => now()->format('H:i:s'),
                // end_time bisa null atau diisi saat logout
                'end_time' => null 
            ]);

            session(['shift_active' => true]);
            return redirect()->route('kasir.dashboard');
        }
        return back()->withErrors(['Email atau password salah, atau bukan akun kasir.']);
    }

    public function logout(Request $request)
    {
        // Update end_time saat logout
        $shift = Shift::where('kasir_name', Auth::user()->name)->whereNull('end_time')->first();
        if ($shift) {
            $shift->update(['end_time' => now()->format('H:i:s')]);
        }
        
        session()->forget('shift_active');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('kasir.login');
    }
}
<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input seperti biasa
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $credentials['email'])->first();

        // 3. Lakukan pengecekan:
        //    - Apakah user ada?
        //    - Apakah password cocok?
        //    - Apakah rolenya adalah 'admin'?
        if ($user && Hash::check($credentials['password'], $user->password) && $user->role === 'admin') {
            
            // 4. Jika semua benar, login menggunakan guard 'admin'
            Auth::guard('admin')->login($user);
            
            $request->session()->regenerate();
            
            return redirect()->intended('admin/dashboard');
        }

        // 5. Jika salah satu kondisi tidak terpenuhi, kembalikan error
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok atau Anda bukan admin.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // tambahkan kolom role jika ada, misal: 'role' => 'admin'
            'role' => 'admin',
        ]);

        Auth::guard('admin')->login($user);

        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/admin/login');
    }
}
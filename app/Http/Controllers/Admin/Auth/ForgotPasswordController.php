<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        // ✅ UBAH INI: dari 'admin.auth.password.email' 
        // menjadi 'admin.auth.forgot-password' agar sesuai dengan nama file Anda.
        return view('admin.auth.forgot-password'); 
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan di database.')->withInput();
        }

        // Redirect ke halaman reset password, bawa email sebagai parameter
        return redirect()->route('admin.password.reset', ['email' => $user->email]);
    }

    public function showResetForm(Request $request)
    {
        $email = $request->query('email', '');
        return view('admin.auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('admin.login')->with('status', 'Password berhasil diubah. Silakan login.');
    }
}
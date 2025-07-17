<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cek email di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Gmail anda tidak ada di database.');
        }

        // Kirim link reset password ke email (gunakan fitur bawaan Laravel)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email anda. Silakan cek Gmail anda.');
        } else {
            return back()->with('error', 'Gagal mengirim link reset password. Silakan coba lagi.');
        }
    }
}
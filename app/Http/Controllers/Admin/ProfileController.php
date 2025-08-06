<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin; // ✅ PERBAIKAN: Impor model Admin yang benar

class ProfileController extends Controller
{
    /**
     * Update the authenticated admin's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validasi, pastikan nama input di form Anda juga 'new_password' dan 'new_password_confirmation'
        $request->validate([
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // ✅ PENYEMPURNAAN: Ambil user yang sudah terotentikasi, lebih efisien
        /** @var \App\Models\Admin $user */
        $user = Auth::guard('admin')->user();

        // Check if the user exists.
        if (!$user) {
            // Seharusnya tidak akan terjadi jika rute dilindungi middleware auth:admin
            return back()->withErrors(['user' => 'Authenticated user not found.']);
        }

        // Update the user's password with the new hashed password.
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect back with a success message.
        return back()->with('status', 'Password berhasil diperbarui!');
    }
}
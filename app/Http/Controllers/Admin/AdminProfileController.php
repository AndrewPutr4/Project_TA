<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdminProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::guard('web')->user();
        if ($user && $user instanceof \App\Models\User) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            return back()->withErrors(['user' => 'User model does not support password update.']);
        }

        return back()->with('success', 'Password berhasil diubah.');
    }
}

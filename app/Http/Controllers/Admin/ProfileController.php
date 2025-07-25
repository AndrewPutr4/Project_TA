<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Ensure this is imported if your User model is in App\Models

class ProfileController extends Controller
{
    /**
     * Display the admin profile editing form.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Return the view for editing the admin profile.
        // You'll need to create this Blade view (e.g., resources/views/admin/profile/edit.blade.php)
        return view('admin.profile.edit');
    }

    /**
     * Update the authenticated admin's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate the incoming request data for password update.
        // No current_password validation as requested.
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Get the currently authenticated admin user using the 'admin' guard.
        $user = Auth::guard('admin')->user();

        // Check if the user exists and is an instance of the User model before attempting to save.
        if (!$user) {
            return back()->withErrors(['user' => 'Authenticated user not found.']);
        }

        // ✅ FIX: Added explicit type check for the User model
        if (!$user instanceof User) {
            // This error suggests your 'admin' guard might be returning a non-Eloquent object,
            // or your User model isn't correctly set up.
            return back()->withErrors(['user' => 'User object is not a valid model instance. Please check your User model and authentication guard configuration.']);
        }

        // Update the user's password with the new hashed password.
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect back with a success message.
        return back()->with('status', 'Password updated successfully!');
    }
}

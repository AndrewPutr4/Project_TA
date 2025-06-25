<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
    public function index()
    {
        $kasirs = User::where('role', 'kasir')->get();
        return view('admin.kasir.register', compact('kasirs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'kasir',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $kasir->id,
            'password' => 'nullable|string|min:6',
        ]);

        $kasir->name = $request->name;
        $kasir->email = $request->email;
        if ($request->filled('password')) {
            $kasir->password = Hash::make($request->password);
        }
        $kasir->role = 'kasir'; // Pastikan role tetap kasir
        $kasir->save();

        return redirect()->route('admin.dashboard')->with('success', 'Kasir berhasil diupdate.');
    }

    public function destroy($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $kasir->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Kasir berhasil dihapus.');
    }
}

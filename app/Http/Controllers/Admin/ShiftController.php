<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User; // <-- 1. TAMBAHKAN INI untuk mengakses model User

class ShiftController extends Controller
{
    public function index()
    {
        // 2. Gunakan 'with('kasir')' agar lebih efisien mengambil data relasi
        $shifts = Shift::with('kasir')->orderBy('date', 'desc')->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        // 3. Ambil semua user yang rolenya 'kasir'
        $kasirs = User::where('role', 'kasir')->get(); 
        // 4. Kirim data kasir ke view
        return view('admin.shifts.create', compact('kasirs'));
    }

    public function store(Request $request)
    {
        // 5. Ubah validasi dari 'kasir_name' menjadi 'kasir_id'
        $request->validate([
            'kasir_id'   => 'required|exists:users,id', // Validasi kasir_id
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time'   => 'nullable|date_format:H:i:s|after:start_time',
        ]);

        // 6. Ambil data kasir berdasarkan id yang dipilih
        $kasir = User::find($request->kasir_id);

        Shift::create([
            'kasir_id' => $kasir->id,
            'kasir_name' => $kasir->name, // Simpan juga namanya untuk arsip
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit(Shift $shift)
    {
        // 7. Sama seperti create, ambil daftar kasir untuk dropdown
        $kasirs = User::where('role', 'kasir')->get();
        return view('admin.shifts.edit', compact('shift', 'kasirs'));
    }

    public function update(Request $request, Shift $shift)
    {
        // 1. Validasi input untuk proses update
        $request->validate([
            'kasir_id'   => 'required|exists:users,id',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i|after:start_time',
        ]);
        
        // 2. Cari kasir berdasarkan ID yang dipilih
        $kasir = User::find($request->kasir_id);

        // 3. Update data shift
        $shift->update([
            'kasir_id'   => $kasir->id,
            'kasir_name' => $kasir->name,
            'date'       => $request->date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift berhasil dihapus.');
    }
}
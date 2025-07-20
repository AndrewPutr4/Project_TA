<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class ShiftController extends Controller
{
    /**
     * Menampilkan halaman manajemen shift.
     */
    public function showShiftPage()
    {
        $kasir = Auth::guard('kasir')->user();
        $activeShift = Shift::where('kasir_id', $kasir->id)
                            ->whereNull('end_time')
                            ->first();
        return view('kasir.shift', compact('activeShift'));
    }

    /**
     * Memulai shift baru untuk kasir yang sedang login.
     */
    public function startShift()
    {
        // 1. Dapatkan user kasir yang sedang login
        $kasir = Auth::guard('kasir')->user();

        // 2. Jika karena suatu alasan kasir tidak ditemukan, hentikan proses
        if (!$kasir) {
            // Ini akan mencatat error di log dan menghentikan proses
            Log::error('Gagal memulai shift: User kasir tidak terautentikasi.');
            return redirect('/login/kasir')->with('error', 'Sesi Anda tidak valid, silakan login kembali.');
        }

        // 3. Gunakan metode create() untuk menyimpan data. Ini adalah cara paling aman.
        try {
            Shift::create([
                'kasir_id'   => $kasir->id,
                'kasir_name' => $kasir->name,
                'date'       => now()->toDateString(),
                'start_time' => now()->format('H:i:s'),
                // end_time tidak perlu diisi, karena default-nya sudah NULL
            ]);

            session(['shift_active' => true]);
            return redirect()->route('kasir.shift')->with('message', 'Shift berhasil dimulai.');

        } catch (\Exception $e) {
            // Jika masih gagal, catat error lengkapnya di file log
            Log::error('SQL ERROR - Gagal membuat shift untuk kasir ID: ' . $kasir->id . ' | Pesan: ' . $e->getMessage());
            
            // Tampilkan pesan error ke pengguna
            return redirect()->back()->with('error', 'Terjadi kesalahan internal saat memulai shift. Silakan hubungi administrator.');
        }
    }

    /**
     * Mengakhiri shift yang sedang aktif.
     */
    public function endShift()
    {
        $kasir = Auth::guard('kasir')->user();
        $shift = Shift::where('kasir_id', $kasir->id)
                        ->whereNull('end_time')
                        ->first();
                        
        if ($shift) {
            $shift->end_time = now()->format('H:i:s');
            $shift->save();
        }

        session()->forget('shift_active');
        return redirect()->route('kasir.shift')->with('message', 'Shift diakhiri.');
    }
}
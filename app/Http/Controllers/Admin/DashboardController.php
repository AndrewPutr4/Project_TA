<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua shift yang sedang aktif (belum ada end_time)
        $activeShifts = Shift::whereNull('end_time')->latest()->get();
        
        return view('admin.dashboard', compact('activeShifts'));
    }
}
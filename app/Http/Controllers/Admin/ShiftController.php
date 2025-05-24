<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kasir_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        Shift::create($request->all());
        return redirect()->route('admin.shifts.index');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'kasir_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        $shift->update($request->all());
        return redirect()->route('admin.shifts.index');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('admin.shifts.index');
    }
}

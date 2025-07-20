<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('kasir')->latest()->get();
        $kasirs = User::where('role', 'kasir')->get();
        
        return view('admin.transactions.index', compact('transactions', 'kasirs'));
    }

    public function create()
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'total' => 'required|numeric',
        ]);
        Transaction::create($request->all());
        return redirect()->route('admin.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'customer_name' => 'required',
            'total' => 'required|numeric',
        ]);
        $transaction->update($request->all());
        return redirect()->route('admin.transactions.index');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionExportController extends Controller
{
    public function export()
    {
        $transactions = Transaction::with('kasir')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.transactions.pdf', compact('transactions'));
        
        return $pdf->download('transactions.pdf');
    }
}

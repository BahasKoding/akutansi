<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function incomeStatement(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $transactions = Transaction::where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['account', 'category'])
            ->get();

        $totalIncome = $transactions->sum('amount');

        return view('reports.income-statement', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalIncome'
        ))->with('title', 'Income Statement');
    }

    public function downloadIncomeStatement(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $transactions = Transaction::where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['account', 'category'])
            ->get();

        $totalIncome = $transactions->sum('amount');

        // Load the PDF view
        $pdf = Pdf::loadView('reports.pdf', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        // Download the PDF
        return $pdf->download('income_statement.pdf');
    }
}

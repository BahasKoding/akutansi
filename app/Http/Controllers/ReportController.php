<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function getFilteredTransactions(Request $request)
    {
        $query = Transaction::where('type', 'income')
            ->with(['account', 'category'])
            ->orderBy('created_at');

        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        return $query->get();
    }

    public function incomeStatement(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $totalIncome = $transactions->sum('amount');

        return view('reports.income-statement', compact(
            'transactions',
            'totalIncome'
        ))->with('title', 'Income Statement');
    }

    public function previewPdf(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $totalIncome = $transactions->sum('amount');

        $period = $request->filled(['start_date', 'end_date'])
            ? Carbon::parse($request->start_date)->format('d M Y') . ' - ' . Carbon::parse($request->end_date)->format('d M Y')
            : 'All Time';

        $pdf = PDF::loadView('reports.pdf', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'period' => $period,
            'generatedAt' => now()->format('d M Y H:i:s')
        ]);

        return $pdf->stream('income_statement_preview.pdf');
    }

    public function downloadPdf(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $totalIncome = $transactions->sum('amount');

        $period = $request->filled(['start_date', 'end_date'])
            ? Carbon::parse($request->start_date)->format('d M Y') . ' - ' . Carbon::parse($request->end_date)->format('d M Y')
            : 'All Time';

        $pdf = PDF::loadView('reports.pdf', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'period' => $period,
            'generatedAt' => now()->format('d M Y H:i:s')
        ]);

        $filename = 'income_statement_' . ($request->filled(['start_date', 'end_date'])
            ? Carbon::parse($request->start_date)->format('d-m-Y') . '_to_' . Carbon::parse($request->end_date)->format('d-m-Y')
            : 'all_time') . '.pdf';

        return $pdf->download($filename);
    }
}

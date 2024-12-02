<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $currentBalance = Account::sum('balance');

        $recentTransactions = Transaction::with(['account', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $monthlyData = Transaction::selectRaw('MONTH(created_at) as month, SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $title = 'Dashboard'; // Set the title variable

        return view('dashboard.index', compact(
            'title', // Pass the title variable to the view
            'totalIncome',
            'currentBalance',
            'recentTransactions',
            'monthlyData'
        ));
    }
}

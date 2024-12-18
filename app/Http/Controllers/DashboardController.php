<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        $userRole = auth()->user()->role;

        // Common data for all roles
        $data['title'] = 'Dashboard';

        // Role-specific data
        switch ($userRole) {
            case 'owner':
                $data['totalIncome'] = Transaction::where('type', 'income')->sum('amount');
                $data['currentBalance'] = Account::sum('balance');
                $data['monthlyData'] = $this->getMonthlyData();
                $data['recentTransactions'] = Transaction::with(['account', 'category'])
                    ->latest()
                    ->take(5)
                    ->get();
                break;

            case 'finance':
                $data['todayIncome'] = Transaction::where('type', 'income')
                    ->whereDate('created_at', today())
                    ->sum('amount');
                $data['weeklyIncome'] = Transaction::where('type', 'income')
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('amount');
                $data['recentTransactions'] = Transaction::with(['account', 'category'])
                    ->latest()
                    ->take(5)
                    ->get();
                $data['monthlyData'] = $this->getMonthlyData();
                break;

            case 'admin':
                $data['totalAccounts'] = Account::count();
                $data['totalCategories'] = Category::count();
                $data['totalUsers'] = User::count();
                $data['accountTypes'] = Account::select('type', DB::raw('count(*) as count'))
                    ->groupBy('type')
                    ->get();
                break;
        }

        return view('dashboard.index', $data);
    }

    private function getMonthlyData()
    {
        return Transaction::selectRaw('MONTH(created_at) as month, SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}

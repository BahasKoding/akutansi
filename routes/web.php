<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transactions', TransactionController::class);
    Route::resource('journal-entries', JournalEntryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);

    Route::get('/reports/income-statement', [ReportController::class, 'incomeStatement'])
        ->name('reports.income-statement');
    Route::get('/download-income-statement', [ReportController::class, 'downloadIncomeStatement'])->name('download.income.statement');
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet'])
        ->name('reports.balance-sheet');
    Route::get('/reports/analysis', [ReportController::class, 'analysis'])
        ->name('reports.analysis');
});

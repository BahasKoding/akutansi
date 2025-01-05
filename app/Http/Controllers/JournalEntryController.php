<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    public function index()
    {
        // Mengambil semua journal entry yang terkait dengan transaksi income
        $journalEntries = JournalEntry::with(['transaction', 'debitAccount', 'creditAccount'])
            ->whereHas('transaction', function ($query) {
                $query->where('type', 'income');
            })
            ->get();

        // Calculate total debit and credit
        $totalDebit = $journalEntries->sum('amount'); // Assuming all entries are debit for income
        $totalCredit = $totalDebit; // For income, total credit will match total debit
        $totalEntries = $journalEntries->count();

        $title = 'Journal Entries'; // Set the title variable

        return view('journal-entries.index', compact(
            'title',
            'journalEntries',
            'totalDebit',
            'totalCredit',
            'totalEntries'
        ));
    }
}

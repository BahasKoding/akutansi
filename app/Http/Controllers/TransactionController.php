<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::income()->get(); // Fetch only income transactions
        $totalIncome = $transactions->sum('amount'); // Calculate total income
        $accounts = Account::all(); // Fetch all accounts
        $categories = Category::all();
        $title = 'Transactions'; // Set the title variable

        return view('transactions.index', compact(
            'title',
            'categories',
            'transactions',
            'totalIncome',
            'accounts' // Pass accounts to the view
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,transfer',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required_if:type,income|exists:categories,id',
            'destination_account_id' => 'required_if:type,transfer|exists:accounts,id|different:account_id',
        ]);

        DB::transaction(function () use ($validated) {
            $sourceAccount = Account::findOrFail($validated['account_id']);

            if ($validated['type'] === 'income') {
                // Handle income
                $sourceAccount->balance += $validated['amount'];
                $sourceAccount->save();

                Transaction::create($validated);
            } else {
                // Handle transfer
                $destinationAccount = Account::findOrFail($validated['destination_account_id']);

                $sourceAccount->balance -= $validated['amount'];
                $destinationAccount->balance += $validated['amount'];

                $sourceAccount->save();
                $destinationAccount->save();

                Transaction::create($validated);
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction recorded successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $sourceAccount = $transaction->account;

            if ($transaction->type === 'income') {
                $sourceAccount->balance -= $transaction->amount;
                $sourceAccount->save();
            } else {
                // Reverse transfer
                $sourceAccount->balance += $transaction->amount;
                $transaction->destinationAccount->balance -= $transaction->amount;

                $sourceAccount->save();
                $transaction->destinationAccount->save();
            }

            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}

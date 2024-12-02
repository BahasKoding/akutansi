<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        $totalAccounts = $accounts->count();
        $totalCash = $accounts->where('type', 'cash')->sum('balance');
        $totalBank = $accounts->where('type', 'bank')->sum('balance');

        $title = 'Accounts'; // Set the title variable

        return view('accounts.index', compact(
            'title',
            'accounts',
            'totalAccounts',
            'totalCash',
            'totalBank'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank',
            'balance' => 'required|numeric|min:0',
        ]);

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank',
            'balance' => 'required|numeric|min:0',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        if ($account->balance > 0) {
            return back()->with('error', 'Cannot delete account with existing balance.');
        }

        $account->delete();
        return redirect()->route('accounts.index')
            ->with('success', 'Account deleted successfully.');
    }
}

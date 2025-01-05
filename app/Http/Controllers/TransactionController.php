<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\JournalEntry;
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
        try {
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
                    // 1. Buat transaksi income
                    $transaction = Transaction::create($validated);

                    // 2. Update saldo akun
                    $sourceAccount->balance += $validated['amount'];
                    $sourceAccount->save();

                    // 3. Buat journal entry untuk income
                    JournalEntry::create([
                        'transaction_id' => $transaction->id,
                        'debit_account_id' => $sourceAccount->id,  // Kas/Bank bertambah (debit)
                        'credit_account_id' => 1,                  // Pendapatan bertambah (credit)
                        'amount' => $validated['amount']
                    ]);
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
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->with('error', 'Mohon periksa kembali data yang dimasukkan. Pastikan semua field yang diperlukan telah diisi dengan benar.')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Maaf, terjadi kesalahan saat menyimpan transaksi. Silakan coba lagi atau hubungi admin jika masalah berlanjut.')
                ->withInput();
        }
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

    public function edit(Transaction $transaction)
    {
        return response()->json([
            'transaction' => $transaction,
            'account' => $transaction->account,
            'category' => $transaction->category
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            // Reverse the old transaction
            $oldAccount = $transaction->account;
            $oldAccount->balance -= $transaction->amount;
            $oldAccount->save();

            // Apply the new transaction
            $newAccount = Account::findOrFail($validated['account_id']);
            $newAccount->balance += $validated['amount'];
            $newAccount->save();

            // Update the transaction
            $transaction->update($validated);

            // Update atau buat journal entry baru
            $journalEntry = JournalEntry::where('transaction_id', $transaction->id)->first();

            if ($journalEntry) {
                // Update journal entry yang ada
                $journalEntry->update([
                    'debit_account_id' => $validated['account_id'],
                    'amount' => $validated['amount'],
                    'updated_at' => now()
                ]);
            } else {
                // Buat journal entry baru jika tidak ada
                JournalEntry::create([
                    'transaction_id' => $transaction->id,
                    'debit_account_id' => $validated['account_id'],
                    'credit_account_id' => 1, // Akun pendapatan
                    'amount' => $validated['amount'],
                    'created_at' => $transaction->created_at,
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }
}

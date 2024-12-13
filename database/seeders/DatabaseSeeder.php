<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\JournalEntry;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Create Accounts
        $cashAccount = Account::create([
            'name' => 'Cash',
            'type' => 'cash',
            'balance' => 0
        ]);

        $bankAccount = Account::create([
            'name' => 'Bank BCA',
            'type' => 'bank',
            'balance' => 0
        ]);

        // Create Categories
        $categories = [
            Category::create(['name' => 'Daily Sales']),
            Category::create(['name' => 'Online Orders']),
            Category::create(['name' => 'Catering Service'])
        ];

        // Create Transactions with Journal Entries
        $dates = [
            Carbon::now()->subDays(4),
            Carbon::now()->subDays(3),
            Carbon::now()->subDays(2),
            Carbon::now()->subDays(1),
            Carbon::now(),
        ];

        foreach ($dates as $date) {
            // Create income transaction
            $amount = $faker->numberBetween(100000, 5000000);
            $account = $faker->randomElement([$cashAccount, $bankAccount]);

            $transaction = Transaction::create([
                'account_id' => $account->id,
                'category_id' => $faker->randomElement($categories)->id,
                'type' => 'income',
                'amount' => $amount,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Create corresponding journal entry (removed description field)
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'debit_account_id' => $account->id, // Money goes to cash/bank account
                'credit_account_id' => 1, // Income account
                'amount' => $amount,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Update account balance
            $account->balance += $amount;
            $account->save();
        }
            // Create Users with Correct Roles
            User::create([
                'name' => 'Admin',
                'email' => 'admin@restaurant.com',
                'password' => Hash::make('password123'),
                'role' => 'admin'  // Manages accounts, categories, and users
            ]);

            User::create([
                'name' => 'Finance Manager',
                'email' => 'finance@restaurant.com',
                'password' => Hash::make('password123'),
                'role' => 'finance'  // Records income and transfers
            ]);     
            
            User::create([
                'name' => 'Agus Yusuf Ramadhan',
                'email' => 'agus@restaurant.com',
                'password' => Hash::make('password123'),
                'role' => 'YMA'  // Records income and transfers
            ]);     

            User::create([
                'name' => 'Owner',
                'email' => 'owner@restaurant.com',
                'password' => Hash::make('password123'),
                'role' => 'owner'  // Access to financial reports and analysis
            ]);
    }
}

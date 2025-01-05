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

        // Create Categories
        $categories = [
            Category::create(['name' => 'Penjualan Harian']),
            Category::create(['name' => 'Penjualan Online']),
            Category::create(['name' => 'Katering']),
            Category::create(['name' => 'Event/Acara']),
            Category::create(['name' => 'Lain-lain'])
        ];

        // Create Accounts - Kas
        $cashAccounts = [
            Account::create([
                'name' => 'Kas Tunai',
                'type' => 'cash',
                'balance' => 0
            ]),
            Account::create([
                'name' => 'Kas Kecil',
                'type' => 'cash',
                'balance' => 0
            ]),
            Account::create([
                'name' => 'Kas Harian',
                'type' => 'cash',
                'balance' => 0
            ])
        ];

        // Create Accounts - Bank
        $bankAccounts = [
            Account::create([
                'name' => 'Bank BCA',
                'type' => 'bank',
                'balance' => 0
            ]),
            Account::create([
                'name' => 'Bank Mandiri',
                'type' => 'bank',
                'balance' => 0
            ])
        ];

        // Generate transactions for Dec 2024 - Jan 2025
        $startDate = Carbon::create(2024, 12, 1);
        $endDate = Carbon::create(2025, 1, 4);

        // Buat array tanggal berurutan (1 transaksi setiap 3-4 hari)
        $dates = collect();
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Skip hari Minggu
            if (!$currentDate->isSunday()) {
                $dates->push($currentDate->copy()->setTime(
                    $faker->numberBetween(8, 20), // Jam kerja 8 pagi - 8 malam
                    $faker->numberBetween(0, 59),
                    0
                ));
            }
            // Lompat 3-4 hari ke depan
            $currentDate->addDays($faker->numberBetween(3, 4));
        }

        // Pastikan ada 10 data
        while ($dates->count() < 10) {
            $randomDate = Carbon::create(
                $faker->dateTimeBetween($startDate, $endDate)
            )->setTime(
                $faker->numberBetween(8, 20),
                $faker->numberBetween(0, 59),
                0
            );

            if (!$randomDate->isSunday()) {
                $dates->push($randomDate);
            }
        }

        // Sort tanggal ascending
        $dates = $dates->sort()->take(10);

        // Generate transaksi untuk setiap tanggal
        foreach ($dates as $date) {
            $category = $faker->randomElement($categories);

            // Amount berdasarkan kategori dan hari
            $baseAmount = match ($category->name) {
                'Penjualan Harian' => $faker->numberBetween(500000, 2000000),
                'Penjualan Online' => $faker->numberBetween(200000, 1000000),
                'Katering' => $faker->numberBetween(2000000, 5000000),
                'Event/Acara' => $faker->numberBetween(3000000, 8000000),
                default => $faker->numberBetween(100000, 1000000)
            };

            // Bonus untuk hari Sabtu (weekend)
            $amount = $date->isSaturday() ? ($baseAmount * 1.3) : $baseAmount;

            // Pilih akun berdasarkan jumlah
            $account = $amount > 3000000
                ? $faker->randomElement($bankAccounts)
                : $faker->randomElement($cashAccounts);

            // Buat transaksi
            $transaction = Transaction::create([
                'account_id' => $account->id,
                'category_id' => $category->id,
                'type' => 'income',
                'amount' => $amount,
                'description' => "Pendapatan dari {$category->name} - " . $date->format('l'),
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Buat journal entry
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'debit_account_id' => $account->id,
                'credit_account_id' => 1,
                'amount' => $amount,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Update saldo akun
            $account->balance += $amount;
            $account->save();
        }

        // Create Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Finance',
            'email' => 'finance@restaurant.com',
            'password' => Hash::make('password123'),
            'role' => 'finance'
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@restaurant.com',
            'password' => Hash::make('password123'),
            'role' => 'owner'
        ]);
    }
}

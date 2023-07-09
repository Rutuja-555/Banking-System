<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Transaction Type 1 is for Withdraw
        DB::table('accounts')->insert([
            'acc_number' => 'Cust1'.Str::random(5),
            'transaction_type' => '1',
            'amount' => 200,
            'description' => 'Tuition Fees',
            'date' => Carbon::today(),  
        ]);

        // Transaction Type 2 is for Deposit
        DB::table('accounts')->insert([
            'acc_number' => 'Cust2'.Str::random(5),
            'transaction_type' => '2',
            'amount' => 500,
            'description' => 'Grow Credit',
            'date' => Carbon::today(),  
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Type 1 is for Customer
        DB::table('users')->insert([
            'name' => 'Customer 1',
            'email' => 'customer1@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 1,
            'total_balance' => '50000'
        ]);

        DB::table('users')->insert([
            'name' => 'Customer 2',
            'email' => 'customer2@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 1,
            'total_balance' => '4000'
            
        ]);

        // User Type 2 is for Banker
        DB::table('users')->insert([
            'name' => 'Banker 1',
            'email' => 'banker1@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 2,
        ]);
    }
}

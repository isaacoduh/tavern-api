<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sellers')->insert([
            [
                'active' => true,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'email_verified_at' => now(),
                'mobile_number' => '1234567890',
                'password' => Hash::make('password'),
                'avatar' => null,
                'is_owner' => false,
                'bank_name' => 'Bank of Laravel',
                'account_number' => '1234567890',
                'shop_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

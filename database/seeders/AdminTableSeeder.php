<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'active' => true,
            'is_super' => false,
            'first_name' => 'Admin',
            'last_name' => 'Musa',
            'email' => 'adminmusa@gmail.com',
            'password' => Hash::make('password'),
            'mobile_number' => '07512345678'
        ]);
    }
}

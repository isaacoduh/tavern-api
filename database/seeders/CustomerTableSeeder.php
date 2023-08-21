<?php

namespace Database\Seeders;

use App\Models\Customer;
use Database\Factories\CustomerAddressFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $customers = [
            [
                "first_name" => "Rock",
                "last_name" => "Johnson",
                "mobile_number" => "07407073457",
                "email" => "rj@mail.com",
                "password" => Hash::make('password')
            ],
            [
                "first_name" => "Jane",
                "last_name" => "Smith",
                "mobile_number" => "07409073457",
                "email" => "janesmith@mail.com",
                "password" => Hash::make('password456')
            ],
            [
                "first_name" => "Emily",
                "last_name" => "Brown",
                "mobile_number" => "07307073457",
                "email" => "emilyb@mail.com",
                "password" => Hash::make('passwordxyz')
            ]
        ];

        foreach($customers as $customer){
            Customer::create($customer);
        }

        $noOfAddresses = 2;
        Customer::all()->each(function($customer) use ($noOfAddresses){
            CustomerAddressFactory::new([
                'customer_id' => $customer->id,
            ])->count($noOfAddresses)->create();
        });
    }
}

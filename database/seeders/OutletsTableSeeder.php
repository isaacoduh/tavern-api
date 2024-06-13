<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Outlet::create([
            'outlet_name' => 'Home Store',
            'location' => 'leeds',
            'phone_number' => '+447409091234',
            'contact_person' => 'Kamsi Doe',
            'imageUrl' => '',
            'active' => true,
            'delivery_available' => true,
            'delivery_estimate_min' => 30,
            'delivery_estimate_max' => 60,
            'city_id' => '1',
            'seller_id' => '1'
        ]);
        // Outlet::create([
        //     'outlet_name' => 'Tech Shop',
        //     'location' => 'Manchester',
        //     'phone_number' => '+447509091234',
        //     'contact_person' => 'Alex Smith',
        //     'imageUrl' => '',
        //     'active' => true,
        //     'delivery_available' => false,
        //     'delivery_estimate_min' => null,
        //     'delivery_estimate_max' => null,
        //     'city_id' => 2,
        //     'seller_id' => 2
        // ]);

        // Outlet::create([
        //     'outlet_name' => 'Gadget World',
        //     'location' => 'London',
        //     'phone_number' => '+447609091234',
        //     'contact_person' => 'Jane Doe',
        //     'imageUrl' => '',
        //     'active' => true,
        //     'delivery_available' => true,
        //     'delivery_estimate_min' => 20,
        //     'delivery_estimate_max' => 45,
        //     'city_id' => 3,
        //     'seller_id' => 3
        // ]);
    }
}

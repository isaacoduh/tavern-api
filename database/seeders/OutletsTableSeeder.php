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
            'city_id' => '1',
            'seller_id' => '1'
        ]);
    }
}

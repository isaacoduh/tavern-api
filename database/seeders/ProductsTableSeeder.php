<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Table Lamp',
            'price' => 12.99,
            'image_url' => 'https://res.cloudinary.com/isaacoduh/image/upload/v1720040805/tavern-dev/Hanni-Table-Lamp-1.jpg',
            
            'outlet_id' => '1'
        ]);
        Product::create([
            'name' => 'Toaster',
            'price' => 28.99,
            'image_url' => 'https://res.cloudinary.com/isaacoduh/image/upload/v1720040843/tavern-dev/8f6fe24f6cef5b349954772610b83395618e3a1e.png',
            'outlet_id' => '1'
        ]);
    }
}

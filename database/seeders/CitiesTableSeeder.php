<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
    \DB::table('cities')->delete();
        
        \DB::table('cities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'state_id' => 3911,
                'name' => 'leeds',
                'status' => 'active',
                'created_at' => '2021-03-04 03:53:46',
                'updated_at' => '2021-03-04 03:53:46',
                'deleted_at' => NULL,
            ),
        )
    );
    }
}
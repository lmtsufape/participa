<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('revisors')->insert([  //
            'trabalhosCorrigidos' => 0,
            'user_id' => 2,
            'areaId' => 2,
            'modalidadeId' => 2,
            'evento_id' => 2,
        ]);

        DB::table('revisors')->insert([  //
            'trabalhosCorrigidos' => 0,
            'user_id' => 3,
            'areaId' => 2,
            'modalidadeId' => 2,
            'evento_id' => 2,
        ]);
    }
}

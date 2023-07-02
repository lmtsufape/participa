<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoordComissaoCientificaSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name', 'CoordComissaoCientifica')->pluck('id');

        DB::table('coord_comissao_cientificas')->insert([
            'user_id' => $user_id[0],
        ]);
    }
}

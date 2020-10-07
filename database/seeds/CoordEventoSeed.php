<?php

use Illuminate\Database\Seeder;

class CoordEventoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name','coord')->pluck('id');

		DB::table('coordenador_eventos')->insert([
		'user_id' => $user_id[0],
		]);
    }
}

<?php

use Illuminate\Database\Seeder;

class ParticipanteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name','Participante')->pluck('id');

		DB::table('participantes')->insert([
		'user_id' => $user_id[0],
		]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipanteSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name', 'Participante')->pluck('id');

        DB::table('participantes')->insert([
            'user_id' => $user_id[0],
        ]);
    }
}

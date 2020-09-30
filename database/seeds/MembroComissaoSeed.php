<?php

use Illuminate\Database\Seeder;

class MembroComissaoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name','MembroComissao')->pluck('id');

		DB::table('membro_comissaos')->insert([
		'user_id' => $user_id[0],
		]);
    }
}

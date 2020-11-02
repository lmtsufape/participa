<?php

use Illuminate\Database\Seeder;

class CoordComissaoOrganizadoraSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->where('name','CoordComissaoOrganizadora')->pluck('id');

		DB::table('coord_comissao_organizadoras')->insert([
		'user_id' => $user_id[0],
        'eventos_id' => 1,
		]);
    }
}

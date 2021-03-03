<?php

use Illuminate\Database\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([  //
          'name' => 'Administrador',
          'email' => 'admin@ufrpe.br',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'email_verified_at' => '2020-02-15',
          'enderecoId' => 1,
        ]);

         

        DB::table('users')->insert([  //
          'name' => 'Participante',
          'email' => 'participante@ufrpe.br',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'email_verified_at' => '2020-02-15',
          'enderecoId' => 1,
        ]);

        DB::table('users')->insert([  //
          'name' => 'CoordComissaoCientifica',
          'email' => 'coordCC@ufrpe.br',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'email_verified_at' => '2020-02-15',
          'enderecoId' => 1,
        ]);

        DB::table('users')->insert([  //
          'name' => 'CoordComissaoOrganizadora',
          'email' => 'coordCO@ufrpe.br',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'email_verified_at' => '2020-02-15',
          'enderecoId' => 1,
        ]);   
    }
}

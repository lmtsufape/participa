<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('enderecos')->insert([  // 1
          'rua' => 'a',
          'numero' => 1,
          'bairro' => 'b',
          'cidade' => 'c',
          'uf'     => 'd',
          'cep'    => 2,
        ]);

        DB::table('users')->insert([  //
          'name' => 'teste',
          'email' => 'teste@teste',
          'password' => bcrypt('12345678'),
          'cpf' => 4,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'enderecoId' => 1,
        ]);

        DB::table('eventos')->insert([
          'nome'=>'Evento para Testes',
          'numeroParticipantes'=>60,
          'tipo'=>'teste',
          'dataInicio'=>'2020-02-15',
          'dataFim'=>'2020-02-15',
          'inicioSubmissao'=>'2020-02-15',
          'fimSubmissao'=>'2020-02-15',
          'inicioRevisao'=>'2020-02-15',
          'fimRevisao'=>'2020-02-15',
          'inicioResultado'=>'2020-02-15',
          'fimResultado'=>'2020-02-15',
          'possuiTaxa'=>true,
          'valorTaxa'=>10,
          'enderecoId'=>1,
          'coordenadorId'=>1,
        ]);
    }
}

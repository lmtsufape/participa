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
          'name' => 'coord',
          'email' => 'teste@teste',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'enderecoId' => 1,
        ]);

        DB::table('eventos')->insert([
          'nome'=>'Evento para Testes',
          // 'numeroParticipantes'=>60,
          'descricao'=>'Eventos Para teste do Sistema',
          'tipo'=>'teste',
          'dataInicio'=>'2020-02-15',
          'dataFim'=>'2020-02-15',
          'inicioSubmissao'=>'2020-02-15',
          'fimSubmissao'=>'2020-02-15',
          'inicioRevisao'=>'2020-02-15',
          'fimRevisao'=>'2020-02-15',
          'inicioResultado'=>'2020-02-15',
          'fimResultado'=>'2020-02-15',
          // 'possuiTaxa'=>true,
          // 'valorTaxa'=>10,
          'enderecoId'=>1,
          'coordenadorId'=>1,
        ]);

        DB::table('areas')->insert([
          'nome'      => 'area 1',
          'eventoId'  => 1,
        ]);

        DB::table('areas')->insert([
          'nome'      => 'area 2',
          'eventoId'  => 1,
        ]);

        DB::table('areas')->insert([
          'nome'      => 'area 3',
          'eventoId'  => 1,
        ]);

        DB::table('modalidades')->insert([
          'nome'      => 'mod 1',
        ]);

        for($i = 0; $i < 40; $i++){
          DB::table('users')->insert([  //
            'name' => 'teste',
            'email' => 'teste@teste'.$i,
            'password' => bcrypt('12345678'),
            'cpf' => ''.$i,
            'instituicao'     => 'd',
            'celular'    => 2,
            'especProfissional' => 'e',
            'enderecoId' => 1,
          ]);

          if($i < 20){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 1,
              'autorId' => $i+2,
            ]);
          }
          if($i >= 20 && $i < 30){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 2,
              'autorId' => $i+2,
            ]);
          }
          if($i >= 30){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 3,
              'autorId' => $i+2,
            ]);
          }

        }
    }
}

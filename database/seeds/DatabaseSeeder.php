<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
          'complemento' => 'complemento teste',
        ]);

        DB::table('enderecos')->insert([  // 1
          'rua' => 'R. Manoel Clemente',
          'numero' => '161',
          'bairro' => 'Santo Antônio',
          'cidade' => 'Garanhuns',
          'uf'     => 'PE',
          'cep'    => '55293-040',
          'complemento'    => 'complemento teste',
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
          'email_verified_at' => '2020-02-15',
        ]);

        // DB::table('users')->insert([  //
        //   'name' => 'Felipe',
        //   'email' => 'felipeaquac@yahoo.com.br',
        //   'password' => bcrypt('guedes80'),
        //   'cpf' => '999.999.999-99',
        //   'instituicao'     => 'UFAPE',
        //   'celular'    => '(99) 99999-9999',
        //   'especProfissional' => ' ',
        //   'enderecoId' => 1,
        //   'email_verified_at' => '2020-02-15',
        // ]);

        DB::table('users')->insert([  //
          'name' => 'Gabriel Coordenador',
          'email' => 'gabriel.uag.ufrpe@gmail.com',
          'password' => bcrypt('12345678'),
          'cpf' => 123132131,
          'instituicao'     => 'd',
          'celular'    => 2,
          'especProfissional' => 'e',
          'email_verified_at' => now(),
          'enderecoId' => 1,
        ]);


        // DB::table('users')->insert([  //
        //   'name' => 'Gabriel Revisor',
        //   'email' => 'gabriel.app.dev@gmail.com',
        //   'password' => bcrypt('12345678'),
        //   'cpf' => 123132131,
        //   'instituicao'     => 'd',
        //   'celular'    => 2,
        //   'especProfissional' => 'e',
        //   'email_verified_at' => '2020-02-15',
        //   'enderecoId' => 1,
        // ]);

        $evento = DB::table('eventos')->insert([
          'nome'=>'II CONGRESSO REGIONAL DE ZOOTECNIA',
          // 'numeroParticipantes'=>60,
          'descricao'=>'Cada autor inscrito poderá submeter até dois (2) resumos;
O número máximo de autores por trabalho será seis autores;
Os trabalhos deverão ser submetidos na forma de resumo simples com no máximo uma (01) página, no formato PDF;',
          'tipo'=>'teste',
          'dataInicio'=>now()->subDays(30),
          'dataFim'=>now()->addDays(30),
          'numMaxTrabalhos' => 10,
          'numMaxCoautores' => 10,
          // 'possuiTaxa'=>true,
          'recolhimento'=>'pago',
          'enderecoId'=>2,
          'coordenadorId'=>1,
          'publicado'=>true,
          'deletado'=>false,
        ]);



//         $evento = DB::table('eventos')->insert([
//           'nome'=>'Evento Teste',
//           // 'numeroParticipantes'=>60,
//           'descricao'=>'Cada autor inscrito poderá submeter até dois (2) resumos;
// O número máximo de autores por trabalho será seis autores;
// Os trabalhos deverão ser submetidos na forma de resumo simples com no máximo uma (01) página, no formato PDF;',
//           'tipo'=>'teste',
//           'dataInicio'=>'2021-02-19',
//           'dataFim'=>'2021-04-20',
//           'numMaxTrabalhos' => 10,
//           'numMaxCoautores' => 10,
//           // 'possuiTaxa'=>true,
//           'recolhimento'=>'pago',
//           'enderecoId'=>2,
//           'coordenadorId'=>2,
//           'publicado'=>true,
//           'deletado'=>false,
//         ]);
        // $user_id = DB::table('eventos')->where('nome','II CONGRESSO REGIONAL DE ZOOTECNIA')->pluck('id');


        DB::table('form_eventos')->insert([
          'eventoId'                       => 1,
          'etiquetanomeevento'             => 'Nome',
          'etiquetatipoevento'             => 'Tipo',
          'etiquetadescricaoevento'        => 'Descrição',
          'etiquetadatas'                  => 'Realização',
          'etiquetasubmissoes'             => 'Submissões',
          'etiquetabaixarregra'            => 'Regras',
          'etiquetabaixartemplate'         => 'Template',
          'etiquetaenderecoevento'         => 'Endereço',
          'etiquetamoduloinscricao'        => 'Inscrições',
          'etiquetamoduloprogramacao'      => 'Programação',
          'etiquetamoduloorganizacao'      => 'Organização',

        ]);

        DB::table('form_subm_trabas')->insert([
          'eventoId'                       => 1,
          'etiquetatitulotrabalho'         => 'Titulo',
          'etiquetaautortrabalho'          => 'Autor',
          'etiquetacoautortrabalho'        => 'Co-Autor',
          'etiquetaresumotrabalho'         => 'Resumo',
          'etiquetaareatrabalho'           => 'Área',
          'etiquetauploadtrabalho'         => 'Upload de Trabalho',
          'etiquetacampoextra1'            => 'Campo Extra',
          'etiquetacampoextra2'            => 'Campo Extra',
          'etiquetacampoextra3'            => 'Campo Extra',
          'etiquetacampoextra4'            => 'Campo Extra',
          'etiquetacampoextra5'            => 'Campo Extra',
          'ordemCampos'                    => 'etiquetatitulotrabalho,etiquetaautortrabalho,etiquetacoautortrabalho,etiquetaresumotrabalho,etiquetaareatrabalho,etiquetauploadtrabalho,checkcampoextra1,etiquetacampoextra1,select_campo1,checkcampoextra2,etiquetacampoextra2,select_campo2,checkcampoextra3,etiquetacampoextra3,select_campo3,checkcampoextra4,etiquetacampoextra4,select_campo4,checkcampoextra5,etiquetacampoextra5,select_campo5',
        ]);

        // DB::table('form_eventos')->insert([
        //   'eventoId'                       => 2,
        //   'etiquetanomeevento'             => 'Nome',
        //   'etiquetatipoevento'             => 'Tipo',
        //   'etiquetadescricaoevento'        => 'Descrição',
        //   'etiquetadatas'                  => 'Realização',
        //   'etiquetasubmissoes'             => 'Submissões',
        //   'etiquetabaixarregra'            => 'Regras',
        //   'etiquetabaixartemplate'         => 'Template',
        //   'etiquetaenderecoevento'         => 'Endereço',
        //   'etiquetamoduloinscricao'        => 'Inscrições',
        //   'etiquetamoduloprogramacao'      => 'Programação',
        //   'etiquetamoduloorganizacao'      => 'Organização',
        //   'modsubmissao'                   => true
        // ]);

        // DB::table('form_subm_trabas')->insert([
        //   'eventoId'                       => 2,
        //   'etiquetatitulotrabalho'         => 'Titulo',
        //   'etiquetaautortrabalho'          => 'Autor',
        //   'etiquetacoautortrabalho'        => 'Co-Autor',
        //   'etiquetaresumotrabalho'         => 'Resumo',
        //   'etiquetaareatrabalho'           => 'Área',
        //   'etiquetauploadtrabalho'         => 'Upload de Trabalho',
        //   'etiquetacampoextra1'            => 'Campo Extra',
        //   'etiquetacampoextra2'            => 'Campo Extra',
        //   'etiquetacampoextra3'            => 'Campo Extra',
        //   'etiquetacampoextra4'            => 'Campo Extra',
        //   'etiquetacampoextra5'            => 'Campo Extra',
        //   'ordemCampos'                    => 'etiquetatitulotrabalho,etiquetaautortrabalho,etiquetacoautortrabalho,etiquetaresumotrabalho,etiquetaareatrabalho,etiquetauploadtrabalho,checkcampoextra1,etiquetacampoextra1,select_campo1,checkcampoextra2,etiquetacampoextra2,select_campo2,checkcampoextra3,etiquetacampoextra3,select_campo3,checkcampoextra4,etiquetacampoextra4,select_campo4,checkcampoextra5,etiquetacampoextra5,select_campo5',
        // ]);

        $areasEventoZoo = [
                            'Produção e nutrição de ruminantes',
                            'Produção e nutrição de não-ruminantes',
                            'Reprodução e melhoramento de ruminantes',
                            'Reprodução e melhoramento de não-ruminantes',
                            'Tecnologia de produtos de origem animal',
                            'Nutrição e Criação de Animais Pet',
                            'Apicultura e Meliponicultura',
                            'Animais Silvestres',
                            'Extensão rural e Desenvolvimento Sustentável',
                            'Forragicultura'
                          ];

        for($i = 0; $i < sizeof($areasEventoZoo); $i++){
          DB::table('areas')->insert([
            'nome'      => $areasEventoZoo[$i],
            'eventoId'  => 1,
          ]);
          // DB::table('areas')->insert([
          //   'nome'      => $areasEventoZoo[$i],
          //   'eventoId'  => 2,
          // ]);
        }


        DB::table('modalidades')->insert([
          'nome'        => 'Resumo',
          'evento_id'   => 1,
          'inicioSubmissao' =>  now()->subDays(2),
          'fimSubmissao'    =>  now()->subDays(1),
          'inicioRevisao'   =>  now(),
          'fimRevisao'      =>  now()->addDay(),
          'inicioCorrecao'  =>  now()->addDays(1),
          'fimCorrecao'     =>  now()->addDays(2),
          'inicioValidacao' =>  now()->addDays(3),
          'fimValidacao'    =>  now()->addDays(4),
          'inicioResultado' =>  now()->addDays(5),
          'texto'           =>  null,
          'arquivo'         =>  null,
          'caracteres'      =>  true,
          'mincaracteres'   =>  1,
          'maxcaracteres'   =>  20,
          'palavras'        =>  false,
          'minpalavras'     =>  null,
          'maxpalavras'     =>  null,
          'pdf'             =>  null,
          'jpg'             =>  null,
          'regra'           =>  null,
          'template'        =>  null
        ]);

        // for($i = 0; $i < sizeof($areasEventoZoo); $i++){
        //   DB::table('area_modalidades')->insert([
        //     'areaId'       => $i + 1,
        //     'modalidadeId' => 1,
        //   ]);
        // }


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
            'email_verified_at' => '2020-02-15'
          ]);

          if($i < 20){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 1,
              'autorId' => $i+2,
              'eventoId' => 1,
              'avaliado' => 'nao'
            ]);
          }
          if($i >= 20 && $i < 30){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 2,
              'eventoId' => 1,
              'autorId' => $i+2,
              'avaliado' => 'nao'
            ]);
          }
          if($i >= 30){
            DB::table('trabalhos')->insert([
              'titulo' => 'trabalho' . $i,
              'autores' => '-',
              'data'  => '2020-02-15',
              'modalidadeId'  => 1,
              'areaId'  => 3,
              'eventoId' => 1,
              'autorId' => $i+2,
              'avaliado' => 'nao'
            ]);
          }

        }

        // DB::table('users')->insert([  //
        //   'name' => 'eu',
        //   'email' => 'asd@asd',
        //   'password' => bcrypt('12345678'),
        //   'cpf' => 123132131,
        //   'instituicao'     => 'd',
        //   'celular'    => 2,
        //   'especProfissional' => 'e',
        //   'email_verified_at' => '2020-02-15',
        //   'enderecoId' => 1,
        // ]);


        DB::table('tipo_atividades')->insert([  //
          'descricao' => 'palestra',
          'evento_id' => 1,
        ]);

        DB::table('tipo_atividades')->insert([  //
          'descricao' => 'minicurso',
          'evento_id' => 1,
        ]);

        DB::table('tipo_atividades')->insert([  //
          'descricao' => 'oficina',
          'evento_id' => 1,
        ]);

        DB::table('atividades')->insert([  //
          'titulo' => 'Atividade teste',
          'vagas' => 100,
          'valor' => 50.0,
          'descricao' => 'atividade criada pelo seeder',
          'local' => 'sala 14, 2º andar',
          'carga_horaria' => 8,
          'palavras_chave' => 'teste, testando',
          'visibilidade_participante' => true,
          'eventoId' => 1,
          'tipo_id' => 1,
        ]);


        DB::table('convidados')->insert([  //
          'nome' => 'Carlos',
          'email' => 'carlos.andre12@live.com',
          'funcao' => 'palestrante',
          'atividade_id' => 1,
        ]);

        DB::table('datas_atividades')->insert([
          'data' => now()->addDay(),
          'hora_inicio' => '14:00',
          'hora_fim' => '18:00',
          'atividade_id' => 1,
        ]);

        $this->call(UsersSeed::class);
        $this->call(AdministradorSeed::class);
        $this->call(CoordComissaoCientificaSeed::class);
        $this->call(CoordComissaoOrganizadoraSeed::class);
        $this->call(ParticipanteSeed::class);
        $this->call(CoordEventoSeed::class);
        $this->call(TipoPagamentoSeed::class);
        // $this->call(RevisorSeeder::class);
    }
}

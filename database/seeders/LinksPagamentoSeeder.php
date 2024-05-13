<?php

namespace Database\Seeders;

use App\Models\Inscricao\LinksPagamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LinksPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idEstudanteGradFragilidade = DB::table('categoria_participantes')->where('nome', 'Estudante de graduação - Situação de fragilidade econômica')->pluck('id');
        $idEstudanteGradSocio = DB::table('categoria_participantes')->where('nome', 'Estudante de graduação - SÓCIO ABZ ou SNPA')->pluck('id');
        $idEstudanteGradNaoSocio = DB::table('categoria_participantes')->where('nome', 'Estudante de graduação - NÃO SÓCIO')->pluck('id');
        $idEstudantePosGradSocio = DB::table('categoria_participantes')->where('nome', 'Estudante de pós-graduação - SÓCIO ABZ ou SNPA')->pluck('id');
        $idEstudantePosGradNaoSocio = DB::table('categoria_participantes')->where('nome', 'Estudante de pós-graduação - NÃO SÓCIO')->pluck('id');
        $idProfissionalSocio = DB::table('categoria_participantes')->where('nome', 'Profissional - SÓCIO ABZ ou SNPA')->pluck('id');
        $idProfissionalNaoSocio = DB::table('categoria_participantes')->where('nome', 'Profissional - NÃO SÓCIO')->pluck('id');
        $idCaravanaGrad = DB::table('categoria_participantes')->where('nome', 'Caravana graduação (10 pessoas)')->pluck('id');
        $idCaravanaPosGrad = DB::table('categoria_participantes')->where('nome', 'Caravana pós-graduação (5 pessoas)')->pluck('id');


        //Lote Promo 01/03 - 15/03
        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1KDub64",
            'valor' => 55,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idEstudanteGradFragilidade[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1XvPLUm",
            'valor' => 95,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idEstudanteGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1LfF8Vf",
            'valor' => 160,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idEstudanteGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2Yw4uGP",
            'valor' => 190,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idEstudantePosGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1XHkRyK",
            'valor' => 305,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idEstudantePosGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1UYwauP",
            'valor' => 265,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idProfissionalSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2CCfMBp",
            'valor' => 430,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idProfissionalNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2HKZq6T",
            'valor' => 850,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idCaravanaGrad[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2Gkh3rG",
            'valor' => 850,
            'dataInicio' => '01/03/2024',
            'dataFim' => '15/03/2024',
            'categoria_id' => $idCaravanaPosGrad[0],
        ]);

        //Lote 1 16/03 - 15/04
        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1BQTGGH",
            'valor' => 55,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idEstudanteGradFragilidade[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1wUB7YW",
            'valor' => 140,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idEstudanteGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/31on1ir",
            'valor' => 200,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idEstudanteGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1D99HbT",
            'valor' => 275,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idEstudantePosGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1VX9B6U",
            'valor' => 390,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idEstudantePosGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2F9EuwJ",
            'valor' => 325,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idProfissionalSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2y2idPq",
            'valor' => 495,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idProfissionalNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1boPPUb",
            'valor' => 1225,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idCaravanaGrad[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/28iDrRK",
            'valor' => 1225,
            'dataInicio' => '16/03/2024',
            'dataFim' => '15/04/2024',
            'categoria_id' => $idCaravanaPosGrad[0],
        ]);
        //Lote 2 16/05 - 15/06
        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1kWf8aY",
            'valor' => 55,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idEstudanteGradFragilidade[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1xWMToF",
            'valor' => 180,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idEstudanteGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/19gNiLZ",
            'valor' => 245,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idEstudanteGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/14ZRT11",
            'valor' => 360,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idEstudantePosGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1CmTSqg",
            'valor' => 475,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idEstudantePosGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/278bSk9",
            'valor' => 390,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idProfissionalSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1mm7qLo",
            'valor' => 555,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idProfissionalNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/18LNKtu",
            'valor' => 1605,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idCaravanaGrad[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2fUsz9S",
            'valor' => 1605,
            'dataInicio' => '16/05/2024',
            'dataFim' => '15/06/2024',
            'categoria_id' => $idCaravanaPosGrad[0],
        ]);

        //Lote 3 16/06
        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2gKZyz4",
            'valor' => 55,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idEstudanteGradFragilidade[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2S9bsou",
            'valor' => 220,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idEstudanteGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2yKkHVD",
            'valor' => 285,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idEstudanteGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1AAqgmo",
            'valor' => 440,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idEstudantePosGradSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2WnVSZx",
            'valor' => 555,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idEstudantePosGradNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/2cvii4c",
            'valor' => 455,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idProfissionalSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1i2zgXM",
            'valor' => 620,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idProfissionalNaoSocio[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1ArURaL",
            'valor' => 1980,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idCaravanaGrad[0],
        ]);

        DB::table('links_pagamento')->insert([
            'link' => "https://mpago.la/1VtTuZ4",
            'valor' => 1980,
            'dataInicio' => '16/06/2024',
            'dataFim' => '30/06/2024',
            'categoria_id' => $idCaravanaPosGrad[0],
        ]);
    }

    
}

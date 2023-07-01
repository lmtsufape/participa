<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPagamentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_pagamentos')->insert([  //
          'descricao' => 'cartao',
        ]);

        DB::table('tipo_pagamentos')->insert([  //
          'descricao' => 'boleto',
        ]);
    }
}

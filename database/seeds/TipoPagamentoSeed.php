<?php

use Illuminate\Database\Seeder;

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

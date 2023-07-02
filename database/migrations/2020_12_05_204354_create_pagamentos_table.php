<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('valor');
            $table->string('descricao')->nullable();
            $table->string('reference');
            $table->string('pagseguro_code');
            $table->integer('pagseguro_status');
            $table->timestamps();

            $table->bigInteger('tipo_pagamento_id')->nullable();
            $table->foreign('tipo_pagamento_id')->references('id')->on('tipo_pagamentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
}

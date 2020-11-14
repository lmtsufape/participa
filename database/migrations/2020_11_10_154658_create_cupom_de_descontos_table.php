<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCupomDeDescontosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupom_de_descontos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identificador');
            $table->float('valor');
            $table->boolean('porcentagem');
            $table->integer('quantidade_aplicacao');
            $table->date('inicio');
            $table->date('fim');
            $table->timestamps();
            $table->bigInteger('evento_id');

            $table->foreign('evento_id')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cupom_de_descontos');
    }
}

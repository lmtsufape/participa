<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabalhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabalhos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('titulo');
            $table->string('autores')->nullable();
            $table->date('data')->nullable();
            $table->text('resumo')->nullable();
            $table->text('avaliado')->nullable();

            $table->integer('modalidadeId');
            $table->integer('areaId');
            $table->integer('autorId');
            $table->integer('eventoId');

            $table->string('campoextra1')->nullable();
            $table->string('campoextra2')->nullable();
            $table->string('campoextra3')->nullable();
            $table->string('campoextra4')->nullable();
            $table->string('campoextra5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trabalhos');
    }
}

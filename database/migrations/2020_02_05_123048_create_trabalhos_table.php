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

            // Campos extras: Texto simples e Texto maior
            $table->string('campoextra1simples')->nullable();
            $table->text('campoextra1grande')->nullable();
            $table->string('campoextra2simples')->nullable();
            $table->text('campoextra2grande')->nullable();
            $table->string('campoextra3simples')->nullable();
            $table->text('campoextra3grande')->nullable();
            $table->string('campoextra4simples')->nullable();
            $table->text('campoextra4grande')->nullable();
            $table->string('campoextra5simples')->nullable();
            $table->text('campoextra5grande')->nullable();
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

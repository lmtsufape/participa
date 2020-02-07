<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('nome');
            $table->integer('numeroParticipantes');
            $table->string('tipo');
            $table->date('dataInicio');
            $table->date('dataFim');
            $table->date('inicioSubmissao');
            $table->date('fimSubmissao');
            $table->date('inicioRevisao');
            $table->date('fimRevisao');
            $table->date('inicioResultado');
            $table->date('fimResultado');
            $table->boolean('possuiTaxa');
            $table->double('valorTaxa');
            $table->string('fotoEvento')->nullable();

            $table->integer('enderecoId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}

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
            $table->string('nome')->nullable();
            // $table->integer('numeroParticipantes');
            $table->string('descricao')->nullable();
            $table->string('tipo')->nullable();
            $table->date('dataInicio')->nullable();
            $table->date('dataFim')->nullable();

            $table->boolean('exibir_calendario_programacao')->nullable();
            $table->string('pdf_programacao')->nullable();
            
            $table->integer('numMaxTrabalhos')->nullable();
            $table->integer('numMaxCoautores')->nullable();
            // $table->boolean('possuiTaxa');
            // $table->double('valorTaxa');
            $table->string('fotoEvento')->nullable();
            $table->boolean('publicado')->nullable();

            $table->integer('coord_comissao_cientifica_id')->nullable();
            $table->integer('coord_comissao_organizadora_id')->nullable();
            $table->integer('enderecoId')->nullable();
            $table->integer('coordenadorId')->nullable();
            $table->boolean('deletado')->nullable();;

            $table->timestamps();
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

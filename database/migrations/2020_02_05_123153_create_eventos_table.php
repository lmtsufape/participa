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
            $table->text('descricao')->nullable();
            $table->string('tipo')->nullable();
            $table->date('dataInicio')->nullable();
            $table->date('dataFim')->nullable();

            $table->boolean('exibir_calendario_programacao')->nullable();
            $table->string('pdf_programacao')->nullable();

            $table->integer('numMaxTrabalhos')->nullable();
            $table->integer('numMaxCoautores')->nullable();
            // $table->boolean('possuiTaxa');
            $table->string('recolhimento')->nullable();
            $table->string('fotoEvento')->nullable();
            // $table->string('timezone')->default('America/Recife');
            $table->boolean('publicado')->default(false);

            $table->unsignedBigInteger('coord_comissao_cientifica_id')->nullable();
            $table->unsignedBigInteger('coord_comissao_organizadora_id')->nullable();
            $table->unsignedBigInteger('enderecoId')->nullable();
            $table->unsignedBigInteger('coordenadorId')->nullable();
            $table->boolean('deletado')->nullable();

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

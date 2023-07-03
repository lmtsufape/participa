<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('titulo');
            $table->integer('vagas')->nullable();
            $table->float('valor')->nullable();
            $table->text('descricao');
            $table->string('local');
            $table->integer('carga_horaria')->nullable();
            $table->string('palavras_chave')->nullable();
            $table->boolean('visibilidade_participante');

            $table->integer('eventoId')->refereces('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atividades');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExibirPromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exibir_promocaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('promocao_id');
            $table->unsignedBigInteger('categoria_participante_id');
            $table->timestamps();

            $table->foreign('categoria_participante_id')->references('id')->on('categoria_participantes');
            $table->foreign('promocao_id')->references('id')->on('promocaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exibir_promocaos');
    }
}

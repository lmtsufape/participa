<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtividadesPromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atividades_promocaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('atividade_id');
            $table->bigInteger('promocao_id');

            $table->foreign('atividade_id')->references('id')->on('atividades');
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
        Schema::dropIfExists('atividades_promocaos');
    }
}

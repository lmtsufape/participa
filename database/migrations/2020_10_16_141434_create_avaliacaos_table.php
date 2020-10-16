<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('opcao_criterio_id');
            $table->bigInteger('revisor_id');
            $table->bigInteger('trabalho_id');
            $table->timestamps();

            $table->foreign('opcao_criterio_id')->references('id')->on('opcoes_criterios');
            $table->foreign('revisor_id')->references('id')->on('revisors');
            $table->foreign('trabalho_id')->references('id')->on('trabalhos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliacaos');
    }
}

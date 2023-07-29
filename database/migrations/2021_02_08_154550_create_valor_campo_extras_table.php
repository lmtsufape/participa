<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorCampoExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_campo_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscricao_id');
            $table->unsignedBigInteger('campo_formulario_id');
            $table->text('valor');
            $table->timestamps();

            $table->foreign('inscricao_id')->references('id')->on('inscricaos');
            $table->foreign('campo_formulario_id')->references('id')->on('campo_formularios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('valor_campo_extras');
    }
}

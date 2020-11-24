<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampoNecessariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campo_necessarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campo_formulario_id');
            $table->bigInteger('categoria_participante_id');
            $table->timestamps();

            $table->foreign('campo_formulario_id')->references('id')->on('campo_formularios');
            $table->foreign('categoria_participante_id')->references('id')->on('categoria_participantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campo_necessarios');
    }
}

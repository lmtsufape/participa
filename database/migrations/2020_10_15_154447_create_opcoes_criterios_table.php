<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcoesCriteriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcoes_criterios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome_opcao');
            $table->bigInteger('criterio_id');
            $table->double('valor_real');
            $table->timestamps();

            $table->foreign('criterio_id')->references('id')->on('criterios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opcoes_criterios');
    }
}

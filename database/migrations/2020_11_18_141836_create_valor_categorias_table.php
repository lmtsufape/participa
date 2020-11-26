<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValorCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor_categorias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('porcentagem');
            $table->float('valor');
            $table->date('inicio_prazo');
            $table->date('fim_prazo');
            $table->bigInteger('categoria_participante_id');
            $table->timestamps();

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
        Schema::dropIfExists('valor_categorias');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensagemParecersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensagem_parecers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('texto');
            $table->string('parecer');

            $table->unsignedBigInteger('evento_id')->nullable();
            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->unsignedBigInteger('modalidade_id')->nullable();
            $table->foreign('modalidade_id')->references('id')->on('modalidades');
            $table->unsignedBigInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');

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
        Schema::dropIfExists('mensagem_parecers');
    }
}

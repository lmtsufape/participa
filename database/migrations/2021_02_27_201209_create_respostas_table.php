<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('respostas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('revisor_id')->nullable();
            $table->foreign('revisor_id')->references('id')->on('revisors')->onDelete('cascade');

            $table->bigInteger('trabalho_id')->nullable();
            $table->foreign('trabalho_id')->references('id')->on('trabalhos')->onDelete('cascade');

            $table->bigInteger('pergunta_id');
            $table->foreign('pergunta_id')->references('id')->on('perguntas')->onDelete('cascade');
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
        Schema::dropIfExists('respostas');
    }
}

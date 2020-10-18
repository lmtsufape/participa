<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('prazo')->nullable();
            $table->integer('trabalhosCorrigidos')->nullable();
            $table->integer('correcoesEmAndamento')->nullable();

            // $table->unsignedBigInteger('eventos_id')->nullable();
            // $table->foreign('eventos_id')->references('id')->on('eventos');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('eventoId');
            $table->integer('areaId')->nullable();
            $table->integer('modalidadeId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisors');
    }
}

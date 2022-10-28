<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMidiaExtrasTrabalhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midia_extras_trabalho', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('caminho')->nullable();
            $table->unsignedBigInteger('midia_extra_id');
            $table->foreign('midia_extra_id')->references('id')->on('midia_extras');
            $table->unsignedBigInteger('trabalho_id');
            $table->foreign('trabalho_id')->references('id')->on('trabalhos');
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
        Schema::dropIfExists('midia_extras_trabalho');
    }
}

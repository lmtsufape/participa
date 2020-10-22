<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoRevisor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_revisor', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('revisor_id');
            $table->foreign('revisor_id')->references('id')->on('revisors');

            $table->unsignedBigInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos');

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
        Schema::dropIfExists('evento_revisor');
    }
}

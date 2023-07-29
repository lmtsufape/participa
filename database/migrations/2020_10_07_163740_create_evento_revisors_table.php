<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoRevisorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_revisors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('convite_aceito')->nullable();
            $table->unsignedBigInteger('evento_id')->nullable();
            $table->unsignedBigInteger('revisor_id')->nullable();
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('eventos');
            $table->foreign('revisor_id')->references('id')->on('revisors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_revisors');
    }
}

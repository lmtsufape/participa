<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisorTrabalho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisor_trabalho', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('revisor_id');
            $table->foreign('revisor_id')->references('id')->on('revisors');

            $table->unsignedBigInteger('trabalho_id');
            $table->foreign('trabalho_id')->references('id')->on('eventos');

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
        Schema::dropIfExists('revisor_trabalho');
    }
}

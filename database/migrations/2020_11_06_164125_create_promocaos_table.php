<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('evento_id');
            $table->float('valor');
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocaos');
    }
}

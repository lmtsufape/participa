<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpcaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opcaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('titulo');
            $table->enum('tipo', ['checkbox', 'radio'])->default('checkbox');
            $table->boolean('check')->default(false);

            $table->bigInteger('resposta_id');
            $table->foreign('resposta_id')->references('id')->on('respostas')->onDelete('cascade');
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
        Schema::dropIfExists('opcaos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissaoregrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regra_submis', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('nome');

            $table->bigInteger("modalidadeId")->nullable();
            $table->foreign("modalidadeId")->references("id")->on("modalidades");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissaoregras');
    }
}

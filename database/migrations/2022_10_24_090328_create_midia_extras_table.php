<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMidiaExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('midia_extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->boolean('pdf')->default(false);
            $table->boolean('jpg')->default(false);
            $table->boolean('jpeg')->default(false);
            $table->boolean('docx')->default(false);
            $table->boolean('odt')->default(false);
            $table->boolean('zip')->default(false);
            $table->boolean('svg')->default(false);
            $table->boolean('mp4')->default(false);
            $table->boolean('mp3')->default(false);
            $table->unsignedBigInteger('modalidade_id');
            $table->foreign('modalidade_id')->references('id')->on('modalidades');
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
        Schema::dropIfExists('midia_extras');
    }
}

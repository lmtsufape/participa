<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArquivoextraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivoextras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');

            $table->unsignedBigInteger('trabalhoId')->nullable();
            $table->foreign('trabalhoId')->references('id')->on('trabalhos');
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
        Schema::dropIfExists('arquivoextra');
    }
}

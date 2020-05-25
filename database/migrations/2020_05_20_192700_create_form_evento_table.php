<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('etiquetatipoevento')->nullable();
            $table->string('etiquetadescricaoevento')->nullable();
            $table->string('etiquetadatas')->nullable();
            $table->string('etiquetaenderecoevento')->nullable();
            $table->string('etiquetamoduloinscricao')->nullable();
            $table->string('etiquetamoduloprogramacao')->nullable();
            $table->string('etiquetamoduloorganizacao')->nullable();

            $table->boolean('modinscricao')->nullable();
            $table->boolean('modprogramacao')->nullable();
            $table->boolean('modorganizacao')->nullable();

            $table->bigInteger("eventoId")->nullable();
            $table->foreign("eventoId")->references("id")->on("eventos");
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
        Schema::dropIfExists('formeventos');
    }
}

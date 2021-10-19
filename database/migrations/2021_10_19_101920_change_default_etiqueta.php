<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultEtiqueta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_eventos', function (Blueprint $table) {
            $table->string('etiquetamoduloinscricao')->default('Inscrições')->change();
            $table->string('etiquetamoduloprogramacao')->default('Programação')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

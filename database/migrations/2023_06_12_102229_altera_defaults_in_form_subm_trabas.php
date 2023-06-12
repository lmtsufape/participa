<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlteraDefaultsInFormSubmTrabas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_subm_trabas', function (Blueprint $table) {
            $table->string('etiquetatitulotrabalho')->default('Título')->change();
            $table->string('etiquetaautortrabalho')->default('Autor(a)')->change();
            $table->string('etiquetacoautortrabalho')->default('Coautor(a)')->change();
            $table->string('etiquetauploadtrabalho')->default('Envio de Trabalho')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_subm_trabas', function (Blueprint $table) {
            //
        });
    }
}

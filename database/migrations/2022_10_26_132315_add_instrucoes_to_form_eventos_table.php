<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstrucoesToFormEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_eventos', function (Blueprint $table) {
            $table->string('etiquetabaixarinstrucoes')->default('Instruções aos avaliadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_eventos', function (Blueprint $table) {
            $table->dropColumn('etiquetabaixarinstrucoes');
        });
    }
}

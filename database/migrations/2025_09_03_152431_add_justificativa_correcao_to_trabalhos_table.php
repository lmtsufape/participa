<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJustificativaCorrecaoToTrabalhosTable extends Migration
{
    public function up()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->text('justificativa_correcao')->nullable()->after('avaliado'); // Adiciona a coluna para a justificativa
        });
    }

    public function down()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropColumn('justificativa_correcao');
        });
    }
}
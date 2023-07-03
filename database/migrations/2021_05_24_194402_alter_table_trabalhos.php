<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTrabalhos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->enum('status', ['rascunho', 'submetido', 'avaliado', 'corrigido', 'aprovado', 'reprovado', 'arquivado'])->default('rascunho') // Nome da coluna
                ->nullable() // Preenchimento não obrigatório
                ->after('avaliado'); // Ordenado após a coluna "password"
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAprovacaoColumnsToTrabalhosTable extends Migration
{
    public function up()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            if (!Schema::hasColumn('trabalhos', 'hash_codigo_aprovacao')) {
                $table->string('hash_codigo_aprovacao', 64)->nullable()->after('permite_correcao');
            }
            if (!Schema::hasColumn('trabalhos', 'aprovacao_emitida_em')) {
                $table->timestamp('aprovacao_emitida_em')->nullable()->after('hash_codigo_aprovacao');
            }
        });
    }

    public function down()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropColumn('hash_codigo_aprovacao');
            $table->dropColumn('aprovacao_emitida_em');
        });
    }
}
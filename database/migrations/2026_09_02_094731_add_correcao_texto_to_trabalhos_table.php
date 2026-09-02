<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            if (!Schema::hasColumn('trabalhos', 'data_correcao_submetida')) {
                $table->dateTime('data_correcao_submetida')->nullable()->after('avaliado');
            }
        });

        // Retroalimenta trabalhos antigos de texto que já foram submetidos/corrigidos
        DB::table('trabalhos')
            ->join('modalidades', 'trabalhos.modalidadeId', '=', 'modalidades.id')
            ->where('modalidades.texto', true)
            ->whereNull('trabalhos.data_correcao_submetida')
            ->where(function ($query) {
                $query->whereIn('trabalhos.avaliado', ['corrigido', 'corrigido_parcialmente', 'nao_corrigido'])
                      ->orWhere(function ($q) {
                          $q->where('trabalhos.permite_correcao', true)
                            ->whereColumn('trabalhos.updated_at', '>', 'trabalhos.created_at');
                      });
            })
            ->update([
                'data_correcao_submetida' => DB::raw('trabalhos.updated_at')
            ]);
    }

    public function down(): void
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            if (Schema::hasColumn('trabalhos', 'data_correcao_submetida')) {
                $table->dropColumn('data_correcao_submetida');
            }
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('nome_es')->nullable()->after('nome_en');
            $table->text  ('descricao_es')->nullable()->after('descricao_en');
            $table->string('fotoEvento_es')->nullable()->after('fotoEvento_en');
            $table->string('icone_es')->nullable()->after('icone_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'nome_es',
                'descricao_es',
                'fotoEvento_es',
                'icone_es',
            ]);
        });
    }
};

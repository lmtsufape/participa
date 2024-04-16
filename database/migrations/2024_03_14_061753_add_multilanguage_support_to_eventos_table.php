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
            $table->string('nome_en')->nullable()->after('nome');
            $table->text('descricao_en')->nullable()->after('descricao');
            $table->string('fotoEvento_en')->nullable()->after('fotoEvento');
            $table->string('icone_en')->nullable()->after('icone');
            $table->boolean('is_multilingual')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn(['nome_en', 'descricao_en', 'fotoEvento_en', 'icone_en', 'is_multilingual']);
        });
    }
};

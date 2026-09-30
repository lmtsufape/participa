<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->dateTime('inicio_versao_final')->nullable();
            $table->dateTime('fim_versao_final')->nullable();
        });
        Schema::create('versoes_finais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabalho_id')->constrained('trabalhos');
            $table->foreignId('user_id')->constrained('users');
            $table->string('caminho');
            $table->string('nome_original');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versoes_finais');
        Schema::table('modalidades', fn (Blueprint $table) => $table->dropColumn(['inicio_versao_final', 'fim_versao_final']));
    }
};

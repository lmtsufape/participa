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
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->string('hash_codigo_aprovacao', 64)->nullable()->unique()->after('aprovado');
            $table->timestamp('aprovacao_emitida_em')->nullable()->after('hash_codigo_aprovacao');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropUnique(['hash_aprovacao']);
            $table->dropColumn(['hash_aprovacao', 'aprovacao_emitida_em']);
        });
    }
};

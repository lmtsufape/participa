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
        Schema::table('areas', function (Blueprint $table) {
            $table->text('resumo')->nullable();
            $table->text('resumo_en')->nullable();
            $table->text('resumo_es')->nullable();
            $table->string('nome_en')->nullable();
            $table->string('nome_es')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('resumo');
            $table->dropColumn('resumo_en');
            $table->dropColumn('resumo_es');
            $table->dropColumn('nome_en');
            $table->dropColumn('nome_es');
        });
    }
};

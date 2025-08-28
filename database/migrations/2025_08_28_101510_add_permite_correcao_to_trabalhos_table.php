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
            $table->boolean('permite_correcao')->default(false)->after('aprovado');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropColumn('permite_correcao');
        });
    }
};

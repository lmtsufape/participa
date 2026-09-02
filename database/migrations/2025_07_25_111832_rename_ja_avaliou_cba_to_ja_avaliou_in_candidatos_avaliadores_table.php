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
        Schema::table('candidatos_avaliadores', function (Blueprint $table) {
            $table->renameColumn('ja_avaliou_cba', 'ja_avaliou');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidatos_avaliadores', function (Blueprint $table) {
            $table->renameColumn('ja_avaliou', 'ja_avaliou_cba');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atividades', fn (Blueprint $table) => $table->json('proponentes')->nullable());
    }

    public function down(): void
    {
        Schema::table('atividades', fn (Blueprint $table) => $table->dropColumn('proponentes'));
    }
};

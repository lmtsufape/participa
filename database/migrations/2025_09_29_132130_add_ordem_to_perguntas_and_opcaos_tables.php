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
        Schema::table('perguntas', function (Blueprint $table) {
            $table->unsignedInteger('ordem')->default(0)->after('id');
        });

        Schema::table('opcaos', function (Blueprint $table){
            $table->unsignedInteger('ordem')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('opcaos', function (Blueprint $table) {
            $table->dropColumn('ordem');
        });

        Schema::table('perguntas', function (Blueprint $table) {
            $table->dropColumn('ordem');
        });

    }
};

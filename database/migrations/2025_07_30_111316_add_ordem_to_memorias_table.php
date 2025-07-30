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
        Schema::table('memorias', function (Blueprint $table) {
            $table->integer('ordem')->default(0)->after('evento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memorias', function (Blueprint $table) {
            $table->dropColumn('ordem');
        });
    }
};

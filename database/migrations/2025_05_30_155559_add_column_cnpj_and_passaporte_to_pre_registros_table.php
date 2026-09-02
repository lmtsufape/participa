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
        Schema::table('pre_registros', function (Blueprint $table) {
            $table->string('cpf')->nullable()->change();
            $table->string('cnpj')->nullable();
            $table->string('passaporte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registros', function (Blueprint $table) {
            $table->string('cpf')->nullable(false)->change();
            $table->dropColumn('cnpj');
            $table->dropColumn('passaporte');
        });
    }
};

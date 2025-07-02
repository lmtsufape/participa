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
        Schema::table('perfil_identitarios', function (Blueprint $table) {
            $table->text('vinculoInstitucional')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_identitarios', function (Blueprint $table) {
            $table->string('vinculoInstitucional')->nullable()->change();
        });
    }
};

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
            DB::statement('ALTER TABLE perfil_identitarios ALTER COLUMN raca TYPE JSON USING to_jsonb(raca)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perfil_identitarios', function (Blueprint $table) {
            DB::statement('ALTER TABLE perfil_identitarios ALTER COLUMN raca TYPE VARCHAR(255)');
        });
    }
};

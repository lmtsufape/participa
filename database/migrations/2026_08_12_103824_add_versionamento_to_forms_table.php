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
        Schema::table('forms', function (Blueprint $table) {
            $table->foreignId('form_original_id')->nullable();

            $table->foreignId('form_anterior_id')->nullable();

            $table->unsignedInteger('versao')->default(1);

            $table->string('status', 20)->nullable();

            $table->timestamp('publicado_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropForeign(['form_original_id']);
            $table->dropForeign(['form_anterior_id']);

            $table->dropColumn([
                'form_original_id',
                'form_anterior_id',
                'versao',
                'status',
                'publicado_em',
            ]);
        });
    }
};

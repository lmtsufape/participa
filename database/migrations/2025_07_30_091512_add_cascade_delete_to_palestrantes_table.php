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
        Schema::table('palestrantes', function (Blueprint $table) {
            // Add cascade delete for palestra_id foreign key
            $table->dropForeign(['palestra_id']);
            $table->foreign('palestra_id')
                ->references('id')
                ->on('palestras')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('palestrantes', function (Blueprint $table) {
            $table->dropForeign(['palestra_id']);
            $table->foreign('palestra_id')
                ->references('id')
                ->on('palestras');
        });
    }
};

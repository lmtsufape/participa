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
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->decimal('valor')->change();
            $table->dropColumn('reference');
            $table->renameColumn('pagseguro_code', 'codigo');
            $table->renameColumn('pagseguro_status', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->integer('valor')->change();
            $table->string('reference');
            $table->renameColumn('codigo', 'pagseguro_code');
            $table->renameColumn('status', 'pagseguro_status');
        });
    }
};

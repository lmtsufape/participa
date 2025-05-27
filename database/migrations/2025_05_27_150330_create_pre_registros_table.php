<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pre_registros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf');
            $table->string('email');
            $table->string('codigo');
            $table->timestamp('expiracao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_registros');
    }
};

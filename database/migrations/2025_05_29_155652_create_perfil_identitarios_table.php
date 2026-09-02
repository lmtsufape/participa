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
        Schema::create('perfil_identitarios', function (Blueprint $table) {

            $table->id();
            $table->string('nomeSocial')->nullable();
            $table->string('genero')->nullable();
            $table->string('outroGenero')->nullable();
            $table->string('raca')->nullable();
            $table->string('outraRaca')->nullable();
            $table->boolean('comunidadeTradicional')->nullable();
            $table->string('nomeComunidadeTradicional')->nullable();
            $table->boolean('lgbtqia')->nullable();
            $table->boolean('deficienciaIdoso')->nullable();
            $table->json('necessidadesEspeciais')->nullable();
            $table->string('outraNecessidadeEspecial')->nullable();
            $table->string('vinculoInstitucional')->nullable();
            $table->boolean('participacaoOrganizacao')->nullable();
            $table->string('nomeOrganizacao')->nullable();
            $table->text('vinculoInstitucional')->nullable()->change();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_identitarios');
    }
};

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
        Schema::create('table_perfil_indentitarios', function (Blueprint $table) {

            $table->id();
            $table->string('nomeSocial')->nullable();
            $table->date('dataNascimento');
            $table->string('genero');
            $table->string('outroGenero')->nullable();
            $table->string('raca');
            $table->string('outraRaca')->nullable();
            $table->boolean('comunidadeTradicional');
            $table->string('nomeComunidadeTradicional')->nullable();
            $table->boolean('lgbtqia');
            $table->boolean('deficienciaIdoso');
            $table->json('necessidadesEspeciais')->nullable();
            $table->string('outraNecessidadeEspecial')->nullable();
            $table->boolean('associadoAba');
            $table->boolean('receberInfoAba');
            $table->string('vinculoInstitucional')->nullable();
            $table->boolean('participacaoOrganizacao');
            $table->string('nomeOrganizacao')->nullable();
            $table->foreignId('userId')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_perfil_indentarios');
    }
};

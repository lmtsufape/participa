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
            $table->date('dataNascimento');
            $table->string('genero');
            $table->string('outroGenero')->nullable();
            $table->json('raca');
            $table->string('outraRaca')->nullable();
            $table->boolean('comunidadeTradicional');
            $table->string('nomeComunidadeTradicional')->nullable();
            $table->boolean('lgbtqia');
            $table->boolean('deficienciaIdoso');
            $table->json('necessidadesEspeciais')->nullable();
            $table->string('outraNecessidadeEspecial')->nullable();
            $table->boolean('associadoCPFreire');
            $table->boolean('receberInfoCPFreire');
            $table->boolean('participacaoOrganizacao');
            $table->string('nomeOrganizacao')->nullable();
            $table->boolean('estudoPedagogiaFreiriana');
            $table->string('pedagogiasFreirianas')->nullable();
            $table->boolean('participarEstudoPedagogiaFreiriana');
            $table->foreignId('userId')->constrained('users');
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

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
        Schema::create('links_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('link')->nullable();
            $table->double('valor')->nullable();
            $table->dateTime('dataInicio')->nullable();
            $table->dateTime('dataFim')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categoria_participantes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_pagamentos');
    }
};

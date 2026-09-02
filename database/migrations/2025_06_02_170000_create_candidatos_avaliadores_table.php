<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('candidatos_avaliadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('evento_id')->constrained();
            $table->foreignId('area_id')->constrained();
            $table->string('link_lattes');
            $table->text('resumo_lattes');
            $table->boolean('ja_avaliou_cba');
            $table->string('disponibilidade_idiomas');
            $table->boolean('aprovado')->default(false);
            $table->boolean('em_analise')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidatos_avaliadores');
    }
};

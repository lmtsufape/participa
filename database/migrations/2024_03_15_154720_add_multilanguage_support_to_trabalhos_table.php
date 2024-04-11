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
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->string('titulo_en')->nullable();
            $table->text('resumo_en')->nullable();

            $table->string('campoextra1simples_en')->nullable();
            $table->text('campoextra1grande_en')->nullable();
            $table->string('campoextra2simples_en')->nullable();
            $table->text('campoextra2grande_en')->nullable();
            $table->string('campoextra3simples_en')->nullable();
            $table->text('campoextra3grande_en')->nullable();
            $table->string('campoextra4simples_en')->nullable();
            $table->text('campoextra4grande_en')->nullable();
            $table->string('campoextra5simples_en')->nullable();
            $table->text('campoextra5grande_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropColumn(['titulo_en', 'resumo_en', 'campoextra1simples_en','campoextra1grande_en', 'campoextra2simples_en'
            , 'campoextra2grande_en', 'campoextra3simples_en', 'campoextra3grande_en', 'campoextra4simples_en', 'campoextra4grande_en',
                'campoextra5simples_en','campoextra5grande_en']);
        });
    }
};

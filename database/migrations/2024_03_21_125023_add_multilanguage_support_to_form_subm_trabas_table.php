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
        Schema::table('form_subm_trabas', function (Blueprint $table) {
            $table->string('etiquetatitulotrabalho_en')->nullable();
            $table->string('etiquetaresumotrabalho_en')->nullable();
            $table->string('etiquetacampoextra1_en')->nullable();
            $table->string('etiquetacampoextra2_en')->nullable();
            $table->string('etiquetacampoextra3_en')->nullable();
            $table->string('etiquetacampoextra4_en')->nullable();
            $table->string('etiquetacampoextra5_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_subm_trabas', function (Blueprint $table) {
            $table->dropColumn(['etiquetatitulotrabalho_en', 'etiquetaresumotrabalho_en','etiquetacampoextra1_en'
                ,'etiquetacampoextra2_en','etiquetacampoextra3_en','etiquetacampoextra4_en','etiquetacampoextra5_en']);
        });
    }
};

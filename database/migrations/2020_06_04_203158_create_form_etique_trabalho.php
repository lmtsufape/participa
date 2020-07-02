<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormEtiqueTrabalho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Formulário para edição de etiquetas do da tela de submissão de trabalhos;
        Schema::create('form_subm_trabas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('etiquetatitulotrabalho')->nullable();
            $table->string('etiquetaautortrabalho')->nullable();
            $table->string('etiquetacoautortrabalho')->nullable();
            $table->string('etiquetaresumotrabalho')->nullable();
            $table->string('etiquetaareatrabalho')->nullable();
            $table->string('etiquetauploadtrabalho')->nullable();

            $table->integer('indicedecampos')->nullable();
            
            // Etiquetas de campos extras
            $table->string('etiquetacampoextra1')->nullable();
            $table->string('etiquetacampoextra2')->nullable();
            $table->string('etiquetacampoextra3')->nullable();
            $table->string('etiquetacampoextra4')->nullable();
            $table->string('etiquetacampoextra5')->nullable();

            // Tipo dos campos extras
            $table->string('tipocampoextra1')->nullable();
            $table->string('tipocampoextra2')->nullable();
            $table->string('tipocampoextra3')->nullable();
            $table->string('tipocampoextra4')->nullable();
            $table->string('tipocampoextra5')->nullable();
            
            // Checkboxes para exibição ou não dos campos extras 
            $table->boolean('checkcampoextra1')->nullable();
            $table->boolean('checkcampoextra2')->nullable();
            $table->boolean('checkcampoextra3')->nullable();
            $table->boolean('checkcampoextra4')->nullable();
            $table->boolean('checkcampoextra5')->nullable();

            $table->bigInteger("eventoId")->nullable();
            $table->foreign("eventoId")->references("id")->on("eventos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_etique_trabalhos');
    }
}

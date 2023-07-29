<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
            $table->string('etiquetatitulotrabalho')->default('Titulo');
            $table->string('etiquetaautortrabalho')->default('Autor');
            $table->string('etiquetacoautortrabalho')->default('Co-Autor');
            $table->string('etiquetaresumotrabalho')->default('Resumo');
            $table->string('etiquetaareatrabalho')->default('Área');
            $table->string('etiquetauploadtrabalho')->default('Upload de Trabalho');

            // Etiquetas de campos extras
            $table->string('etiquetacampoextra1')->default('Campo Extra');
            $table->string('etiquetacampoextra2')->default('Campo Extra');
            $table->string('etiquetacampoextra3')->default('Campo Extra');
            $table->string('etiquetacampoextra4')->default('Campo Extra');
            $table->string('etiquetacampoextra5')->default('Campo Extra');

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

            $table->text('ordemCampos');

            $table->unsignedBigInteger('eventoId')->nullable();
            $table->foreign('eventoId')->references('id')->on('eventos');
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

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
            $table->string('etiquetabaixarregra')->nullable();
            $table->string('etiquetabaixartemplate')->nullable();
            $table->string('campoextra1')->nullable();
            $table->string('campoextra2')->nullable();
            $table->string('campoextra3')->nullable();
            $table->string('campoextra4')->nullable();
            $table->string('campoextra5')->nullable();
            $table->string('campoextra6')->nullable();
            $table->string('campoextra7')->nullable();
            $table->string('campoextra8')->nullable();
            $table->string('campoextra9')->nullable();
            $table->string('campoextra10')->nullable();
            $table->string('campoextra11')->nullable();
            $table->string('campoextra12')->nullable();
            $table->string('campoextra13')->nullable();

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

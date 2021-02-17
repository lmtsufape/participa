<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormEtiqueEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Formulário para edição de etiquetas do card de eventos.
        Schema::create('form_eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('etiquetanomeevento')->default('Nome');
            $table->string('etiquetatipoevento')->default('Tipo');
            $table->string('etiquetadescricaoevento')->default('Descrição');
            $table->string('etiquetadatas')->default('Realização');
            $table->string('etiquetasubmissoes')->default('Submissões');
            $table->string('etiquetaenderecoevento')->default('Endereço');
            $table->string('etiquetamoduloinscricao')->default('Submissões');
            $table->string('etiquetamoduloprogramacao')->default('Inscrições');
            $table->string('etiquetamoduloorganizacao')->default('Organização');
            $table->string('etiquetabaixarregra')->default('Regras');
            $table->string('etiquetabaixartemplate')->default('Template');

            $table->boolean('modinscricao')->nullable();
            $table->boolean('modprogramacao')->nullable();
            $table->boolean('modorganizacao')->nullable();
            $table->boolean('modsubmissao')->nullable();

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
        Schema::dropIfExists('formeventos');
    }
}

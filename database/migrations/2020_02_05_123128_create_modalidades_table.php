<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modalidades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('nome');
            $table->date('inicioSubmissao')->nullable();
            $table->date('fimSubmissao')->nullable();
            $table->date('inicioRevisao')->nullable();
            $table->date('fimRevisao')->nullable();
            $table->date('inicioResultado')->nullable();

            $table->boolean('texto')->nullable();
            $table->boolean('arquivo')->nullable();
            
            $table->boolean('caracteres')->nullable();
            $table->bigInteger('mincaracteres')->nullable();
            $table->bigInteger('maxcaracteres')->nullable();

            $table->boolean('palavras')->nullable();
            $table->bigInteger('minpalavras')->nullable();
            $table->bigInteger('maxpalavras')->nullable();
            
            $table->boolean('pdf')->nullable();
            $table->boolean('jpg')->nullable();
            $table->boolean('jpeg')->nullable();
            $table->boolean('png')->nullable();
            $table->boolean('docx')->nullable();
            $table->boolean('odt')->nullable();

            $table->string('regra')->nullable();
            $table->string('template')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modalidades');
    }
}

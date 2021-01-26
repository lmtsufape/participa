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
            $table->dateTimeTz('inicioSubmissao')->nullable();
            $table->dateTimeTz('fimSubmissao')->nullable();
            $table->dateTimeTz('inicioRevisao')->nullable();
            $table->dateTimeTz('fimRevisao')->nullable();
            $table->dateTimeTz('inicioResultado')->nullable();

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
            $table->boolean('zip')->nullable();
            $table->boolean('svg')->nullable();

            $table->string('regra')->nullable();
            $table->string('template')->nullable();
            $table->bigInteger('evento_id')->nullable();
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

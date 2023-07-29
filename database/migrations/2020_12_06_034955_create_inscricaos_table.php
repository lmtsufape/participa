<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('finalizada');
            $table->timestamps();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('evento_id')->nullable();
            $table->foreign('evento_id')->references('id')->on('eventos');

            $table->unsignedBigInteger('categoria_participante_id');
            $table->foreign('categoria_participante_id')->references('id')->on('categoria_participantes');

            $table->unsignedBigInteger('pagamento_id')->nullable();
            $table->foreign('pagamento_id')->references('id')->on('pagamentos');

            $table->unsignedBigInteger('promocao_id')->nullable();
            $table->foreign('promocao_id')->references('id')->on('promocaos');

            $table->unsignedBigInteger('cupom_desconto_id')->nullable();
            $table->foreign('cupom_desconto_id')->references('id')->on('cupom_de_descontos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscricaos');
    }
}

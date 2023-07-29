<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCertificadoUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificado_user', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('validacao');
            $table->boolean('valido')->default(true);

            $table->unsignedBigInteger('certificado_id');
            $table->foreign('certificado_id')->references('id')->on('certificados');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('trabalho_id')->nullable();
            $table->foreign('trabalho_id')->references('id')->on('trabalhos');

            $table->unsignedBigInteger('palestra_id')->nullable();
            $table->foreign('palestra_id')->references('id')->on('palestras');

            $table->unsignedBigInteger('comissao_id')->nullable();
            $table->foreign('comissao_id')->references('id')->on('tipo_comissaos');

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
        Schema::dropIfExists('certificado_user');
    }
}

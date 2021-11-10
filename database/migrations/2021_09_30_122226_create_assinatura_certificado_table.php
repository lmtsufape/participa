<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssinaturaCertificadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assinatura_certificado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assinatura_id');
            $table->foreign('assinatura_id')->references('id')->on('assinaturas');
            $table->bigInteger('certificado_id');
            $table->foreign('certificado_id')->references('id')->on('certificados');
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
        Schema::dropIfExists('assinatura_certificado');
    }
}

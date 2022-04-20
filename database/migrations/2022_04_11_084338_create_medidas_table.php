<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->double('x', 8, 2);
            $table->double('y', 8, 2);
            $table->double('largura', 8, 2);
            $table->double('altura', 8, 2)->nullable();
            $table->integer('fontSize')->nullable();
            $table->integer('tipo');

            $table->unsignedBigInteger('certificado_id')->nullable();
            $table->foreign('certificado_id')->references('id')->on('certificados');

            $table->unsignedBigInteger('assinatura_id')->nullable();
            $table->foreign('assinatura_id')->references('id')->on('assinaturas');

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
        Schema::dropIfExists('medidas');
    }
}

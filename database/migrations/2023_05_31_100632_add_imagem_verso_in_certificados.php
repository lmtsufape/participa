<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagemVersoInCertificados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->boolean('has_imagem_verso')->default(false);
            $table->string('imagem_verso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificados', function (Blueprint $table) {
            $table->dropColumn(['has_imagem_verso', 'imagem_verso']);
        });
    }
};

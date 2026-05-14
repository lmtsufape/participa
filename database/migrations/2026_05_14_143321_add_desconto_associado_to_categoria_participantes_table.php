<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('categoria_participantes', function (Blueprint $table) {
            $table->float('porcentagem_desconto_associado')->nullable();
        });
    }

    public function down()
    {
        Schema::table('categoria_participantes', function (Blueprint $table) {
            $table->dropColumn('porcentagem_desconto_associado');
        });
    }
};

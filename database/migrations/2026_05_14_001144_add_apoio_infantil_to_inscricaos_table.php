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
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->boolean('apoio_infantil')->default(false);
        });
    }

    public function down()
    {
        Schema::table('inscricaos', function (Blueprint $table) {
            $table->dropColumn('apoio_infantil');
        });
    }
};

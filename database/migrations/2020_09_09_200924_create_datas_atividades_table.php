<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasAtividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datas_atividades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->unsignedBigInteger('atividade_id')->refereces('id')->on('atividades')->onDelete('cascade');
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
        Schema::dropIfExists('datas_atividades');
    }
}

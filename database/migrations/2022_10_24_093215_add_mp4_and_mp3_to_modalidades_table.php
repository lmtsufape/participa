<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMp4AndMp3ToModalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->boolean('mp4')->after('svg')->nullable();
            $table->boolean('mp3')->after('mp4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->dropColumn('mp4');
            $table->dropColumn('mp3');
        });
    }
}

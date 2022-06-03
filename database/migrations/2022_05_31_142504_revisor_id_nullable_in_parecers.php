<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RevisorIdNullableInParecers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parecers', function (Blueprint $table) {
            $table->integer('revisorId')->nullable()->change();
            $table->boolean('parecer_final')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parecers', function (Blueprint $table) {
            $table->dropColumn('parecer_final');
        });
    }
}

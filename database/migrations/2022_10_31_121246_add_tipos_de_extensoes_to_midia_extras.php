<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTiposDeExtensoesToMidiaExtras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('midia_extras', function (Blueprint $table) {
            $table->boolean('ogg')->default(false);
            $table->boolean('wav')->default(false);
            $table->boolean('ogv')->default(false);
            $table->boolean('mpg')->default(false);
            $table->boolean('mpeg')->default(false);
            $table->boolean('mkv')->default(false);
            $table->boolean('avi')->default(false);
            $table->boolean('odp')->default(false);
            $table->boolean('pptx')->default(false);
            $table->boolean('csv')->default(false);
            $table->boolean('ods')->default(false);
            $table->boolean('xlsx')->default(false); //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('midia_extras', function (Blueprint $table) {
            $table->dropColumn(
                ['ogg', 'wav', 'ogv', 'mpg', 'mpeg', 'mkv', 'avi', 'odp', 'pptx', 'csv', 'ods', 'xlsx']
            );
        });
    }
}

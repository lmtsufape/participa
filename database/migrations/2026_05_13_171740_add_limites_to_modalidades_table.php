<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->boolean('exclusividade_autoria')->default(false); 
            $table->integer('numMaxTrabalhos')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->dropColumn(['exclusividade_autoria', 'numMaxTrabalhos']);
        });
    }
};

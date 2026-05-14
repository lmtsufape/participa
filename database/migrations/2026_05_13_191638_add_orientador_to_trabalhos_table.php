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
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->foreignId('orientador_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('trabalhos', function (Blueprint $table) {
            $table->dropForeign(['orientador_id']);
            $table->dropColumn('orientador_id');
        });
}
};

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
        Schema::rename('table_perfil_indentitarios', 'perfil_identitarios');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::rename('perfil_identitarios', 'table_perfil_indentitarios');
    }
};

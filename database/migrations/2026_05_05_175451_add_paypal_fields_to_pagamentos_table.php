<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->string('gateway')->default('mercadopago')->after('id'); // 'mercadopago' or 'paypal'
            $table->string('paypal_order_id')->nullable()->after('codigo');
            $table->string('paypal_payer_id')->nullable()->after('paypal_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagamentos', function (Blueprint $table) {
            $table->dropColumn(['gateway', 'paypal_order_id', 'paypal_payer_id']);
        });
    }
};
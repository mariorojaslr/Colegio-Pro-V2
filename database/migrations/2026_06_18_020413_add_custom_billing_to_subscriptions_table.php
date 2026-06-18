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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->decimal('custom_price', 10, 2)->nullable()->after('status')->comment('Precio fijo personalizado para sobreescribir el plan');
            $table->integer('discount_percent')->nullable()->after('custom_price')->comment('Porcentaje de bonificación (0 a 100)');
            $table->timestamp('discount_expires_at')->nullable()->after('discount_percent')->comment('Fecha de expiración de la bonificación o precio especial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['custom_price', 'discount_percent', 'discount_expires_at']);
        });
    }
};

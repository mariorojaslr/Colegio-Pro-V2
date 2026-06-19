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
        Schema::table('schools', function (Blueprint $table) {
            $table->integer('billing_day')->nullable()->after('is_active');
            $table->boolean('auto_billing_enabled')->default(false)->after('billing_day');
            
            // Mercado Pago (or generic payment gateway credentials)
            $table->string('mp_access_token')->nullable()->after('auto_billing_enabled');
            $table->string('mp_public_key')->nullable()->after('mp_access_token');
            $table->boolean('mp_sandbox_mode')->default(true)->after('mp_public_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'billing_day',
                'auto_billing_enabled',
                'mp_access_token',
                'mp_public_key',
                'mp_sandbox_mode'
            ]);
        });
    }
};

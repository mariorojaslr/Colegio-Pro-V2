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
            // Localización y Moneda
            $table->string('currency_code', 3)->default('ARS'); // ARS, MXN, EUR, USD
            $table->string('currency_symbol', 10)->default('$'); // $, MEX$, €
            
            // Parametrización de Módulos
            $table->boolean('has_academy')->default(true); // Permite activar o desactivar la cartelera académica
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['currency_code', 'currency_symbol', 'has_academy']);
        });
    }
};

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
        Schema::table('compliance_requirements', function (Blueprint $table) {
            // Frecuencia de caducidad obligatoria para el control de habilitación
            $table->string('expiry_frequency')->default('none'); // none, semester, year, fixed
            $table->date('expiry_fixed_date')->nullable(); // Si la fecha es específica e inamovible
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compliance_requirements', function (Blueprint $table) {
            $table->dropColumn(['expiry_frequency', 'expiry_fixed_date']);
        });
    }
};

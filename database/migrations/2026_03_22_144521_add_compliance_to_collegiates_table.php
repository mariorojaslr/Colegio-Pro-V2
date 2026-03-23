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
        Schema::table('collegiates', function (Blueprint $table) {
            // Cumplimiento de Ética
            $table->boolean('is_ethics_compliant')->default(true);
            $table->date('ethics_expiry')->nullable(); // Para certificados de ética con vencimiento
            
            // Estado de Cuotas Societarias
            $table->boolean('is_fees_compliant')->default(false);
            $table->date('fees_expiry')->nullable(); // Hasta qué fecha está al día
            
            // Certificación General
            $table->boolean('is_fully_documented')->default(false); // Cache de si cumplió toda la tabla documental
            
            // Notas del administrador
            $table->text('compliance_notes')->nullable();
            
            // Metadatos para campos especiales según el tipo de colegio
            $table->json('custom_attributes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            //
        });
    }
};

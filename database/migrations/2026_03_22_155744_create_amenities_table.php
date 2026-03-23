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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); // Ejemplo: "Piscina Natatorio", "Salón Auditorio"
            $table->text('description')->nullable();
            $table->string('icon')->default('bi-building'); // Icono bi-xxxx
            
            $table->boolean('is_active')->default(true); // Switch de activación opcional
            
            // Tarifarios
            $table->decimal('base_price', 12, 2)->default(0);
            $table->boolean('is_seasonal')->default(false); // ¿Tiene precio por temporada?
            $table->decimal('seasonal_price', 12, 2)->nullable();
            $table->string('season_range')->nullable(); // Guardamos el rango (marzo-septiembre, etc.)
            
            $table->boolean('has_calendar')->default(true); // Si permite reservas por fecha/hora
            $table->integer('capacity')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};

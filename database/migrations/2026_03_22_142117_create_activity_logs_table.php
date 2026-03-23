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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Quién realizó la acción
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // En qué institución ocurrió (null si es acción global del OWNER)
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            
            // Tipo de acción: 'login', 'create', 'update', 'delete', 'impersonate'
            $table->string('action');
            
            // Descripción legible para el OWNER
            $table->text('description');
            
            // Relación polimórfica opcional con el objeto afectado (Colegio, Plan, Alumno)
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            
            // Metadatos técnicos
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable(); // Para guardar qué cambió exactamente
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            
            // Institución dueña del contenido
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            
            // Datos del contenido
            $table->string('title');
            $table->text('description')->nullable();
            
            // Integración con Bunny.net (Video ID / Library ID si fuera necesario)
            $table->string('bunny_video_id')->nullable();
            $table->string('bunny_collection_id')->nullable();
            
            // Estado y Tipo
            $table->boolean('is_published')->default(true);
            $table->boolean('is_live')->default(false); // Para distinguir clases en vivo de grabadas
            $table->string('live_url')->nullable(); // Si se usa una fuente externa (Zoom/Meet/YouTube)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};

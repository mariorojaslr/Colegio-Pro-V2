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
        Schema::create('help_sections', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique(); // La página/ruta a la que pertenece
            $table->string('title');                // Título descriptivo (ej: Manual de Padrón)
            $table->text('content');                // El texto del manual en español
            $table->string('video_url')->nullable(); // Link opcional a un video tutorial
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_sections');
    }
};

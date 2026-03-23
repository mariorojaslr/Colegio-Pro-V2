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
        Schema::create('collegiate_documents', function (Blueprint $table) {
            $table->id();
            
            // Relaciones clave
            $table->foreignId('collegiate_id')->constrained()->onDelete('cascade');
            $table->foreignId('compliance_requirement_id')->constrained()->onDelete('cascade');
            
            // Datos del archivo
            $table->string('file_path')->nullable();
            
            // Ciclo de vida
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->date('expiry_date')->nullable();
            
            $table->text('admin_notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collegiate_documents');
    }
};

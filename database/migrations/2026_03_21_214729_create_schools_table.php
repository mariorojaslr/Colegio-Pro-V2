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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            
            // Branding por empresa
            $table->string('primary_color')->default('#0F172A');
            $table->string('secondary_color')->default('#EAB308');
            $table->string('tertiary_color')->default('#1E293B');
            
            // Métricas de uso (SaaS)
            $table->unsignedBigInteger('storage_used')->default(0);
            $table->unsignedBigInteger('traffic_used')->default(0);
            $table->unsignedInteger('user_count')->default(0);
            $table->string('plan_category')->default('initial'); // base, professional, enterprise
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};

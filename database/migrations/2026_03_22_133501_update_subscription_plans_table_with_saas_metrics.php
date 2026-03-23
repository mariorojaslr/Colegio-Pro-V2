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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('max_traffic')->default(0); // GB
            $table->unsignedInteger('max_files')->default(0);
            $table->unsignedInteger('max_images')->default(0);
            $table->unsignedInteger('max_streaming')->default(0); // minutos
            $table->boolean('is_one_time')->default(false); // para cargos únicos vs recurrentes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['max_traffic', 'max_files', 'max_images', 'max_streaming', 'is_one_time']);
        });
    }
};

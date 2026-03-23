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
            $table->boolean('is_ia_enabled')->default(false)->after('features');
            $table->boolean('is_padron_enabled')->default(false)->after('is_ia_enabled');
            $table->boolean('is_clubs_enabled')->default(false)->after('is_padron_enabled');
            $table->boolean('is_help_enabled')->default(false)->after('is_clubs_enabled');
            $table->boolean('is_massive_import_enabled')->default(false)->after('is_help_enabled');
            $table->boolean('is_analytics_enabled')->default(false)->after('is_massive_import_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn([
                'is_ia_enabled',
                'is_padron_enabled',
                'is_clubs_enabled',
                'is_help_enabled',
                'is_massive_import_enabled',
                'is_analytics_enabled'
            ]);
        });
    }
};

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
            $table->decimal('historical_debt', 12, 2)->default(0)->after('billing_profile');
            $table->integer('historical_debt_months')->default(0)->after('historical_debt');
            $table->text('observations')->nullable()->after('historical_debt_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            $table->dropColumn(['historical_debt', 'historical_debt_months', 'observations']);
        });
    }
};

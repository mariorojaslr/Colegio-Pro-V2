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
        Schema::table('compliance_requirements', function (Blueprint $table) {
            // Null means permanent, otherwise integer months (6, 12, 24, etc.)
            $table->integer('expiration_months')->nullable()->after('is_mandatory');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compliance_requirements', function (Blueprint $table) {
            $table->dropColumn('expiration_months');
        });
    }
};

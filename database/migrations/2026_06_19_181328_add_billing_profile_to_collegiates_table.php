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
            $table->string('billing_profile')->default('mensual')->after('status')->comment('mensual, anual, exento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            $table->dropColumn('billing_profile');
        });
    }
};

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
            $table->boolean('is_exempt_from_fees')->default(false)->after('status')->comment('Si es true, no se le generarán cuotas sociales automáticamente (ej. directivos)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            $table->dropColumn('is_exempt_from_fees');
        });
    }
};

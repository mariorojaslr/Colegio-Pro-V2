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
        Schema::table('collegiate_dues', function (Blueprint $table) {
            $table->string('concept')->nullable()->after('amount')->comment('Ej: Pago Anual 2026 (1/2)');
            $table->string('due_type')->default('monthly')->after('concept')->comment('monthly, extraordinary, enrollment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiate_dues', function (Blueprint $table) {
            $table->dropColumn(['concept', 'due_type']);
        });
    }
};

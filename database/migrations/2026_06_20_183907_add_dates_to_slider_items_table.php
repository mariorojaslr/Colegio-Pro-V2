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
        Schema::table('slider_items', function (Blueprint $table) {
            $table->dateTime('starts_at')->nullable()->after('order');
            $table->dateTime('ends_at')->nullable()->after('starts_at');
        });

        // Set default values for existing items
        DB::table('slider_items')->update([
            'starts_at' => now(),
            'ends_at' => now()->addYears(50)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slider_items', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'ends_at']);
        });
    }
};

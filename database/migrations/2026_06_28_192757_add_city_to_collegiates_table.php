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
            $table->string('city')->default('Capital')->nullable()->after('address');
        });

        // Set default city for all existing collegiates
        \Illuminate\Support\Facades\DB::table('collegiates')->update(['city' => 'Capital']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};

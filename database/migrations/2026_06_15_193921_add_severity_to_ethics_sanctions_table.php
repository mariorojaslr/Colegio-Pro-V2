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
        Schema::table('ethics_sanctions', function (Blueprint $table) {
            $table->string('severity')->default('media')->after('type'); // grave, media, leve
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ethics_sanctions', function (Blueprint $table) {
            $table->dropColumn('severity');
        });
    }
};

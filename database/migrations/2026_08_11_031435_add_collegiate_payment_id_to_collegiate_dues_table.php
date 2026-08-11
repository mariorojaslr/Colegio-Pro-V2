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
            $table->foreignId('collegiate_payment_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiate_dues', function (Blueprint $table) {
            $table->dropForeign(['collegiate_payment_id']);
            $table->dropColumn('collegiate_payment_id');
        });
    }
};

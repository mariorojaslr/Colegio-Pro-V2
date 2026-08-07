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
            $table->foreignId('billing_concept_id')->nullable()->constrained('billing_concepts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiate_dues', function (Blueprint $table) {
            $table->dropForeign(['billing_concept_id']);
            $table->dropColumn('billing_concept_id');
        });
    }
};

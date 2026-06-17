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
        Schema::table('board_members', function (Blueprint $table) {
            $table->foreignId('collegiate_id')->nullable()->after('school_id')->constrained()->nullOnDelete();
            // Hacer name y image_path nullables para mantener los datos legacy,
            // pero el nuevo sistema usará collegiate_id
            $table->string('name')->nullable()->change();
            $table->string('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_members', function (Blueprint $table) {
            $table->dropForeign(['collegiate_id']);
            $table->dropColumn('collegiate_id');
            $table->string('name')->nullable(false)->change();
            $table->string('image_path')->nullable(false)->change();
        });
    }
};

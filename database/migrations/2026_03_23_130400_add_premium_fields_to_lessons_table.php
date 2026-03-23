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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('category')->nullable()->after('school_id');
            $table->string('thumbnail_url')->nullable()->after('description');
            $table->decimal('price', 10, 2)->default(0)->after('thumbnail_url');
            $table->string('lecturer')->nullable()->after('price');
            $table->string('duration')->nullable()->after('lecturer');
            $table->string('start_date')->nullable()->after('duration');
            $table->text('benefit')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['category', 'thumbnail_url', 'price', 'lecturer', 'duration', 'start_date', 'benefit']);
        });
    }
};

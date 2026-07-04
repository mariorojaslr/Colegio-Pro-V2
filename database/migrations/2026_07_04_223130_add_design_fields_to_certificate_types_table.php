<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificate_types', function (Blueprint $table) {
            $table->string('background_path')->nullable()->after('template_content');
            $table->string('page_size', 20)->default('a4')->after('background_path');
            $table->string('page_orientation', 20)->default('landscape')->after('page_size');
            $table->text('design_settings')->nullable()->after('page_orientation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificate_types', function (Blueprint $table) {
            $table->dropColumn(['background_path', 'page_size', 'page_orientation', 'design_settings']);
        });
    }
};

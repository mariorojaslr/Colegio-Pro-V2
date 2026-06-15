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
        Schema::table('certificate_types', function (Blueprint $table) {
            $table->boolean('is_single_use')->default(false)->after('validity_days');
        });

        Schema::table('professional_certificates', function (Blueprint $table) {
            $table->timestamp('used_at')->nullable()->after('status');
            $table->string('used_for_expedient')->nullable()->after('used_at');
            $table->text('used_by_info')->nullable()->after('used_for_expedient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            //
        });
    }
};

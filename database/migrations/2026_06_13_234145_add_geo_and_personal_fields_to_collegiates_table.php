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
            $table->date('birth_date')->nullable()->after('dni');
            $table->string('address')->nullable()->after('phone');
            $table->string('plus_code')->nullable()->after('address');
            $table->decimal('latitude', 10, 8)->nullable()->after('plus_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('degree')->nullable()->after('longitude');
            $table->text('workplaces_info')->nullable()->after('degree');
            $table->integer('practicing_since_year')->nullable()->after('workplaces_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiates', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'address',
                'plus_code',
                'latitude',
                'longitude',
                'degree',
                'workplaces_info',
                'practicing_since_year'
            ]);
        });
    }
};

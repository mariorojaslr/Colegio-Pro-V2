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
        Schema::table('payment_records', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->nullable()->after('id');
            $table->string('currency')->default('CLP')->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'currency']);
        });
    }
};

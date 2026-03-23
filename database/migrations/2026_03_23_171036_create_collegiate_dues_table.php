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
        Schema::create('collegiate_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collegiate_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('due_date'); // Fecha de vencimiento (ej: 10/MM/YYYY)
            $table->string('status')->default('pending'); // pending, paid, overdue, defaulted
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collegiate_dues');
    }
};

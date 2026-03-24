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
        Schema::create('payment_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('collegiate_id')->constrained()->onDelete('cascade');
            $table->string('type'); // yearly_promo, installment_plan, custom
            $table->decimal('total_amount_original', 12, 2);
            $table->decimal('total_amount_agreement', 12, 2);
            $table->integer('installment_count');
            $table->string('status')->default('active'); // active, completed, void
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_agreements');
    }
};

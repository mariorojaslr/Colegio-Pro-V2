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
        Schema::create('ethics_sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collegiate_id')->constrained()->onDelete('cascade');
            $table->string('type'); // temporary, permanent
            $table->text('reason'); // Motivo resumido
            $table->text('arguments')->nullable(); // Argumentación detallada
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('active'); // active, lifted, expired
            $table->timestamp('lifted_at')->nullable();
            $table->text('lifted_reason')->nullable();
            $table->foreignId('lifted_by')->nullable()->constrained('users');
            $table->boolean('approved_by_president')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethics_sanctions');
    }
};

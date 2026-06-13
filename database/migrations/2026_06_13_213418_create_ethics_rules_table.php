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
        Schema::create('ethics_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g. "Falta Grave: Retraso en pagos"
            $table->text('description')->nullable();
            $table->string('penalty_type')->default('suspension'); // suspension, amonestacion, expulsion
            $table->integer('penalty_days')->nullable(); // null means permanent or indeterminate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ethics_rules');
    }
};

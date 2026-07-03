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
        // 1. Agregar ip_address a chatbot_knowledge
        if (Schema::hasTable('chatbot_knowledge')) {
            Schema::table('chatbot_knowledge', function (Blueprint $table) {
                if (!Schema::hasColumn('chatbot_knowledge', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('status');
                }
            });
        }

        // 2. Crear tabla banned_ips
        if (!Schema::hasTable('banned_ips')) {
            Schema::create('banned_ips', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 45)->unique();
                $table->string('reason')->nullable();
                $table->unsignedBigInteger('school_id')->nullable();
                $table->timestamps();

                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('banned_ips')) {
            Schema::dropIfExists('banned_ips');
        }

        if (Schema::hasTable('chatbot_knowledge')) {
            Schema::table('chatbot_knowledge', function (Blueprint $table) {
                if (Schema::hasColumn('chatbot_knowledge', 'ip_address')) {
                    $table->dropColumn('ip_address');
                }
            });
        }
    }
};

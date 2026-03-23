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
        Schema::table('collegiate_documents', function (Blueprint $table) {
            // Renombrar para mayor claridad con Bunny.net
            $table->renameColumn('file_path', 'file_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegiate_documents', function (Blueprint $table) {
            $table->renameColumn('file_url', 'file_path');
        });
    }
};

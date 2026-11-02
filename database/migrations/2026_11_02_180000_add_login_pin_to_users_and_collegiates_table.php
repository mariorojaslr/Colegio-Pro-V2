<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las modificaciones en la base de datos (Estrictamente Aditivas).
     * Incorpora el código de validación PIN de 4 dígitos para agilizar
     * el acceso de colegiados por legajo o teléfono.
     */
    public function up(): void
    {
        // 1. Agregar columna login_pin a la tabla users si no existe
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'login_pin')) {
            Schema::table('users', function (Blueprint $table) {
                // Almacena el PIN de 4 dígitos (encriptado o hasheado)
                $table->string('login_pin', 255)->nullable()->after('password')->comment('Código PIN de 4 dígitos para acceso rápido');
            });
        }

        // 2. Agregar columna login_pin a la tabla collegiates si no existe
        if (Schema::hasTable('collegiates') && !Schema::hasColumn('collegiates', 'login_pin')) {
            Schema::table('collegiates', function (Blueprint $table) {
                // Almacena el PIN o indicador de PIN para búsquedas rápidas en el padrón
                $table->string('login_pin', 255)->nullable()->after('phone')->comment('Copia del PIN de 4 dígitos para validación rápida');
            });
        }
    }

    /**
     * Revierte las modificaciones en caso de ser necesario.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'login_pin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('login_pin');
            });
        }

        if (Schema::hasTable('collegiates') && Schema::hasColumn('collegiates', 'login_pin')) {
            Schema::table('collegiates', function (Blueprint $table) {
                $table->dropColumn('login_pin');
            });
        }
    }
};

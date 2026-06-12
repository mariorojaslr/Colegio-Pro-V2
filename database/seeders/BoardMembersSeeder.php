<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BoardMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = \App\Models\School::where('slug', 'cotolar')->first();
        if (!$school) return;

        // Limpiar para evitar duplicados en desarrollo
        \App\Models\BoardMember::where('school_id', $school->id)->delete();

        $members = [
            // COMISIÓN DIRECTIVA
            ['dep' => 'Comisión Directiva', 'role' => 'Presidente', 'name' => 'Lic. Silvia Arce', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => 'Vicepresidente', 'name' => 'Lic. Gabriela Avila', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => 'Secretaria', 'name' => 'Lic. Soledad Aguero', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => 'Tesorera', 'name' => 'Lic. M. Luz García Pereyra', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => '1er Vocal', 'name' => 'Lic. Aldana Rojas Cabrera', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => '2do Vocal', 'name' => 'Lic. Marcela Beatriz Mercado', 'sub' => false],
            ['dep' => 'Comisión Directiva', 'role' => 'Suplente', 'name' => 'Lic. M. Esther Rodriguez', 'sub' => true],

            // TRIBUNAL DE ÉTICA Y DISCIPLINA
            ['dep' => 'Tribunal de Ética y Disciplina', 'role' => 'Presidente', 'name' => 'Lic. Pablo Barrionuevo', 'sub' => false],
            ['dep' => 'Tribunal de Ética y Disciplina', 'role' => '1er Vocal', 'name' => 'Lic. Margarita Álvarez', 'sub' => false],
            ['dep' => 'Tribunal de Ética y Disciplina', 'role' => '2do Vocal', 'name' => 'Lic. Karen Villaroel', 'sub' => false],
            ['dep' => 'Tribunal de Ética y Disciplina', 'role' => '1er Vocal Suplente', 'name' => 'Lic. María Eva Gonzalez', 'sub' => true],
            ['dep' => 'Tribunal de Ética y Disciplina', 'role' => '2do Vocal Suplente', 'name' => 'Lic. Erica Corzo', 'sub' => true],

            // RENDICIÓN DE CUENTAS
            ['dep' => 'Rendición de Cuentas', 'role' => 'Titular', 'name' => 'Lic. Karina Arias', 'sub' => false],
            ['dep' => 'Rendición de Cuentas', 'role' => 'Suplente', 'name' => 'Lic. Mónica Zalazar', 'sub' => true],
        ];

        foreach ($members as $index => $m) {
            \App\Models\BoardMember::create([
                'school_id' => $school->id,
                'department' => $m['dep'],
                'role' => $m['role'],
                'name' => $m['name'],
                'is_substitute' => $m['sub'],
                'order' => $index + 1
            ]);
        }
    }
}

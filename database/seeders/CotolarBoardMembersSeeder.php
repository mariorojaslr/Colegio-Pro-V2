<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BoardMember;
use App\Models\School;

class CotolarBoardMembersSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('slug', 'cotolar')->first();
        if (!$school) return;

        // Clear existing ones for Cotolar
        BoardMember::where('school_id', $school->id)->delete();

        $members = [
            // Comisión Directiva
            ['name' => 'Lic. Silvia Arce', 'role' => 'Presidente', 'department' => 'Comisión Directiva', 'order' => 1, 'is_substitute' => false],
            ['name' => 'Lic. Gabriela Avila', 'role' => 'Vicepresidente', 'department' => 'Comisión Directiva', 'order' => 2, 'is_substitute' => false],
            ['name' => 'Lic. Soledad Aguero', 'role' => 'Secretaria', 'department' => 'Comisión Directiva', 'order' => 3, 'is_substitute' => false],
            ['name' => 'Lic. M. Luz García Pereyra', 'role' => 'Tesorera', 'department' => 'Comisión Directiva', 'order' => 4, 'is_substitute' => false],
            ['name' => 'Lic. Aldana Rojas Cabrera', 'role' => '1er Vocal', 'department' => 'Comisión Directiva', 'order' => 5, 'is_substitute' => false],
            ['name' => 'Lic. Marcela Beatriz Mercado', 'role' => '2do Vocal', 'department' => 'Comisión Directiva', 'order' => 6, 'is_substitute' => false],
            ['name' => 'Lic. M. Esther Rodriguez', 'role' => 'Suplente', 'department' => 'Comisión Directiva', 'order' => 7, 'is_substitute' => true],

            // Tribunal de Ética y Disciplina
            ['name' => 'Lic. Pablo Barrionuevo', 'role' => 'Presidente', 'department' => 'Tribunal de Ética', 'order' => 8, 'is_substitute' => false],
            ['name' => 'Lic. Margarita Álvarez', 'role' => '1er Vocal', 'department' => 'Tribunal de Ética', 'order' => 9, 'is_substitute' => false],
            ['name' => 'Lic. Karen Villaroel', 'role' => '2do Vocal', 'department' => 'Tribunal de Ética', 'order' => 10, 'is_substitute' => false],
            ['name' => 'Lic. María Eva Gonzalez', 'role' => '1er Vocal Suplente', 'department' => 'Tribunal de Ética', 'order' => 11, 'is_substitute' => true],
            ['name' => 'Lic. Erica Corzo', 'role' => '2do Vocal Suplente', 'department' => 'Tribunal de Ética', 'order' => 12, 'is_substitute' => true],

            // Rendición de Cuentas
            ['name' => 'Lic. Karina Arias', 'role' => 'Titular', 'department' => 'Rendición de Cuentas', 'order' => 13, 'is_substitute' => false],
            ['name' => 'Lic. Mónica Zalazar', 'role' => 'Suplente', 'department' => 'Rendición de Cuentas', 'order' => 14, 'is_substitute' => true],
        ];

        foreach ($members as $m) {
            BoardMember::create(array_merge($m, [
                'school_id' => $school->id,
                'is_active' => true,
            ]));
        }
    }
}

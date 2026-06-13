<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\BoardMember;

class PruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::updateOrCreate(
            ['slug' => 'prueba'],
            [
                'name' => "C.P. de Sistemas y Tecnología",
                'logo' => "https://img.logoipsum.com/296.svg",
                'primary_color' => "#10B981",
                'secondary_color' => "#0f172a",
                'is_active' => true,
                'plan_category' => 'basic'
            ]
        );

        // Clear old members
        BoardMember::where('school_id', $school->id)->delete();

        // Add new ones
        BoardMember::create(['school_id' => $school->id, 'name' => 'Ing. Ada Lovelace', 'role' => 'Presidente', 'department' => 'Junta Ejecutiva', 'order' => 1]);
        BoardMember::create(['school_id' => $school->id, 'name' => 'Lic. Alan Turing', 'role' => 'Vicepresidente', 'department' => 'Junta Ejecutiva', 'order' => 2]);
        BoardMember::create(['school_id' => $school->id, 'name' => 'Ing. Grace Hopper', 'role' => 'Tesorera', 'department' => 'Finanzas', 'order' => 3]);
    }
}

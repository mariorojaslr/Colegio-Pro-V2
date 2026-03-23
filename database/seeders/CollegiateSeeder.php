<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Collegiate;
use App\Models\School;

class CollegiateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();
        if (!$school) return;

        $data = [
            [
                'school_id' => $school->id,
                'registration_number' => 'MAT-1001-CABA',
                'first_name' => 'Roberto',
                'last_name' => 'Gomez',
                'email' => 'roberto@abogados.com',
                'dni' => '20.123.456',
                'status' => 'active',
            ],
            [
                'school_id' => $school->id,
                'registration_number' => 'MAT-1002-CABA',
                'first_name' => 'Mariela',
                'last_name' => 'Perez',
                'email' => 'mariela@abogados.com',
                'dni' => '25.987.654',
                'status' => 'active',
            ],
            [
                'school_id' => $school->id,
                'registration_number' => 'MAT-2005-CABA',
                'first_name' => 'Carlos',
                'last_name' => 'Sanchez',
                'email' => 'carlos@abogados.com',
                'dni' => '30.456.789',
                'status' => 'active',
            ],
            [
                'school_id' => $school->id,
                'registration_number' => 'MAT-5088-CABA',
                'first_name' => 'Ana',
                'last_name' => 'Lopez',
                'email' => 'ana@abogados.com',
                'dni' => '22.333.444',
                'status' => 'active',
            ],
            [
                'school_id' => $school->id,
                'registration_number' => 'MAT-9999-CABA',
                'first_name' => 'Federico',
                'last_name' => 'Mendez',
                'email' => 'federico@abogados.com',
                'dni' => '28.111.222',
                'status' => 'inactive',
            ],
        ];

        foreach ($data as $item) {
            Collegiate::create($item);
        }
    }
}

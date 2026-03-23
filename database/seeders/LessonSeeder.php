<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();

        foreach ($schools as $school) {
            // Lección 1: Video de Bienvenida (Grabado)
            \App\Models\Lesson::create([
                'school_id' => $school->id,
                'title' => 'Bienvenida al Año Escolar - ' . $school->name,
                'description' => 'Un mensaje de bienvenida de nuestro director para todos los alumnos y padres.',
                'bunny_video_id' => '00000000-0000-0000-0000-000000000001', // ID ficticio para pruebas
                'is_published' => true,
                'is_live' => false,
            ]);

            // Lección 2: Clase Magistral (En Vivo)
            \App\Models\Lesson::create([
                'school_id' => $school->id,
                'title' => 'Taller de Innovación y Tecnología',
                'description' => 'Clase magistral interactiva sobre las nuevas tendencias del mercado.',
                'bunny_video_id' => '00000000-0000-0000-0000-000000000002',
                'is_published' => true,
                'is_live' => true,
                'live_url' => 'https://zoom.us/demo-link',
            ]);

            // Lección 3: Video Tutorial (Grabado)
            \App\Models\Lesson::create([
                'school_id' => $school->id,
                'title' => 'Uso de la Plataforma Colegio-Pro',
                'description' => 'Guía paso a paso para navegar por tu nuevo portal del alumno.',
                'bunny_video_id' => '00000000-0000-0000-0000-000000000003',
                'is_published' => true,
                'is_live' => false,
            ]);
        }
    }
}

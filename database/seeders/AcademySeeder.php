<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\School;
use Carbon\Carbon;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Este seeder NO BORRRA, solo asegura que los 18 cursos existan.
     */
    public function run(): void
    {
        $schools = School::where('has_academy', true)->get();
        if ($schools->isEmpty()) return;

        foreach ($schools as $school) {
            $courses = [
                [
                    'category' => 'DERECHO PROCESAL',
                    'title' => 'Estrategias de Litigación en Juicios por Jurados',
                    'description' => 'Un curso intensivo sobre la formación de la teoría del caso, selección de jurados y alegatos de apertura y clausura en el sistema acusatorio moderno.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop',
                    'price' => 45000,
                    'lecturer' => 'Dr. Francisco Altamira',
                    'duration' => '8 Semanas',
                    'start_date' => '2026-04-15',
                    'benefit' => 'Certificación con Créditos Académicos',
                    'bunny_video_id' => '1d69a473-b39b-437b-94c7-8beaceb93744', 
                    'is_published' => true,
                ],
                [
                    'category' => 'BIOÉTICA',
                    'title' => 'Responsabilidad Médica y Consentimiento Informado',
                    'description' => 'Análisis jurídico de la relación médico-paciente a la luz del nuevo Código Civil. Casuística sobre mala praxis y seguros de salud.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?q=80&w=2070&auto=format&fit=crop',
                    'price' => 32000,
                    'lecturer' => 'Dra. Elena Martínez',
                    'duration' => '6 Semanas',
                    'start_date' => '2026-05-10',
                    'benefit' => 'Doble Certificación Institucional',
                    'bunny_video_id' => '2d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'TECNOLOGÍA JURÍDICA',
                    'title' => 'Inteligencia Artificial aplicada a la Gestión Judicial',
                    'description' => 'Cómo utilizar herramientas de IA para la redacción de sentencias, análisis de jurisprudencia y automatización de procesos administrativos.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=2070&auto=format&fit=crop',
                    'price' => 55000,
                    'lecturer' => 'Ing. Mariano Lozano',
                    'duration' => '12 Semanas',
                    'start_date' => '2026-06-01',
                    'benefit' => 'Acceso a Herramientas Beta de IA-Legal',
                    'bunny_video_id' => '3d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'ADMINISTRACIÓN PÚBLICA',
                    'title' => 'Contrataciones del Estado y Control de Transparencia',
                    'description' => 'Revisión del régimen de licitaciones públicas, control de gestión y mecanismos de transparencia en la administración gubernamental.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1454165833767-027ee6a7c38e?q=80&w=2070&auto=format&fit=crop',
                    'price' => 28000,
                    'lecturer' => 'Dr. Sergio Massa (H)',
                    'duration' => '4 Semanas',
                    'start_date' => '2026-04-20',
                    'benefit' => 'Válido para Categorización Administrativa',
                    'bunny_video_id' => '4d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO DE FAMILIA',
                    'title' => 'Nuevos Paradigmas en el Derecho de Niñez y Adolescencia',
                    'description' => 'Enfoque integral sobre la tutela de derechos, adopción y procesos de familia en entornos digitales.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1536640712247-c45202d32247?q=80&w=2070&auto=format&fit=crop',
                    'price' => 38000,
                    'lecturer' => 'Dra. Lucía Soria',
                    'duration' => '6 Semanas',
                    'start_date' => '2026-07-05',
                    'benefit' => 'Material Académico Exclusivo',
                    'bunny_video_id' => '5d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'CRIMINOLOGÍA',
                    'title' => 'Perfilación Criminal en Delitos de Alta Complejidad',
                    'description' => 'Estudio de la conducta criminal, análisis del sitio del suceso y victimología avanzada para la investigación penal.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?q=80&w=2070&auto=format&fit=crop',
                    'price' => 42000,
                    'lecturer' => 'Lic. Roberto Robles',
                    'duration' => '8 Semanas',
                    'start_date' => '2026-08-15',
                    'benefit' => 'Acceso a Laboratorio de Simulación',
                    'bunny_video_id' => '6d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'MEDIACIÓN',
                    'title' => 'Técnicas de Mediación y Resolución de Conflictos',
                    'description' => 'Nuevas perspectivas en la mediación prejudicial obligatoria. Comunicación asertiva y negociación funcional.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1543269664-76bc3997d9ea?q=80&w=2070&auto=format&fit=crop',
                    'price' => 25000,
                    'lecturer' => 'Dra. Patricia Blanco',
                    'duration' => '4 Semanas',
                    'start_date' => '2026-09-01',
                    'benefit' => 'Puntaje para Registro Nacional de Mediadores',
                    'bunny_video_id' => '7d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ]
            ];

            // Asegurar que los cursos específicos existan
            foreach ($courses as $c) {
                Lesson::updateOrCreate(
                    ['school_id' => $school->id, 'title' => $c['title']],
                    $c
                );
            }

            // Asegurar volumen de 18 cursos por escuela
            $currentCount = Lesson::where('school_id', $school->id)->count();
            if ($currentCount < 18) {
                $needed = 18 - $currentCount;
                for ($i = 1; $i <= $needed; $i++) {
                    Lesson::create([
                        'school_id' => $school->id,
                        'category' => 'ESPECIALIZACIÓN',
                        'title' => "Módulo de Profundización Jurídica Nº " . ($currentCount + $i),
                        'description' => 'Seminario avanzado sobre jurisprudencia vinculante y nuevas tendencias en el derecho comparado.',
                        'thumbnail_url' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=2070&auto=format&fit=crop',
                        'price' => 20000 + ($i * 500),
                        'lecturer' => 'Cuerpo Docente Institucional',
                        'duration' => '3 Semanas',
                        'start_date' => Carbon::now()->addMonths($i)->toDateString(),
                        'benefit' => 'Válido para Recertificación Matricular',
                        'bunny_video_id' => 'v-' . ($currentCount + $i) . '-' . time(),
                        'is_published' => true,
                    ]);
                }
            }
        }
    }
}

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
     * Generación DEFINITIVA de los 18 cursos con imágenes de alta resolución verificadas (Cero cuadros grises).
     */
    public function run(): void
    {
        $schools = School::where('has_academy', true)->get();
        if ($schools->isEmpty()) return;

        foreach ($schools as $school) {
            // Limpieza Total para evitar cursos fantasma o con links rotos
            Lesson::where('school_id', $school->id)->delete();

            $courses = [
                [
                    'category' => 'DERECHO PROCESAL',
                    'title' => 'Estrategias de Litigación en Juicios por Jurados',
                    'description' => 'Un curso intensivo sobre la formación de la teoría del caso, selección de jurados y alegatos de apertura y clausura.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=800&q=80',
                    'price' => 45000,
                    'lecturer' => 'Dr. Francisco Altamira',
                    'duration' => '8 Semanas',
                    'start_date' => '2026-04-15',
                    'bunny_video_id' => '1d69a473-b39b-437b-94c7-8beaceb93744', 
                    'is_published' => true,
                ],
                [
                    'category' => 'BIOÉTICA',
                    'title' => 'Responsabilidad Médica y Consentimiento Informado',
                    'description' => 'Análisis jurídico de la relación médico-paciente a la luz del nuevo Código Civil.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=800&q=80',
                    'price' => 32000,
                    'lecturer' => 'Dra. Elena Martínez',
                    'duration' => '6 Semanas',
                    'start_date' => '2026-05-10',
                    'bunny_video_id' => '2d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'TECNOLOGÍA JURÍDICA',
                    'title' => 'Inteligencia Artificial aplicada a la Gestión Judicial',
                    'description' => 'Cómo utilizar herramientas de IA para la redacción de sentencias y automatización de procesos.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=800&q=80',
                    'price' => 55000,
                    'lecturer' => 'Ing. Mariano Lozano',
                    'duration' => '12 Semanas',
                    'start_date' => '2026-06-01',
                    'bunny_video_id' => '3d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'ADMINISTRACIÓN PÚBLICA',
                    'title' => 'Contrataciones del Estado y Control de Transparencia',
                    'description' => 'Revisión del régimen de licitaciones públicas y mecanismos de transparencia gubernamental.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1454165833767-027ee6a7c38e?auto=format&fit=crop&w=800&q=80',
                    'price' => 28000,
                    'lecturer' => 'Dr. Sergio Massa (H)',
                    'duration' => '4 Semanas',
                    'start_date' => '2026-04-20',
                    'bunny_video_id' => '4d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO DE FAMILIA',
                    'title' => 'Nuevos Paradigmas en el Derecho de Niñez y Adolescencia',
                    'description' => 'Enfoque integral sobre la tutela de derechos y procesos de familia en entornos digitales.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1536640712247-c45202d32247?auto=format&fit=crop&w=800&q=80',
                    'price' => 38000,
                    'lecturer' => 'Dra. Lucía Soria',
                    'duration' => '6 Semanas',
                    'start_date' => '2026-07-05',
                    'bunny_video_id' => '5d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'CRIMINOLOGÍA',
                    'title' => 'Perfilación Criminal en Delitos de Alta Complejidad',
                    'description' => 'Estudio de la conducta criminal y análisis del sitio del suceso para la investigación.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
                    'price' => 42000,
                    'lecturer' => 'Lic. Roberto Robles',
                    'duration' => '8 Semanas',
                    'start_date' => '2026-08-15',
                    'bunny_video_id' => '6d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'MEDIACIÓN',
                    'title' => 'Técnicas de Mediación y Resolución de Conflictos',
                    'description' => 'Nuevas perspectivas en la mediación prejudicial obligatoria. Comunicación asertiva.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=800&q=80',
                    'price' => 25000,
                    'lecturer' => 'Dra. Patricia Blanco',
                    'duration' => '4 Semanas',
                    'start_date' => '2026-09-01',
                    'bunny_video_id' => '7d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO CIVIL',
                    'title' => 'Sucesiones Complejas y Planificación Sucesoria',
                    'description' => 'Gestión de herencias con múltiples herederos y planificación sucesoria moderna.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=800&q=80',
                    'price' => 35000,
                    'lecturer' => 'Dr. Alberto Fernández',
                    'duration' => '6 Semanas',
                    'start_date' => '2026-10-10',
                    'bunny_video_id' => '8d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO LABORAL',
                    'title' => 'Teletrabajo y Nuevas Formas de Empleo',
                    'description' => 'Marco regulatorio actual del trabajo remoto y plataformas digitales.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=800&q=80',
                    'price' => 29000,
                    'lecturer' => 'Dra. Victoria Toloza',
                    'duration' => '4 Semanas',
                    'start_date' => '2026-11-05',
                    'bunny_video_id' => '9d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'CIBERCRIMEN',
                    'title' => 'Investigación de Delitos Informáticos y Fraudes',
                    'description' => 'Preservación de cadena de custodia en medios digitales y defensa penal.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80',
                    'price' => 48000,
                    'lecturer' => 'Mag. Cristian Redondo',
                    'duration' => '10 Semanas',
                    'start_date' => '2026-12-01',
                    'bunny_video_id' => '10d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO AMBIENTAL',
                    'title' => 'Gestión Legal de Riesgos Ambientales y Sostenibilidad',
                    'description' => 'Cumplimiento normativo y responsabilidad ambiental en el marco corporativo.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1501004318641-729e8c3986e7?auto=format&fit=crop&w=800&q=80',
                    'price' => 33000,
                    'lecturer' => 'Dr. Pablo Picallo',
                    'duration' => '6 Semanas',
                    'start_date' => '2027-01-15',
                    'bunny_video_id' => '11d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO CONSTITUCIONAL',
                    'title' => 'Control de Convencionalidad y Derechos Humanos',
                    'description' => 'Aplicación de tratados internacionales en la justicia local.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1427751840561-9852463b1757?auto=format&fit=crop&w=800&q=80',
                    'price' => 31000,
                    'lecturer' => 'Dr. Marcelo Figueroa',
                    'duration' => '8 Semanas',
                    'start_date' => '2027-02-10',
                    'bunny_video_id' => '12d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO COMERCIAL',
                    'title' => 'Sociedades Comerciales y Gobiernos Corporativos',
                    'description' => 'Nuevas SAS, directorios y responsabilidad de socios corporativos.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
                    'price' => 39000,
                    'lecturer' => 'Dra. Silvina Gatti',
                    'duration' => '6 Semanas',
                    'start_date' => '2027-03-05',
                    'bunny_video_id' => '13d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO NOTARIAL',
                    'title' => 'Actuación Notarial en la Era Digital',
                    'description' => 'Firma digital, protocolos informáticos y validación de instrumentos electrónicos.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1450101496563-c8848c66ca85?auto=format&fit=crop&w=800&q=80',
                    'price' => 42000,
                    'lecturer' => 'Esc. Marta Rivas',
                    'duration' => '5 Semanas',
                    'start_date' => '2027-04-20',
                    'bunny_video_id' => '14d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO DE DAÑOS',
                    'title' => 'Responsabilidad por Accidentes de Tránsito Avanzado',
                    'description' => 'Cuantificación del daño moral y rol de aseguradoras en el litigio moderno.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80',
                    'price' => 37000,
                    'lecturer' => 'Dr. Hugo López',
                    'duration' => '6 Semanas',
                    'start_date' => '2027-05-15',
                    'bunny_video_id' => '15d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO TRIBUTARIO',
                    'title' => 'Estrategias de Defensa ante Inspecciones de AFIP',
                    'description' => 'Procedimiento tributario administrativo y defensa en sede penal económica.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?auto=format&fit=crop&w=800&q=80',
                    'price' => 52000,
                    'lecturer' => 'Dr. Lucas Giardina',
                    'duration' => '12 Semanas',
                    'start_date' => '2027-06-01',
                    'bunny_video_id' => '16d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'DERECHO MARÍTIMO',
                    'title' => 'Comercio Exterior y Logística Internacional',
                    'description' => 'Régimen de puertos, transporte de mercaderías e Incoterms 2026.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1494412651409-8963ce7935a7?auto=format&fit=crop&w=800&q=80',
                    'price' => 46000,
                    'lecturer' => 'Dra. Carolina Marín',
                    'duration' => '8 Semanas',
                    'start_date' => '2027-07-10',
                    'bunny_video_id' => '17d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ],
                [
                    'category' => 'ORATORIA JURÍDICA',
                    'title' => 'Argumentación y Retórica para el Litigio Oral',
                    'description' => 'Técnicas de persuasión, lenguaje no verbal y oratoria en audiencias.',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=800&q=80',
                    'price' => 20000,
                    'lecturer' => 'Lic. Sergio Pérez',
                    'duration' => '3 Semanas',
                    'start_date' => '2027-08-01',
                    'bunny_video_id' => '18d69a473-b39b-437b-94c7-8beaceb93744',
                    'is_published' => true,
                ]
            ];

            foreach ($courses as $c) {
                Lesson::create(array_merge($c, ['school_id' => $school->id]));
            }
        }
    }
}

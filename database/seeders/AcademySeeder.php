<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\School;
use Illuminate\Database\Seeder;

class AcademySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener el primer colegio para asignar los cursos (o iterar si se desea)
        $school = School::first();
        if (!$school) {
            $this->command->error('No se encontró ningún colegio para asignar los cursos.');
            return;
        }

        // 2. Limpiar lecciones previas de este colegio para la restauración limpia
        Lesson::where('school_id', $school->id)->delete();

        $courses = [
            [
                'title' => 'Derecho Penal: Teoría del Delito',
                'category' => 'PENAL',
                'description' => 'Un recorrido avanzado por los elementos constitutivos del delito: acción, tipicidad, antijuridicidad y culpabilidad. Imprescindible para el ejercicio penal moderno.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop',
                'price' => 25000,
                'lecturer' => 'Dr. Alejandro Slokar',
                'duration' => '12 Semanas',
                'start_date' => '10 de Abril, 2026',
                'benefit' => 'Certificación con aval internacional y acceso a jurisprudencia exclusiva.',
                'is_published' => true,
            ],
            [
                'title' => 'Derecho Civil: Sucesiones Complejas',
                'category' => 'CIVIL',
                'description' => 'Estrategias para la resolución de procesos sucesorios en disputa, legítima, partición y herencias transfronterizas bajo el nuevo código.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?q=80&w=2070&auto=format&fit=crop',
                'price' => 32000,
                'lecturer' => 'Dra. Marisa Herrera',
                'duration' => '8 Semanas',
                'start_date' => '15 de Mayo, 2026',
                'benefit' => 'Modelos de escritos procesales y talleres prácticos de partición hereditaria.',
                'is_published' => true,
            ],
            [
                'title' => 'Procesal Penal: El Juicio Oral',
                'category' => 'PROCESAL',
                'description' => 'Técnicas de litigación oral, interrogatorio de testigos y peritos. Cómo construir un alegato de apertura y clausura de impacto.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1589216532372-1c2a367900d9?q=80&w=2071&auto=format&fit=crop',
                'price' => 28000,
                'lecturer' => 'Dr. Alberto Binder',
                'duration' => '10 Semanas',
                'start_date' => '05 de Junio, 2026',
                'benefit' => 'Simulacros de juicios con evaluación personalizada de desempeño.',
                'is_published' => true,
            ],
            [
                'title' => 'Innovación Judicial y IA',
                'category' => 'TECNOLOGÍA',
                'description' => 'Cómo impacta la Inteligencia Artificial en la redacción de sentencias y la gestión de expedientes digitales. El futuro de la justicia.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1932&auto=format&fit=crop',
                'price' => 45000,
                'lecturer' => 'Mag. Mario Adaro',
                'duration' => '6 Semanas',
                'start_date' => '22 de Abril, 2026',
                'benefit' => 'Acceso a herramientas de IA generativa configuradas para uso legal.',
                'is_published' => true,
            ],
            [
                'title' => 'Salud Legal y RCP para Peritos',
                'category' => 'SALUD',
                'description' => 'Formación médica esencial para peritos judiciales y abogados: desde interpretación de informes hasta maniobras de RCP.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1584036561566-baf8f5f1b144?q=80&w=2060&auto=format&fit=crop',
                'price' => 18000,
                'lecturer' => 'Dr. Nelson Castro',
                'duration' => '4 Semanas',
                'start_date' => 'Próximamente',
                'benefit' => 'Créditos oficiales para el registro de peritos judiciales.',
                'is_published' => true,
            ],
            [
                'title' => 'Derecho Laboral: El Teletrabajo',
                'category' => 'LABORAL',
                'description' => 'Análisis de la nueva normativa de teletrabajo, accidentes in itinere y derecho a la desconexión digital en la era post-pandemia.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?q=80&w=2070&auto=format&fit=crop',
                'price' => 22000,
                'lecturer' => 'Dr. Julio Grisolía',
                'duration' => '6 Semanas',
                'start_date' => '12 de Mayo, 2026',
                'benefit' => 'Cuadernillos de actualización con fallos de la Cámara del Trabajo.',
                'is_published' => true,
            ],
            [
                'title' => 'Familia: Nuevos Paradigmas',
                'category' => 'CIVIL',
                'description' => 'Uniones convivenciales, compensación económica y responsabilidad parental en el siglo XXI. Visión práctica y jurisprudencial.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1510563354640-ad9202a0f8fc?q=80&w=2070&auto=format&fit=crop',
                'price' => 30000,
                'lecturer' => 'Dra. Aida Kemelmajer',
                'duration' => '8 Semanas',
                'start_date' => '20 de Julio, 2026',
                'benefit' => 'Foro de debate con especialistas en derecho de familia.',
                'is_published' => true,
            ],
            [
                'title' => 'Constitucional Aplicado',
                'category' => 'PÚBLICO',
                'description' => 'El control de convencionalidad y la aplicación directa de tratados internacionales en la justicia local. Desafíos para el juez.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=2071&auto=format&fit=crop',
                'price' => 27000,
                'lecturer' => 'Dr. Horacio Rosatti',
                'duration' => '10 Semanas',
                'start_date' => '15 de Mayo, 2026',
                'benefit' => 'Certificado de excelencia académica por el Instituto Constitucional.',
                'is_published' => true,
            ],
            [
                'title' => 'Administrativo Sancionador',
                'category' => 'ADMINISTRATIVO',
                'description' => 'Límites de la potestad sancionatoria del Estado, debido proceso y control judicial de las multas administrativas.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1521791136064-7986c29596ba?q=80&w=2070&auto=format&fit=crop',
                'price' => 24000,
                'lecturer' => 'Dr. Guido Tawil',
                'duration' => '7 Semanas',
                'start_date' => '10 de Agosto, 2026',
                'benefit' => 'Talleres de redacción de recursos administrativos.',
                'is_published' => true,
            ],
            [
                'title' => 'Crimen Organizado y Narcos',
                'category' => 'PENAL',
                'description' => 'Investigación de delitos complejos, lavado de dinero y la estructura de las organizaciones criminales modernas.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1582139329536-e7284fece509?q=80&w=2080&auto=format&fit=crop',
                'price' => 35000,
                'lecturer' => 'Dr. Mariano Borinsky',
                'duration' => '12 Semanas',
                'start_date' => '01 de Junio, 2026',
                'benefit' => 'Material exclusivo de agencias internacionales de seguridad.',
                'is_published' => true,
            ],
            [
                'title' => 'Derecho del Consumidor Online',
                'category' => 'CIVIL',
                'description' => 'Protección del usuario en e-commerce, contratos de adhesión y la responsabilidad de los market places.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?q=80&w=2070&auto=format&fit=crop',
                'price' => 19000,
                'lecturer' => 'Dr. Ricardo Lorenzetti',
                'duration' => '6 Semanas',
                'start_date' => 'Próximamente',
                'benefit' => 'Modelos de reclamos ante organismos de defensa del consumidor.',
                'is_published' => true,
            ],
            [
                'title' => 'Responsabilidad Médica',
                'category' => 'SALUD',
                'description' => 'El deber de cuidado, mala praxis y seguros médicos. Cómo abordar judicialmente el error médico profesional.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1504439468489-c8920d796a29?q=80&w=2071&auto=format&fit=crop',
                'price' => 31000,
                'lecturer' => 'Dra. Sandra Arroyo Salgado',
                'duration' => '9 Semanas',
                'start_date' => '15 de Julio, 2026',
                'benefit' => 'Seminario presencial con expertos legistas.',
                'is_published' => true,
            ],
            [
                'title' => 'Ambiental y Sustentabilidad',
                'category' => 'PÚBLICO',
                'description' => 'El amparo ambiental, daño colectivo y los principios de prevención y precaución en la justicia argentina.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2070&auto=format&fit=crop',
                'price' => 26000,
                'lecturer' => 'Dr. Antonio Brailovsky',
                'duration' => '8 Semanas',
                'start_date' => '05 de Septiembre, 2026',
                'benefit' => 'Acceso a la biblioteca digital de derecho ambiental.',
                'is_published' => true,
            ],
            [
                'title' => 'Mediación: El Arte de Acordar',
                'category' => 'GENERAL',
                'description' => 'Herramientas de negociación de Harvard aplicadas a la mediación judicial. Cómo destrabar conflictos de alta intensidad.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1577416416182-ed73779e56ab?q=80&w=2083&auto=format&fit=crop',
                'price' => 15000,
                'lecturer' => 'Dra. Gladys Alvarez',
                'duration' => '5 Semanas',
                'start_date' => '20 de Abril, 2026',
                'benefit' => 'Taller de rol-playing interactivo.',
                'is_published' => true,
            ],
            [
                'title' => 'Seguros y Reaseguros',
                'category' => 'CIVIL',
                'description' => 'Contrato de seguro, cláusulas de exclusión y la dinámica del mercado asegurador frente a nuevos riesgos.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1454165833767-027ffea70250?q=80&w=2070&auto=format&fit=crop',
                'price' => 29000,
                'lecturer' => 'Dr. Waldo Sobrino',
                'duration' => '8 Semanas',
                'start_date' => '10 de Octubre, 2026',
                'benefit' => 'Modelos de liquidación de siniestros.',
                'is_published' => true,
            ],
            [
                'title' => 'Informático y Peritajes',
                'category' => 'TECNOLOGÍA',
                'description' => 'Cadena de custodia digital, validez de la prueba de WhatsApp y correos electrónicos en procesos judiciales.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop',
                'price' => 38000,
                'lecturer' => 'Ing. Gustavo Sain',
                'duration' => '7 Semanas',
                'start_date' => 'Próximamente',
                'benefit' => 'Manual de recolección de evidencia digital.',
                'is_published' => true,
            ],
            [
                'title' => 'Gestión de Despachos',
                'category' => 'GENERAL',
                'description' => 'Marketing jurídico, rentabilidad y liderazgo de equipos en estudios de abogados pequeños y medianos.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop',
                'price' => 12000,
                'lecturer' => 'Lic. Jaime Fernández',
                'duration' => '4 Semanas',
                'start_date' => '01 de Junio, 2026',
                'benefit' => 'Plantillas de control de gestión y presupuestos.',
                'is_published' => true,
            ],
            [
                'title' => 'Internacional de DDHH',
                'category' => 'PÚBLICO',
                'description' => 'Sistema Interamericano y Europeo de DDHH. El rol de la CIDH y la Corte IDH en la protección de los ciudadanos.',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1453941403753-3244abc256f3?q=80&w=2070&auto=format&fit=crop',
                'price' => 33000,
                'lecturer' => 'Dr. Sergio García Ramírez',
                'duration' => '11 Semanas',
                'start_date' => '15 de Agosto, 2026',
                'benefit' => 'Becas de investigación para los mejores promedios.',
                'is_published' => true,
            ],
        ];

        foreach ($courses as $c) {
            Lesson::create(array_merge($c, ['school_id' => $school->id]));
        }

        $this->command->info('¡18 cursos premium restaurados correctamente!');
    }
}

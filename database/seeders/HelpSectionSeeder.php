<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HelpSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🏠 Dashboard / Home
        \App\Models\HelpSection::updateOrCreate(
            ['route_name' => 'home'],
            [
                'title' => 'Guía del Panel Principal',
                'content' => '<p>Bienvenido a su <strong>Torre de Control</strong> institucional. Desde aquí tiene una visión omnisciente de los 4 indicadores clave:</p><ul><li><strong>Matriculados:</strong> Base total de su comunidad.</li><li><strong>Deben Cuotas:</strong> Profesionales con mora administrativa. Haga clic para entrar al listado y enviar avisos.</li><li><strong>Deben Papeles:</strong> Colegiados con legajos incompletos.</li><li><strong>Habilitados:</strong> Profesionales que cumplen con todos los requisitos legales.</li></ul><p>Use la <em>Pizarra de Novedades</em> para comunicar eventos importantes directamente a sus matriculados.</p>',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ]
        );

        // 👥 Padrón Profesional
        \App\Models\HelpSection::updateOrCreate(
            ['route_name' => 'collegiates.index'],
            [
                'title' => 'Gestión del Padrón Inteligente',
                'content' => '<p>El Padrón es el corazón operativo del colegio. Para una gestión eficiente:</p><ol><li><strong>Búsqueda Real-Time:</strong> Comience a escribir (Nombre, DNI, Legajo o Mail) y el sistema resaltará en <strong>amarillo</strong> las coincidencias al instante.</li><li><strong>Exportación:</strong> Use el botón "Descargar Excel" para obtener una copia local de seguridad o para auditorías externas.</li><li><strong>Importación Masiva:</strong> Si necesita actualizar miles de registros, descargue la <em>Plantilla Modelo</em> y súbala de nuevo. El sistema detectará cambios y evitará duplicados automáticamente.</li></ol>',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ]
        );

        // 🏟️ Club y Sedes
        \App\Models\HelpSection::updateOrCreate(
            ['route_name' => 'amenities.index'],
            [
                'title' => 'Administración de Recursos y Sedes',
                'content' => '<p>Optimice el uso de la infraestructura del colegio (Quinchos, Canchas, Salones):</p><ul><li><strong>Agenda de Compromisos:</strong> Visualice por cada recurso quién lo tiene reservado, en qué día y rango horario.</li><li><strong>Disponibilidad:</strong> El sistema previene solapamientos. No podrá reservar un horario que ya esté comprometido.</li><li><strong>Gestión de Cobro:</strong> Al confirmar un turno, puede marcar el estado de pago para llevar la contabilidad del club al día.</li></ul>',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
            ]
        );
    }
}

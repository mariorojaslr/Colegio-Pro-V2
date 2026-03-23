<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Event;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::firstOrCreate(
            ['slug' => 'demo-school'],
            [
                'name' => 'Colegio Profesional de Pruebas',
                'primary_color' => '#10B981',
                'secondary_color' => '#F59E0B',
                'plan_category' => 'professional'
            ]
        );

        // Crear Eventos
        Event::create([
            'school_id' => $school->id,
            'title' => 'Conferencia Anual de Ética Profesional',
            'description' => 'Un encuentro para discutir los desafíos éticos en la era digital.',
            'date' => now()->addDays(15),
            'location' => 'Salón Auditórium Central',
            'capacity' => 200
        ]);

        Event::create([
            'school_id' => $school->id,
            'title' => 'Taller de Gestión de Proyectos',
            'description' => 'Herramientas modernas para profesionales independientes.',
            'date' => now()->addDays(5),
            'location' => 'Sala de Capacitación B',
            'capacity' => 50
        ]);

        // Crear Servicios (Productos)
        Service::create([
            'school_id' => $school->id,
            'name' => 'Certificado de Vigencia Especial',
            'description' => 'Documento digital con firma electrónica válida por 3 meses.',
            'price' => 2500.00,
            'is_available' => true
        ]);

        Service::create([
            'school_id' => $school->id,
            'name' => 'Alquiler de Sala de Reuniones (por hora)',
            'description' => 'Espacio climatizado con proyector y wifi de alta velocidad.',
            'price' => 5000.00,
            'is_available' => true
        ]);
    }
}

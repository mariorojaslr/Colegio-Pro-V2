<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\School;
use App\Models\ComplianceRequirement;

// Asegurarse de que exista el colegio demo
$school = School::firstOrCreate(
    ['slug' => 'demo'],
    ['name' => 'Colegio de Terapistas Ocupacionales', 'status' => 'active']
);

$reqs = [
    [
        'name' => 'Documento Nacional de Identidad (DNI)',
        'description' => 'Copia escaneada de frente y dorso del DNI actualizado.',
        'type' => 'permanent',
        'expiry_frequency' => 'none',
        'is_mandatory' => true
    ],
    [
        'name' => 'Título Profesional',
        'description' => 'Copia legalizada del título universitario de Terapia Ocupacional.',
        'type' => 'permanent',
        'expiry_frequency' => 'none',
        'is_mandatory' => true
    ],
    [
        'name' => 'Certificado de Domicilio',
        'description' => 'Certificado emitido por la policía local o servicio a su nombre. Válido por 1 año.',
        'type' => 'perentory',
        'expiry_frequency' => 'year',
        'is_mandatory' => true
    ],
    [
        'name' => 'Certificado de Antecedentes Penales',
        'description' => 'Certificado de Buena Conducta / Antecedentes vigente. Válido por 6 meses.',
        'type' => 'perentory',
        'expiry_frequency' => 'semester',
        'is_mandatory' => true
    ]
];

foreach ($reqs as $req) {
    ComplianceRequirement::updateOrCreate(
        ['school_id' => $school->id, 'name' => $req['name']],
        $req
    );
}

echo "Requisitos base creados exitosamente.\n";

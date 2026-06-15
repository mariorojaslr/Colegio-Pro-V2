<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\ComplianceRequirement;

class CotolarRequirementsSeeder extends Seeder
{
    public function run()
    {
        $s = School::where('slug', 'cotolar')->first();
        if ($s) {
            ComplianceRequirement::where('school_id', $s->id)->delete();
            $reqs = [
                ['name' => 'Llenar solicitud de inscripción', 'delivery_method' => 'digital'],
                ['name' => 'Fotocopia del título inscripto', 'delivery_method' => 'digital'],
                ['name' => 'Fotocopia del DNI', 'delivery_method' => 'digital'],
                ['name' => 'Fotocopia del analítico', 'delivery_method' => 'digital'],
                ['name' => 'Certificado de domicilio', 'delivery_method' => 'digital'],
                ['name' => 'Certificado de antecedentes', 'delivery_method' => 'digital'],
                ['name' => 'Currículum nominal', 'delivery_method' => 'digital'],
                ['name' => '2 fotos 4x4', 'delivery_method' => 'physical'],
                ['name' => 'Carpeta colgante', 'delivery_method' => 'physical'],
            ];
            foreach ($reqs as $r) {
                ComplianceRequirement::create([
                    'school_id' => $s->id,
                    'name' => $r['name'],
                    'type' => 'permanent',
                    'delivery_method' => $r['delivery_method'],
                    'is_mandatory' => true,
                ]);
            }
            echo "Requisitos de Cotolar creados exitosamente.\n";
        }
    }
}

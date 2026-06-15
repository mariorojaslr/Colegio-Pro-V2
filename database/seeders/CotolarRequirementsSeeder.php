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
                ['name' => 'Llenar solicitud de inscripción', 'is_physical' => false],
                ['name' => 'Fotocopia del título inscripto', 'is_physical' => false],
                ['name' => 'Fotocopia del DNI', 'is_physical' => false],
                ['name' => 'Fotocopia del analítico', 'is_physical' => false],
                ['name' => 'Certificado de domicilio', 'is_physical' => false],
                ['name' => 'Certificado de antecedentes', 'is_physical' => false],
                ['name' => 'Currículum nominal', 'is_physical' => false],
                ['name' => '2 fotos 4x4', 'is_physical' => true],
                ['name' => 'Carpeta colgante', 'is_physical' => true],
            ];
            foreach ($reqs as $r) {
                ComplianceRequirement::create([
                    'school_id' => $s->id,
                    'name' => $r['name'],
                    'type' => 'permanent',
                    'is_physical' => $r['is_physical'],
                    'is_mandatory' => true,
                ]);
            }
            echo "Requisitos de Cotolar creados exitosamente.\n";
        }
    }
}

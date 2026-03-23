<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplianceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();

        foreach ($schools as $school) {
            // 1. Definir Requisitos para este Colegio Profesionales
            
            // Permanente
            $reqDNI = \App\Models\ComplianceRequirement::create([
                'school_id' => $school->id,
                'name' => 'Copia de DNI / Identificación',
                'type' => 'permanent',
                'is_mandatory' => true,
            ]);

            // Permanente
            $reqTitulo = \App\Models\ComplianceRequirement::create([
                'school_id' => $school->id,
                'name' => 'Título Profesional Habilitante',
                'type' => 'permanent',
                'is_mandatory' => true,
            ]);

            // Perentorio (Vence)
            $reqSeguro = \App\Models\ComplianceRequirement::create([
                'school_id' => $school->id,
                'name' => 'Seguro de Responsabilidad Civil',
                'description' => 'Debe renovarse anualmente',
                'type' => 'perentory',
                'is_mandatory' => true,
            ]);

            // 2. Simular entregas para los colegiados
            $collegiates = $school->collegiates;

            foreach ($collegiates as $collegiate) {
                // Entrega DNI (Aprobado)
                \App\Models\CollegiateDocument::create([
                    'collegiate_id' => $collegiate->id,
                    'compliance_requirement_id' => $reqDNI->id,
                    'status' => 'approved',
                    'file_path' => 'docs/dni_1.pdf',
                    'created_at' => now()->subDays(30),
                ]);

                // Entrega Título (Pendiente)
                \App\Models\CollegiateDocument::create([
                    'collegiate_id' => $collegiate->id,
                    'compliance_requirement_id' => $reqTitulo->id,
                    'status' => 'pending',
                    'file_path' => 'docs/titulo_1.pdf',
                ]);

                // Actualizar estado del colegiado
                $collegiate->update([
                    'is_fees_compliant' => true, // Simular que está al día con la cuota
                    'is_ethics_compliant' => true, // Sin sanciones
                ]);
            }
        }
    }
}

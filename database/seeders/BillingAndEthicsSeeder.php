<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Collegiate;
use App\Models\MembershipFee;
use App\Models\CollegiateDue;
use App\Models\CertificateType;
use App\Models\EthicsSanction;
use App\Models\User;
use Carbon\Carbon;

class BillingAndEthicsSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;

        // 1. Configurar Cuota Societaria
        MembershipFee::updateOrCreate(
            ['school_id' => $school->id, 'is_active' => true],
            ['amount' => 25000, 'effective_date' => '2026-01-01']
        );

        // 2. Generar Historial de Pagos para Colegiados del Colegio 1
        $collegiates = Collegiate::where('school_id', $school->id)->get();
        
        foreach ($collegiates as $index => $collegiate) {
            // Generamos cuotas de Enero a Mayo 2026
            for ($month = 1; $month <= 5; $month++) {
                $dueDate = Carbon::create(2026, $month, 10);
                
                // Los primeros 3 meses están pagados, el resto pendiente u atrasado
                $status = ($month <= 3) ? 'paid' : (($month == 4) ? 'overdue' : 'pending');
                $paidAt = ($status == 'paid') ? $dueDate->copy()->subDays(2) : null;

                CollegiateDue::create([
                    'collegiate_id' => $collegiate->id,
                    'amount' => 25000,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'paid_at' => $paidAt,
                    'payment_reference' => $status == 'paid' ? 'REF-'.rand(1000, 9999) : null
                ]);
            }
        }

        // 3. Tipos de Certificados (Entregables)
        $certTypes = [
            ['name' => 'Certificado de Ética Profesional', 'price' => 2500, 'validity_days' => 30],
            ['name' => 'Certificado de Matrícula Vigente', 'price' => 1500, 'validity_days' => 90],
            ['name' => 'Certificado de Libre Deuda', 'price' => 3000, 'validity_days' => 30],
            ['name' => 'Certificado de Baja de Matrícula', 'price' => 5000, 'validity_days' => 0],
        ];

        foreach ($certTypes as $ct) {
            CertificateType::updateOrCreate(
                ['school_id' => $school->id, 'name' => $ct['name']],
                $ct
            );
        }

        // 4. Una Sanción de Ética de prueba para el primer colegiado
        if ($collegiates->count() > 0) {
            $unluckyCollegiate = $collegiates->first();
            EthicsSanction::create([
                'collegiate_id' => $unluckyCollegiate->id,
                'type' => 'temporary',
                'reason' => 'Incumplimiento de normas éticas en colegiación.',
                'arguments' => 'Se detectó una falta en la presentación de documentación obligatoria y comportamiento no profesional.',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'status' => 'active'
            ]);
        }

        $this->command->info('¡Datos de Facturacíón y Ética generados correctamente!');
    }
}

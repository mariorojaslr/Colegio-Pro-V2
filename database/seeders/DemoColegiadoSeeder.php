<?php

namespace Database\Seeders;

use App\Models\Collegiate;
use App\Models\CollegiateDue;
use App\Models\EthicsSanction;
use App\Models\School;
use App\Models\PaymentAgreement;
use App\Models\PaymentAgreementInstallment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemoColegiadoSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('slug', 'abogados')->first();
        if (!$school) return;

        // Limpiar datos previos globales para evitar colisiones de MATRÍCULA
        // Ya que la tabla actual tiene un UNIQUE global en vez de por colegio:
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Casos fijos de la demo
        $demoMats = ['MAT-1001', 'MAT-2002', 'MAT-3003', 'MAT-4004'];
        
        // Limpiamos deudas y sanciones de esos registros
        $oldIds = Collegiate::whereIn('registration_number', $demoMats)->pluck('id');
        CollegiateDue::whereIn('collegiate_id', $oldIds)->delete();
        EthicsSanction::whereIn('collegiate_id', $oldIds)->delete();
        PaymentAgreement::whereIn('collegiate_id', $oldIds)->delete();
        
        // BORRAMOS los colegiados de demo para asegurar recreación limpia
        Collegiate::whereIn('registration_number', $demoMats)->delete();
        
        // Borramos el resto de los MAT-5000+ si existieran
        Collegiate::where('registration_number', 'like', 'MAT-5%')->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // CASO 1: Dr. Juan Pérez (Deudor Crónico - Moroso Permanente)
        $perez = Collegiate::create([
            'school_id' => $school->id,
            'registration_number' => 'MAT-1001',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@example.com',
            'dni' => '11.222.333',
            'phone' => '1155551111',
            'status' => 'active',
            'is_fees_compliant' => false,
            'is_ethics_compliant' => true,
            'is_fully_documented' => true,
        ]);

        for ($i = 1; $i <= 12; $i++) {
            CollegiateDue::create([
                'collegiate_id' => $perez->id,
                'amount' => 5000.00,
                'due_date' => Carbon::now()->subMonths($i)->day(10),
                'status' => 'overdue'
            ]);
        }

        // CASO 2: Dra. Elena Gómez (Habilitada en Pagos, Sancionada en Ética)
        $gomez = Collegiate::create([
            'school_id' => $school->id,
            'registration_number' => 'MAT-2002',
            'first_name' => 'Elena',
            'last_name' => 'Gómez',
            'email' => 'elena.gomez@example.com',
            'dni' => '22.333.444',
            'phone' => '1155552222',
            'status' => 'active',
            'is_fees_compliant' => true,
            'is_ethics_compliant' => false,
            'is_fully_documented' => true,
        ]);

        for ($i = 1; $i <= 12; $i++) {
            CollegiateDue::create([
                'collegiate_id' => $gomez->id,
                'amount' => 4500.00,
                'due_date' => Carbon::now()->subMonths($i + 12)->day(10),
                'status' => 'paid',
                'paid_at' => Carbon::now()->subMonths($i + 12)->day(12)
            ]);
        }

        EthicsSanction::create([
            'collegiate_id' => $gomez->id,
            'type' => 'temporary',
            'reason' => 'Falta de ética profesional en juicio de divorcio (Exp. 445/2026)',
            'arguments' => 'Se constató que la abogada reveló secretos de sumario antes de tiempo.',
            'start_date' => Carbon::now()->subDays(15),
            'end_date' => Carbon::now()->addDays(45),
            'status' => 'active',
            'approved_by_president' => true
        ]);

        // CASO 3: Lic. Ricardo García (Habilitado Perfecto - Caso de Éxito)
        $garcia = Collegiate::create([
            'school_id' => $school->id,
            'registration_number' => 'MAT-3003',
            'first_name' => 'Ricardo',
            'last_name' => 'García',
            'email' => 'ricardo.garcia@example.com',
            'dni' => '33.444.555',
            'phone' => '1155553333',
            'status' => 'active',
            'is_fees_compliant' => true,
            'is_ethics_compliant' => true,
            'is_fully_documented' => true,
        ]);

        CollegiateDue::create([
            'collegiate_id' => $garcia->id,
            'amount' => 6000.00,
            'due_date' => Carbon::now()->subMonth()->day(10),
            'status' => 'paid',
            'paid_at' => Carbon::now()->subMonth()->day(9)
        ]);

        // CASO 4: Ing. Marcos Lopez (Con Convenio de Pago 12x10)
        $lopez = Collegiate::create([
            'school_id' => $school->id,
            'registration_number' => 'MAT-4004',
            'first_name' => 'Marcos',
            'last_name' => 'Lopez',
            'email' => 'marcos.lopez@example.com',
            'dni' => '44.555.666',
            'phone' => '1155554444',
            'status' => 'active',
            'is_fees_compliant' => false,
            'is_ethics_compliant' => true,
            'is_fully_documented' => true,
        ]);

        $agreement = PaymentAgreement::create([
            'school_id' => $school->id,
            'collegiate_id' => $lopez->id,
            'type' => 'yearly_promo',
            'total_amount_original' => 60000.00,
            'total_amount_agreement' => 50000.00,
            'installment_count' => 12,
            'status' => 'active',
            'metadata' => ['promo_name' => 'Plan Anual 12x10']
        ]);

        for ($i = 0; $i < 12; $i++) {
            PaymentAgreementInstallment::create([
                'payment_agreement_id' => $agreement->id,
                'due_date' => Carbon::now()->addMonths($i)->day(15),
                'amount' => 4166.66,
                'status' => $i < 2 ? 'paid' : 'pending'
            ]);
        }

        // Crear masivos
        for ($i = 0; $i < 100; $i++) {
            $regNum = 'MAT-' . (5000 + $i);
            $c = Collegiate::create([
                'school_id' => $school->id,
                'registration_number' => $regNum,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'dni' => rand(10, 40) . '.' . rand(100, 999) . '.' . rand(100, 999),
                'phone' => '11' . rand(40000000, 60000000),
                'status' => rand(0, 5) > 0 ? 'active' : 'suspended',
                'is_fees_compliant' => rand(0, 1) == 1,
                'is_ethics_compliant' => rand(0, 5) > 0,
                'is_fully_documented' => rand(0, 3) > 0,
            ]);

            CollegiateDue::create([
                'collegiate_id' => $c->id,
                'amount' => 5000,
                'due_date' => Carbon::now()->subMonth()->day(10),
                'status' => $c->is_fees_compliant ? 'paid' : 'overdue'
            ]);
        }
    }
}

<?php

namespace App\Services;

use App\Models\School;
use App\Models\Collegiate;
use App\Models\CollegiateDue;
use App\Models\MembershipFee;
use Carbon\Carbon;

class BillingService
{
    /**
     * Genera las cuotas del mes actual para una escuela específica.
     * Solo facturará a los colegiados activos con billing_profile = 'mensual'.
     * Evita duplicados (idempotente).
     *
    public function generateMonthlyDuesForSchool(School $school): array
    {
        $activeFee = MembershipFee::where('school_id', $school->id)->where('is_active', true)->first();

        if (!$activeFee) {
            return [
                'status' => 'error',
                'message' => 'Debe definir un valor de cuota activo primero.'
            ];
        }

        $allCollegiates = Collegiate::where('school_id', $school->id)
            ->where('status', 'active')
            ->get();
            
        $totalActive = $allCollegiates->count();
        $generatedCount = 0;
        $updatedCount = 0;
        $exceptedCount = 0;
        $alreadyPaidCount = 0;
        $alreadyGeneratedCount = 0;

        $dueDate = Carbon::now()->endOfMonth();

        foreach ($allCollegiates as $collegiate) {
            // Excepciones: por ejemplo perfil 'anual' u otros exceptuados
            if ($collegiate->billing_profile === 'anual') {
                $exceptedCount++;
                continue;
            }

            // Buscar si ya existe una cuota para este mes
            $existingDue = CollegiateDue::where('collegiate_id', $collegiate->id)
                ->whereYear('due_date', $dueDate->year)
                ->whereMonth('due_date', $dueDate->month)
                ->first();

            if (!$existingDue) {
                // No existe, la creamos
                CollegiateDue::create([
                    'collegiate_id' => $collegiate->id,
                    'amount' => $activeFee->amount,
                    'due_date' => clone $dueDate,
                    'status' => 'pending'
                ]);
                
                // Si se generó deuda, el usuario pasa a no estar al día
                $collegiate->update(['is_fees_compliant' => false]);
                $generatedCount++;
            } else {
                if ($existingDue->status === 'paid') {
                    $alreadyPaidCount++;
                } elseif ($existingDue->status === 'pending') {
                    if ($existingDue->amount != $activeFee->amount) {
                        // Ya existe, está pendiente, pero el monto es diferente (el admin corrigió el valor de la cuota)
                        $existingDue->update(['amount' => $activeFee->amount]);
                        $updatedCount++;
                    } else {
                        $alreadyGeneratedCount++;
                    }
                }
            }
        }

        return [
            'status' => 'success',
            'total_active' => $totalActive,
            'generated' => $generatedCount,
            'updated' => $updatedCount,
            'excepted' => $exceptedCount,
            'already_paid' => $alreadyPaidCount,
            'already_generated' => $alreadyGeneratedCount,
            'total_processed' => $generatedCount + $updatedCount
        ];
    }
}

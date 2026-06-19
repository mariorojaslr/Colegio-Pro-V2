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
     * @param School $school
     * @return int Número de cuotas generadas
     */
    public function generateMonthlyDuesForSchool(School $school): int
    {
        $activeFee = MembershipFee::where('school_id', $school->id)->where('is_active', true)->first();

        if (!$activeFee) {
            return 0;
        }

        // Filtramos colegiados activos y con perfil mensual
        $collegiates = Collegiate::where('school_id', $school->id)
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('billing_profile', 'mensual')
                      ->orWhereNull('billing_profile'); // retrocompatibilidad
            })
            ->get();

        $dueDate = Carbon::now()->endOfMonth(); // Vencimiento a fin de mes (o se podría usar billing_day)
        $count = 0;

        foreach ($collegiates as $collegiate) {
            // Evitar duplicados en el mismo mes
            $exists = CollegiateDue::where('collegiate_id', $collegiate->id)
                ->whereYear('due_date', $dueDate->year)
                ->whereMonth('due_date', $dueDate->month)
                ->exists();

            if (!$exists) {
                CollegiateDue::create([
                    'collegiate_id' => $collegiate->id,
                    'amount' => $activeFee->amount,
                    'due_date' => clone $dueDate,
                    'status' => 'pending'
                ]);
                
                // Si se generó deuda, el usuario pasa a no estar al día
                $collegiate->update(['is_fees_compliant' => false]);
                $count++;
            }
        }

        return $count;
    }
}

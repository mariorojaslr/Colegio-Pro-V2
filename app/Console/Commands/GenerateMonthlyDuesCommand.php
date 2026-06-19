<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Services\BillingService;

class GenerateMonthlyDuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-dues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera cuotas mensuales para los colegios que tengan facturación automática activa el día de hoy.';

    /**
     * Execute the console command.
     */
    public function handle(BillingService $billingService)
    {
        $todayDay = now()->day;

        $this->info("Buscando colegios con facturación automática configurada para el día {$todayDay}...");

        $schools = School::where('auto_billing_enabled', true)
                         ->where('billing_day', $todayDay)
                         ->where('is_active', true)
                         ->get();

        if ($schools->isEmpty()) {
            $this->info("No hay colegios programados para facturar el día de hoy.");
            return;
        }

        foreach ($schools as $school) {
            $this->info("Generando cuotas para el colegio: {$school->name}");
            $count = $billingService->generateMonthlyDuesForSchool($school);
            $this->info("  -> {$count} cuotas generadas.");
        }

        $this->info("Proceso de facturación masiva finalizado.");
    }
}

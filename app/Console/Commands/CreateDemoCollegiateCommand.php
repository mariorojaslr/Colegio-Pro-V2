<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use App\Models\Collegiate;
use App\Models\User;
use App\Models\CollegiateDue;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateDemoCollegiateCommand extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     *
     * @var string
     */
    protected $signature = 'demo:collegiate {--school=cotolar : Slug del colegio donde crear la demo} {--action=create : create o delete}';

    /**
     * Descripción del comando.
     *
     * @var string
     */
    protected $description = 'Crea o elimina un Colegiado de Demostración para pruebas del OWNER sin ensuciar la base de datos real.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle()
    {
        $schoolSlug = $this->option('school') ?? 'cotolar';
        $action = $this->option('action') ?? 'create';

        $school = School::where('slug', $schoolSlug)->first();
        if (!$school) {
            $this->error("No se encontró el colegio con slug: {$schoolSlug}");
            return 1;
        }

        $demoLegajo = 'DEMO';
        $demoEmail = 'demo.mario@cotolar.com';
        $demoPhone = '3804000000';
        $demoPin = '1234';

        // Buscar si ya existe
        $existingCollegiate = Collegiate::where('school_id', $school->id)
            ->where(function($q) use ($demoLegajo, $demoEmail) {
                $q->where('registration_number', $demoLegajo)
                  ->orWhere('email', $demoEmail);
            })->first();

        if ($action === 'delete') {
            if ($existingCollegiate) {
                if ($existingCollegiate->user_id) {
                    User::where('id', $existingCollegiate->user_id)->delete();
                }
                CollegiateDue::where('collegiate_id', $existingCollegiate->id)->delete();
                $existingCollegiate->delete();
                $this->info("¡Colegiado de prueba eliminado correctamente de {$school->name}!");
            } else {
                $this->warn("No había ningún colegiado de prueba activo en {$school->name}.");
            }
            return 0;
        }

        // Acción: CREATE / REFRESH
        $pinHash = Hash::make($demoPin);

        // 1. Crear o actualizar usuario
        $user = User::updateOrCreate(
            ['email' => $demoEmail],
            [
                'name' => 'Mario Rojas (Demostración)',
                'password' => $pinHash,
                'login_pin' => $pinHash,
                'role' => 'COLEGIADO',
                'school_id' => $school->id,
                'is_active' => true,
            ]
        );

        // 2. Crear o actualizar ficha de colegiado
        $collegiate = Collegiate::updateOrCreate(
            [
                'school_id' => $school->id,
                'registration_number' => $demoLegajo,
            ],
            [
                'user_id' => $user->id,
                'first_name' => 'Mario',
                'last_name' => 'Rojas (Demo)',
                'email' => $demoEmail,
                'phone' => $demoPhone,
                'login_pin' => $pinHash,
                'dni' => '99999999',
                'status' => 'active',
                'is_fees_compliant' => true,
                'is_ethics_compliant' => true,
                'is_fully_documented' => false,
            ]
        );

        // 3. Crear cuotas de ejemplo (1 pagada y 1 pendiente para ver los Vu-Meters)
        CollegiateDue::where('collegiate_id', $collegiate->id)->delete();

        CollegiateDue::create([
            'collegiate_id' => $collegiate->id,
            'amount' => 15000,
            'concept' => 'Cuota Mensual Enero',
            'due_date' => Carbon::now()->subMonth(),
            'status' => 'paid',
            'paid_at' => Carbon::now()->subDays(15),
            'due_type' => 'monthly',
        ]);

        CollegiateDue::create([
            'collegiate_id' => $collegiate->id,
            'amount' => 15000,
            'concept' => 'Cuota Mensual Febrero',
            'due_date' => Carbon::now()->addDays(10),
            'status' => 'pending',
            'due_type' => 'monthly',
        ]);

        $collegiate->updateComplianceStatus();

        $this->info("=================================================");
        $this->info("  COLEGIADO DE DEMOSTRACIÓN CREADO EXITOSAMENTE");
        $this->info("=================================================");
        $this->line("Colegio: <comment>{$school->name} (Slug: {$school->slug})</comment>");
        $this->line("Nombre: <comment>Mario Rojas (Demostración)</comment>");
        $this->line("N° de Legajo: <info>{$demoLegajo}</info>");
        $this->line("Teléfono Celular: <info>{$demoPhone}</info>");
        $this->line("Código PIN de 4 dígitos: <info>{$demoPin}</info>");
        $this->line("Correo Electrónico: <comment>{$demoEmail}</comment>");
        $this->info("=================================================");
        $this->line("Para probar desde el celular o navegador:");
        $this->line("1. Ingresa a la pantalla de login");
        $this->line("2. Escribe Legajo: 'DEMO' o Celular: '3804000000'");
        $this->line("3. Escribe PIN: '1234'");
        $this->line("4. ¡Listo! Vivirás la experiencia completa como socio.");
        $this->info("=================================================");

        return 0;
    }
}

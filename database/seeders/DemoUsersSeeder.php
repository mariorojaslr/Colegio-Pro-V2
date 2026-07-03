<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Collegiate;
use App\Models\CollegiateDue;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Iniciando la creación de usuarios y colegiados demo...");

        // Lista de colegios y sus prefijos correspondientes
        $tenants = [
            'abogados' => ['prefix' => 'abog', 'degree' => 'Abogado/a', 'reg_prefix' => 'ABO'],
            'arquitectos' => ['prefix' => 'arq', 'degree' => 'Arquitecto/a', 'reg_prefix' => 'ARQ'],
            'kinesiologo-school' => ['prefix' => 'kines', 'degree' => 'Kinesiólogo/a', 'reg_prefix' => 'KIN'],
            'trabajosocial' => ['prefix' => 'ts', 'degree' => 'Trabajador/a Social', 'reg_prefix' => 'TS']
        ];

        // Datos para generar personas
        $firstNames = ['Carlos', 'Maria', 'Juan', 'Ana', 'Luis', 'Laura', 'Diego', 'Sofia', 'Pedro', 'Lucia', 'Jorge', 'Elena', 'Miguel', 'Marta', 'Jose', 'Paula', 'Fernando', 'Carmen', 'Raul', 'Isabel', 'Martin', 'Gabriela', 'Andres', 'Felipe', 'Florencia', 'Esteban', 'Camila', 'Santiago', 'Valentina'];
        $lastNames = ['Gomez', 'Rodriguez', 'Fernandez', 'Lopez', 'Martinez', 'Gonzalez', 'Perez', 'Sanchez', 'Romero', 'Suarez', 'Diaz', 'Torres', 'Ruiz', 'Alonso', 'Blanco', 'Iglesias', 'Vidal', 'Molina', 'Garrido', 'Castro', 'Acuna', 'Ortiz', 'Silva', 'Cabrera', 'Sosa', 'Herrera', 'Medina', 'Vargas', 'Rojas', 'Flores'];
        
        $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martin', 'Independencia', 'Rosario Vera Penaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela'];
        $addresses = [
            'Av. Facundo Quiroga 450', 'Calle Rivadavia 123', 'Av. San Martin 880', 'Calle Belgrano 54', 
            'Av. Ortiz de Ocampo 1200', 'Calle Castro Barros 320', 'Av. Peron 77', 'Calle Pelagio B. Luna 89',
            'B° Centro Calle San Nicolas 45', 'Av. Alem 600', 'Calle Catamarca 150', 'Calle Alberdi 99'
        ];

        DB::beginTransaction();

        try {
            foreach ($tenants as $slug => $config) {
                // Buscar el colegio
                $school = School::where('slug', $slug)->first();
                if (!$school) {
                    $this->command->error("Colegio con slug '{$slug}' no encontrado en la base de datos. Saltando...");
                    continue;
                }

                $this->command->info("Creando 15 usuarios demo para: {$school->name}...");

                for ($i = 1; $i <= 15; $i++) {
                    // Seleccionar datos únicos para esta persona
                    $firstName = $firstNames[($i * 3) % count($firstNames)];
                    $lastName = $lastNames[($i * 7) % count($lastNames)];
                    
                    // Limpieza para el correo
                    $cleanFirstName = strtolower(str_replace(['á','é','í','ó','ú','ñ','í',' ','\''], ['a','e','i','o','u','n','i','',''], $firstName));
                    $cleanLastName = strtolower(str_replace(['á','é','í','ó','ú','ñ','í',' ','\''], ['a','e','i','o','u','n','i','',''], $lastName));
                    
                    $email = "demo.{$config['prefix']}.{$cleanFirstName}.{$cleanLastName}@colegiopro.com";
                    $name = "{$firstName} {$lastName}";

                    // 1. Crear el usuario
                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => Hash::make('demo1234'),
                            'role' => 'COLEGIADO',
                            'school_id' => $school->id,
                            'is_active' => 1
                        ]
                    );

                    // 2. Determinar estado y departamentos
                    $status = 'Activo';
                    if ($i === 5) {
                        $status = 'Suspendido por Mora';
                    } elseif ($i === 10) {
                        $status = 'Baja Temporal';
                    }

                    $depto = $departamentos[($i) % count($departamentos)];
                    $address = $addresses[($i) % count($addresses)] . ", " . $depto;
                    $dni = strval(30000000 + ($school->id * 100000) + ($i * 1111));
                    $regNumber = "{$config['reg_prefix']}-" . str_pad($i + 1000, 4, '0', STR_PAD_LEFT);
                    $avatarUrl = "https://i.pravatar.cc/150?u=" . urlencode($email);

                    // 3. Crear colegiado
                    $collegiate = Collegiate::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'school_id' => $school->id,
                            'registration_number' => $regNumber,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'dni' => $dni,
                            'phone' => '3824 ' . (400000 + ($i * 999)),
                            'avatar_url' => $avatarUrl,
                            'birth_date' => Carbon::now()->subYears(rand(25, 60))->subDays(rand(1, 365))->format('Y-m-d'),
                            'address' => $address,
                            'city' => $depto, // Usamos city para guardar el departamento
                            'degree' => $config['degree'],
                            'workplaces_info' => 'Estudio / Consultorio Particular',
                            'practicing_since_year' => Carbon::now()->subYears(rand(2, 20))->year,
                            'is_fees_compliant' => ($status === 'Activo'),
                            'is_ethics_compliant' => true,
                            'is_fully_documented' => true,
                            'status' => $status,
                        ]
                    );

                    // 4. Crear cuotas mensuales (5 cuotas)
                    // Eliminar previas
                    CollegiateDue::where('collegiate_id', $collegiate->id)->delete();

                    for ($d = 0; $d < 5; $d++) {
                        $month = Carbon::now()->subMonths($d);
                        // El 5to usuario (Suspendido por Mora) no tiene cuotas pagadas
                        // El resto tiene pagadas las cuotas anteriores
                        $isPaid = ($status === 'Activo' && $d > 1) || ($status === 'Activo' && rand(1, 100) > 30);
                        if ($status === 'Suspendido por Mora') {
                            $isPaid = false; // debe todo
                        }

                        CollegiateDue::create([
                            'collegiate_id' => $collegiate->id,
                            'amount' => 6000.00,
                            'concept' => 'Cuota Mensual ' . $month->translatedFormat('F Y'),
                            'due_type' => 'mensualidad',
                            'due_date' => $month->copy()->endOfMonth(),
                            'status' => $isPaid ? 'paid' : ($month->isPast() ? 'overdue' : 'pending'),
                            'paid_at' => $isPaid ? $month->copy()->addDays(rand(1, 20)) : null,
                        ]);
                    }
                }

                // Asegurar suscripción activa para evitar el estado "SIN PLAN"
                if (!$school->activeSubscription) {
                    $plan = \App\Models\SubscriptionPlan::first();
                    if ($plan) {
                        \App\Models\Subscription::create([
                            'school_id' => $school->id,
                            'subscription_plan_id' => $plan->id,
                            'status' => 'active',
                            'starts_at' => Carbon::now(),
                            'expires_at' => Carbon::now()->addYear(),
                        ]);
                    }
                }
            }

            DB::commit();
            $this->command->info("¡Poblado de datos demo completado exitosamente!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error durante el poblado: " . $e->getMessage());
        }
    }
}

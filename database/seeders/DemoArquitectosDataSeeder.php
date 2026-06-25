<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Collegiate;
use App\Models\CollegiateDue;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DemoArquitectosDataSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 2; // Arquitectos

        $this->command->info("Creating 50 demo users for Arquitectos (Without Faker)...");

        // Hardcoded arrays to replace Faker
        $firstNames = ['Carlos', 'Maria', 'Juan', 'Ana', 'Luis', 'Laura', 'Diego', 'Sofia', 'Pedro', 'Lucia', 'Jorge', 'Elena', 'Miguel', 'Marta', 'Jose', 'Paula', 'Fernando', 'Carmen', 'Raul', 'Isabel'];
        $lastNames = ['Gomez', 'Rodriguez', 'Fernandez', 'Lopez', 'Martinez', 'Gonzalez', 'Perez', 'Sanchez', 'Romero', 'Suarez', 'Diaz', 'Torres', 'Ruiz', 'Alonso', 'Blanco', 'Iglesias', 'Vidal', 'Molina', 'Garrido', 'Castro'];
        $addresses = ['Av. Siempreviva 742', 'Calle Falsa 123', 'Av. del Libertador 1000', 'Calle Principal 45', 'Bulevar Central 88'];
        $companies = ['Estudio Alfa', 'Constructora Beta', 'Diseños Gamma', 'Arquitectura Moderna', 'Espacios Creativos', 'Independiente'];

        // Start transaction for speed
        DB::beginTransaction();

        try {
            for ($i = 0; $i < 50; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $email = "arquitecto{$i}@demo.com";
                
                // 1. Create User
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => "$firstName $lastName",
                        'password' => Hash::make('password'),
                        'role' => 'COLEGIADO',
                        'school_id' => $schoolId,
                        'is_active' => 1
                    ]
                );

                // 2. Create Collegiate
                $statusOptions = ['Activo', 'Activo', 'Activo', 'Suspendido por Mora', 'Baja Voluntaria'];
                $status = $statusOptions[array_rand($statusOptions)];
                
                // Random avatar
                $avatarUrl = "https://i.pravatar.cc/150?u=" . urlencode($email);

                $collegiate = Collegiate::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $schoolId,
                        'registration_number' => 'ARQ-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'dni' => str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                        'phone' => '11 ' . rand(1000, 9999) . '-' . rand(1000, 9999),
                        'avatar_url' => $avatarUrl,
                        'birth_date' => Carbon::createFromDate(rand(1960, 1995), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                        'address' => $addresses[array_rand($addresses)],
                        'degree' => 'Arquitecto/a',
                        'workplaces_info' => $companies[array_rand($companies)],
                        'practicing_since_year' => rand(1990, 2023),
                        'is_fees_compliant' => ($status === 'Activo'),
                        'is_fully_documented' => (rand(1, 100) > 20), // 80% chance true
                        'status' => $status,
                    ]
                );

                // 3. Create Collegiate Dues (Movements)
                CollegiateDue::where('collegiate_id', $collegiate->id)->delete();

                $dueCount = rand(3, 10);
                for ($d = 0; $d < $dueCount; $d++) {
                    $month = Carbon::now()->subMonths($d);
                    $isPaid = $d > 1 ? true : (rand(1, 100) > 40); // older dues paid, newer might be pending

                    CollegiateDue::create([
                        'collegiate_id' => $collegiate->id,
                        'amount' => 5000.00,
                        'concept' => 'Cuota Mensual ' . $month->translatedFormat('F Y'),
                        'due_type' => 'mensualidad',
                        'due_date' => $month->copy()->endOfMonth(),
                        'status' => $isPaid ? 'paid' : ($month->isPast() ? 'overdue' : 'pending'),
                        'paid_at' => $isPaid ? $month->copy()->addDays(rand(1, 20)) : null,
                    ]);
                }

                // 4. Create some Tickets
                if (rand(1, 100) <= 30) { 
                    $categories = ['Soporte', 'Pagos', 'General'];
                    $priorities = ['low', 'medium', 'high'];
                    $statuses = ['open', 'in_progress', 'resolved', 'closed'];

                    $ticket = Ticket::create([
                        'school_id' => $schoolId,
                        'user_id' => $user->id,
                        'subject' => "Consulta de $firstName",
                        'status' => $statuses[array_rand($statuses)],
                        'priority' => $priorities[array_rand($priorities)],
                        'category' => $categories[array_rand($categories)],
                    ]);
                    
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $user->id,
                        'message' => "Hola, tengo una consulta sobre mi estado actual. Quedo atento.",
                    ]);
                }
            }

            DB::commit();
            $this->command->info("50 Demo users and movements created successfully without Faker!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}

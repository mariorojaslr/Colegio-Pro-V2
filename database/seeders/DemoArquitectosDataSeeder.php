<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Collegiate;
use App\Models\CollegiateDue;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DemoArquitectosDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_AR');
        $schoolId = 2; // Arquitectos

        $this->command->info("Creating 50 demo users for Arquitectos...");

        // Start transaction for speed
        DB::beginTransaction();

        try {
            for ($i = 0; $i < 50; $i++) {
                $firstName = $faker->firstName;
                $lastName = $faker->lastName;
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
                $status = $faker->randomElement($statusOptions);
                
                // Random avatar
                $avatarUrl = "https://i.pravatar.cc/150?u=" . urlencode($email);

                $collegiate = Collegiate::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $schoolId,
                        'registration_number' => 'ARQ-' . $faker->unique()->numerify('####'),
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'dni' => $faker->unique()->numerify('########'),
                        'phone' => $faker->phoneNumber,
                        'avatar_url' => $avatarUrl,
                        'birth_date' => $faker->date('Y-m-d', '2000-01-01'),
                        'address' => $faker->address,
                        'degree' => 'Arquitecto/a',
                        'workplaces_info' => $faker->company,
                        'practicing_since_year' => $faker->numberBetween(1990, 2023),
                        'is_fees_compliant' => ($status === 'Activo'),
                        'is_fully_documented' => $faker->boolean(80),
                        'status' => $status,
                    ]
                );

                // 3. Create Collegiate Dues (Movements)
                CollegiateDue::where('collegiate_id', $collegiate->id)->delete();

                $dueCount = $faker->numberBetween(3, 10);
                for ($d = 0; $d < $dueCount; $d++) {
                    $month = Carbon::now()->subMonths($d);
                    $isPaid = $d > 1 ? true : $faker->boolean(60); 

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
                if ($faker->boolean(30)) { 
                    $ticket = Ticket::create([
                        'school_id' => $schoolId,
                        'user_id' => $user->id,
                        'subject' => $faker->sentence(4),
                        'status' => $faker->randomElement(['open', 'in_progress', 'resolved', 'closed']),
                        'priority' => $faker->randomElement(['low', 'medium', 'high']),
                        'category' => $faker->randomElement(['Soporte', 'Pagos', 'General']),
                    ]);
                    
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $user->id,
                        'message' => $faker->paragraph,
                    ]);
                }
            }

            DB::commit();
            $this->command->info("50 Demo users and movements created successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }
}

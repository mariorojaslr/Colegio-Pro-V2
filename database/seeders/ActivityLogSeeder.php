<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = \App\Models\User::where('role', 'OWNER')->first();
        $schools = \App\Models\School::all();

        if ($owner) {
            // Log 1: Login Owner
            \App\Models\ActivityLog::create([
                'user_id' => $owner->id,
                'action' => 'login',
                'description' => 'Inicio de sesión exitoso en Panel Global',
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
            ]);

            foreach ($schools as $school) {
                // Log 2: Impersonación
                \App\Models\ActivityLog::create([
                    'user_id' => $owner->id,
                    'action' => 'impersonate',
                    'description' => "Acceso omnisciente a la institución: " . $school->name,
                    'school_id' => $school->id,
                    'ip_address' => '192.168.1.1',
                ]);

                $admin = $school->users()->where('role', 'ADMIN_COLEGIO')->first();
                if ($admin) {
                     // Log 3: Admin login
                     \App\Models\ActivityLog::create([
                        'user_id' => $admin->id,
                        'school_id' => $school->id,
                        'action' => 'login',
                        'description' => 'Inicio de sesión corporativo',
                        'ip_address' => '201.12.33.' . rand(1, 254),
                    ]);

                    // Log 4: Plan check
                    \App\Models\ActivityLog::create([
                        'user_id' => $admin->id,
                        'school_id' => $school->id,
                        'action' => 'view_billing',
                        'description' => 'Revisión de consumo y facturación',
                        'ip_address' => '201.12.33.' . rand(1, 254),
                    ]);
                }
            }

            // Log 5: Create School
            \App\Models\ActivityLog::create([
                'user_id' => $owner->id,
                'action' => 'create_school',
                'description' => 'Alta de nueva institución: Colegio Sagrado Corazón',
                'ip_address' => '192.168.1.1',
            ]);
        }
    }
}

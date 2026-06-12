<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantIsolationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar la empresa Demo
        $demoSchool = \App\Models\School::where('slug', 'demo')->first();
        if (!$demoSchool) {
            $demoSchool = \App\Models\School::first();
        }
        
        if ($demoSchool) {
            $demoSchool->update([
                'name' => 'Colegio de Prueba (Demo)',
                'slug' => 'demo',
                'logo' => null // Sin logo de Cotolar
            ]);
        }

        // 2. Crear el colegio real de Cotolar
        $cotolarSchool = \App\Models\School::firstOrCreate(
            ['slug' => 'cotolar'],
            [
                'name' => 'Cotolar',
                'logo' => 'images/logo_cotolar.jpeg',
                'member_singular' => 'Terapeuta Ocupacional',
                'member_plural' => 'Terapeutas Ocupacionales',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#8b5cf6',
                'is_active' => true,
                'plan_category' => 'PREMIUM',
                'locale' => 'es',
                'has_academy' => true
            ]
        );

        $cotolarSchool->update(['name' => 'Cotolar']);

        // 3. Crear el usuario Administrador de Cotolar
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@cotolar.com'],
            [
                'name' => 'Administrador Cotolar',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'ADMIN_COLEGIO',
                'school_id' => $cotolarSchool->id,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Aislamiento de inquilinos (Tenants) completado: Demo y Cotolar han sido separados.');
    }
}

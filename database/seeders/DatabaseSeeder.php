<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear el OWNER del sistema
        \App\Models\User::factory()->create([
            'name' => 'Owner Colegio Pro',
            'email' => 'admin@colegiopro.com',
            'password' => bcrypt('password'),
            'role' => 'OWNER',
            'school_id' => null,
        ]);

        // 2. Crear Colegios de prueba (Tenants)
        $school1 = \App\Models\School::create([
            'name' => 'Colegio de Abogados',
            'slug' => 'abogados',
            'primary_color' => '#1e3a8a',
            'secondary_color' => '#d4af37',
            'plan_category' => 'enterprise',
            'logo' => 'images/tenants/logo-abogados-redondo.png',
        ]);

        $school2 = \App\Models\School::create([
            'name' => 'Colegio de Arquitectos',
            'slug' => 'arquitectos',
            'primary_color' => '#374151',
            'secondary_color' => '#fbbf24',
            'plan_category' => 'professional',
        ]);

        // 3. Crear Administradores para cada Colegio
        \App\Models\User::factory()->create([
            'name' => 'Admin Abogados',
            'email' => 'admin@abogados.com',
            'school_id' => $school1->id,
            'role' => 'ADMIN_COLEGIO',
        ]);
        // 4. Cargar Datos de Demostración Realistas (Casos Críticos)
        $this->call(DemoColegiadoSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'name' => 'Plan Starter',
                'slug' => 'starter',
                'price' => 25000.00,
                'max_users' => 50,
                'max_storage' => 10,
                'features' => json_encode(['Soporte por Ticket', 'Academy Básica', 'Gestión de Legajos']),
                'created_at' => now(),
            ],
            [
                'name' => 'Plan Profesional',
                'slug' => 'professional',
                'price' => 45000.00,
                'max_users' => 200,
                'max_storage' => 100,
                'features' => json_encode(['Soporte Prioritario', 'Padrón Pro', 'Academy Avanzada', 'Certificación Automatizada']),
                'created_at' => now(),
            ],
            [
                'name' => 'Plan Institucional',
                'slug' => 'institutional',
                'price' => 85000.00,
                'max_users' => 1000,
                'max_storage' => 500,
                'features' => json_encode(['Bunny.net Infinito', 'Multi-Escuelas', 'Auditoría Completa', 'API Access']),
                'created_at' => now(),
            ],
            [
                'name' => 'Plan Legacy / Silver',
                'slug' => 'legacy',
                'price' => 150000.00,
                'max_users' => 0, // Ilimitado
                'max_storage' => 0, // Ilimitado
                'features' => json_encode(['White Label Completo', 'Consultoría Senior', 'Infraestructura Dedicada']),
                'created_at' => now(),
            ]
        ]);
    }
}

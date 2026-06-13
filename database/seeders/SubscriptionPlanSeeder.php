<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivamos restricciones de claves foráneas para limpiar la tabla
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\SubscriptionPlan::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $plans = [
            [
                'name' => 'Plan Base (Inicial)',
                'slug' => 'base',
                'price' => 100000.00,
                'price_international' => 29.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 250,
                'max_storage' => 5,
                'max_traffic' => 50,
                'max_files' => 2500,
                'max_images' => 1000,
                'max_streaming' => 0, // No streaming
                'is_one_time' => false,
                'features' => ['Soporte estándar', 'Gestión de matriculados', 'Notificaciones básicas', 'Capacidad 250 colegiados'],
            ],
            [
                'name' => 'Plan Profesional',
                'slug' => 'professional',
                'price' => 200000.00,
                'price_international' => 59.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 500,
                'max_storage' => 10,
                'max_traffic' => 100,
                'max_files' => 5000,
                'max_images' => 2000,
                'max_streaming' => 0, // No streaming
                'is_one_time' => false,
                'features' => ['Soporte prioritario', 'Módulo de eventos', 'Personalización de marca', 'Capacidad 500 colegiados'],
            ],
            [
                'name' => 'Plan Premium',
                'slug' => 'premium',
                'price' => 300000.00,
                'price_international' => 89.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 1000,
                'max_storage' => 20,
                'max_traffic' => 200,
                'max_files' => 10000,
                'max_images' => 4000,
                'max_streaming' => 0, // No streaming
                'is_one_time' => false,
                'features' => ['Soporte 24/7 VIP', 'Gestión de cobros automática', 'Asistente IA Avanzado', 'Capacidad 1000 colegiados'],
            ],
            [
                'name' => 'Plan Enterprise',
                'slug' => 'enterprise',
                'price' => 500000.00,
                'price_international' => 149.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 3000,
                'max_storage' => 60,
                'max_traffic' => 600,
                'max_files' => 30000,
                'max_images' => 12000,
                'max_streaming' => 0, // No streaming
                'is_one_time' => false,
                'features' => ['Todo Ilimitado', 'Consultoría técnica directa', 'Visión Omnisciente Expandida', 'Capacidad 3000 colegiados'],
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\SubscriptionPlan::create($plan);
        }
    }
}

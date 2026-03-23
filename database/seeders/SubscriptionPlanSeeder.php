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
                'price' => 50000.00,
                'price_international' => 19.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 150,
                'max_storage' => 15,
                'max_traffic' => 100,
                'max_files' => 2000,
                'max_images' => 1000,
                'max_streaming' => 60,
                'is_one_time' => false,
                'features' => ['Soporte estándar', 'Gestión de matriculados', 'Notificaciones básicas', 'Capacidad 150 usuarios'],
            ],
            [
                'name' => 'Plan Profesional',
                'slug' => 'professional',
                'price' => 95000.00,
                'price_international' => 49.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 600,
                'max_storage' => 100,
                'max_traffic' => 500,
                'max_files' => 10000,
                'max_images' => 5000,
                'max_streaming' => 300,
                'is_one_time' => false,
                'features' => ['Soporte prioritario', 'Módulo de eventos', 'Streaming Bunny.net (Básico)', 'Personalización de marca', 'Capacidad 600 usuarios'],
            ],
            [
                'name' => 'Plan Premium',
                'slug' => 'premium',
                'price' => 185000.00,
                'price_international' => 99.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 2500,
                'max_storage' => 300,
                'max_traffic' => 2000,
                'max_files' => 50000,
                'max_images' => 20000,
                'max_streaming' => 1200,
                'is_one_time' => false,
                'features' => ['Soporte 24/7 VIP', 'Streaming Bunny.net (Alta Calidad)', 'Gestión de cobros automática', 'Asistente IA Avanzado', 'Capacidad 2500 usuarios'],
            ],
            [
                'name' => 'Plan Enterprise',
                'slug' => 'enterprise',
                'price' => 350000.00,
                'price_international' => 249.99,
                'currency_international' => 'USD',
                'interval' => 'monthly',
                'max_users' => 99999,
                'max_storage' => 1500,
                'max_traffic' => 10000,
                'max_files' => 200000,
                'max_images' => 100000,
                'max_streaming' => 5000,
                'is_one_time' => false,
                'features' => ['Todo Ilimitado', 'Consultoría técnica directa', 'Servidores dedicados Bunny.net', 'Visión Omnisciente Expandida', 'Capacidad Ilimitada Enterprise'],
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\SubscriptionPlan::create($plan);
        }
    }
}

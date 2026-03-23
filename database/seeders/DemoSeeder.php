<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\School;
use App\Models\User;
use App\Models\Collegiate;
use App\Models\ComplianceRequirement;
use App\Models\Amenity;
use App\Models\AmenityBooking;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('es_ES');

        // 1. Limpieza de Datos Previos (Evitar Duplicados)
        $oldSchool = School::where('slug', 'demo')->first();
        if ($oldSchool) {
            // Eliminar usuarios asociados (excluyendo el admin si queremos preservarlo, pero mejor limpiar todo)
            User::where('school_id', $oldSchool->id)->delete();
            $oldSchool->delete();
        }

        // 2. Crear Colegio de Prueba (Slug: demo)
        $school = School::create([
            'name' => 'Colegio de Prueba Profesional',
            'slug' => 'demo',
            'primary_color' => '#0f172a',
            'secondary_color' => '#f59e0b',
            'is_active' => true,
        ]);

        // 3. Crear Administrador del Colegio
        $admin = User::create([
            'name' => 'Administrador Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN_COLEGIO',
            'school_id' => $school->id,
        ]);

        // 4. Crear Requisitos de Cumplimiento
        $reqs = [
            ['name' => 'DNI Frente y Dorso', 'type' => 'permanent', 'expiry_frequency' => 'none'],
            ['name' => 'Título Habilitante', 'type' => 'permanent', 'expiry_frequency' => 'none'],
            ['name' => 'Antecedentes Penales', 'type' => 'perentory', 'expiry_frequency' => 'semester'],
            ['name' => 'Seguro Profesional', 'type' => 'perentory', 'expiry_frequency' => 'year'],
        ];

        foreach ($reqs as $r) {
            ComplianceRequirement::create(
                array_merge($r, ['school_id' => $school->id, 'is_mandatory' => true])
            );
        }

        // 5. Crear Amenidades
        $amenitiesData = [
            ['name' => 'Salón Multiuso', 'icon' => 'bi-buildings', 'base_price' => 15000],
            ['name' => 'Cancha de Pádel', 'icon' => 'bi-trophy', 'base_price' => 5000],
            ['name' => 'Piscina Olímpica', 'icon' => 'bi-water', 'base_price' => 8000, 'is_seasonal' => true, 'seasonal_price' => 12000],
        ];

        foreach ($amenitiesData as $ad) {
            Amenity::create(
                array_merge($ad, ['school_id' => $school->id, 'is_active' => true, 'has_calendar' => true, 'capacity' => 20])
            );
        }

        $amenities = Amenity::where('school_id', $school->id)->get();

        // 6. Generar 150 Colegiados
        for ($i = 0; $i < 150; $i++) {
            $isHabilitado = $faker->boolean(70);

            $email = "demo_user_{$i}@example.com";
            
            $collegiateUser = User::create([
                'name' => $faker->name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'USER',
                'school_id' => $school->id,
            ]);

            $collegiate = Collegiate::create([
                'school_id' => $school->id,
                'user_id' => $collegiateUser->id,
                'registration_number' => "MAT-" . (2000 + $i),
                'first_name' => explode(' ', $collegiateUser->name)[0],
                'last_name' => explode(' ', $collegiateUser->name)[1] ?? 'Doe',
                'email' => $collegiateUser->email,
                'dni' => $faker->numberBetween(20000000, 45000000),
                'phone' => $faker->phoneNumber,
                'is_ethics_compliant' => $isHabilitado ? true : $faker->boolean(80),
                'is_fees_compliant' => $isHabilitado ? true : $faker->boolean(60),
                'is_fully_documented' => $isHabilitado ? true : $faker->boolean(50),
            ]);

            // Crear algunas reservas aleatorias
            if ($faker->boolean(40)) {
                AmenityBooking::create([
                    'amenity_id' => $amenities->random()->id,
                    'collegiate_id' => $collegiate->id,
                    'booking_date' => $faker->dateTimeBetween('now', '+1 month'),
                    'slot_time' => 'Tarde (14:00 - 18:00)',
                    'price_paid' => 5000,
                    'status' => 'confirmed',
                ]);
            }
        }
    }
}

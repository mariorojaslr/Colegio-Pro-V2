<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class NewTenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Colegio de Arquitectos
        School::updateOrCreate(
            ['slug' => 'arquitectos'],
            [
                'name' => "CAPLaR - Colegio de Arquitectos de La Rioja",
                'primary_color' => "#000000", // Black from their logo
                'secondary_color' => "#ffffff", // White
                'is_active' => true,
                'plan_category' => 'basic'
            ]
        );

        // Consejo Profesional de Trabajo Social
        School::updateOrCreate(
            ['slug' => 'trabajosocial'],
            [
                'name' => "Consejo Profesional de Trabajo Social La Rioja",
                'primary_color' => "#1E3A8A", // Blue from the logo
                'secondary_color' => "#DC2626", // Red from the logo
                'is_active' => true,
                'plan_category' => 'basic'
            ]
        );
    }
}

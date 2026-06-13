<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class UpdateLogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $updates = [
            'arquitectos' => 'images/logos/new_arq_logo.png',
            'trabajosocial' => 'images/logos/ts_logo.png',
            'prueba' => 'images/logos/new_prueba_logo.png'
        ];

        foreach ($updates as $slug => $logoPath) {
            $school = School::where('slug', $slug)->first();
            if ($school) {
                $school->logo = $logoPath;
                $school->save();
            }
        }
    }
}

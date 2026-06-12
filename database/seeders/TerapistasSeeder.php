<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Slider;
use App\Models\SliderItem;

class TerapistasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Crear Escuela de Terapistas
        $school = School::updateOrCreate(
            ['slug' => 'terapistas'],
            [
                'name' => 'Colegio de Terapistas Ocupacionales de La Rioja',
                'primary_color' => '#1e3a5f',
                'secondary_color' => '#d4af37',
                'tertiary_color' => '#0f172a',
                'logo' => '/images/logo_terapistas.jpeg'
            ]
        );

        // 2. Crear Owner/Admin (si no existe)
        $admin = User::firstOrCreate(
            ['email' => 'admin@terapistas.org'],
            [
                'name' => 'Administrador Terapistas',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'school_id' => $school->id
            ]
        );

        // 3. Crear Menú Público
        $menu = Menu::firstOrCreate([
            'school_id' => $school->id,
            'location' => 'header',
        ], [
            'name' => 'Menú Principal Terapistas',
            'is_active' => true
        ]);

        if ($menu->items()->count() === 0) {
            MenuItem::insert([
                ['menu_id' => $menu->id, 'title' => 'Institucional', 'url' => '#institucional', 'order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['menu_id' => $menu->id, 'title' => 'Ejercicio Profesional', 'url' => '#ejercicio', 'order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['menu_id' => $menu->id, 'title' => 'Contacto', 'url' => '#contacto', 'order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 4. Crear Slider Hero
        $slider = Slider::firstOrCreate([
            'school_id' => $school->id,
            'name' => 'Hero Terapistas',
        ], [
            'is_active' => true
        ]);

        if ($slider->items()->count() === 0) {
            SliderItem::create([
                'slider_id' => $slider->id,
                'title' => 'Excelencia y Ética en la Terapia Ocupacional',
                'description' => 'Promovemos el desarrollo científico y profesional.',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d',
                'link' => '/login',
                'order' => 1
            ]);
        }
    }
}

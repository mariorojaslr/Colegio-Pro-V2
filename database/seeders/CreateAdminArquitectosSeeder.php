<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminArquitectosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Arquitectos',
                'password' => Hash::make('12345678'),
                'role' => 'ADMIN_COLEGIO',
                'school_id' => 2,
                'is_active' => 1
            ]
        );
        
        $this->command->info("Usuario admin@gmail.com para Arquitectos creado exitosamente.");
    }
}

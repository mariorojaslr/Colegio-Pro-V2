<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\User;
use App\Models\Collegiate;
use App\Models\BoardMember;

return new class extends Migration
{
    /**
     * Ejecuta la migración para garantizar que las 14 autoridades de COTOLAR
     * tengan su usuario creado, vinculado a su legajo/matrícula, con rol ADMIN_COLEGIO
     * y con su contraseña individual asignada.
     */
    public function up(): void
    {
        // Buscamos la institución COTOLAR (Colegio de Terapistas Ocupacionales de La Rioja)
        $school = School::where('slug', 'cotolar')->first();

        if (!$school) {
            return;
        }

        // Nómina oficial de las 14 autoridades de COTOLAR con sus matrículas, correos y contraseñas individuales
        $authorities = [
            // COMISIÓN DIRECTIVA
            [
                'name' => 'Lic. Maria Silvia Arce',
                'position' => 'Presidente',
                'matricula' => '070',
                'matricula_sin_cero' => '70',
                'email' => 'mariasilviaarce2@gmail.com',
                'password' => 'Silvia-2026',
            ],
            [
                'name' => 'Lic. Gabriela Mercedes Avila',
                'position' => 'Vicepresidente',
                'matricula' => '63',
                'matricula_sin_cero' => '63',
                'email' => 'gabytolr61@gmail.com',
                'password' => 'Gabriela-2026',
            ],
            [
                'name' => 'Lic. María Soledad Agüero',
                'position' => 'Secretaria',
                'matricula' => '200',
                'matricula_sin_cero' => '200',
                'email' => 'soledad.aguero@cotolar.com',
                'password' => 'Soledad-2026',
            ],
            [
                'name' => 'Lic. María Luz García Pereyra',
                'position' => 'Tesorera',
                'matricula' => '215',
                'matricula_sin_cero' => '215',
                'email' => 'luz.garcia@cotolar.com',
                'password' => 'Luz-2026',
            ],
            [
                'name' => 'Lic. Aldana Rojas Cabrera',
                'position' => '1er Vocal',
                'matricula' => '250',
                'matricula_sin_cero' => '250',
                'email' => 'torojosaldana@gmail.com',
                'password' => 'Aldana-2026',
            ],
            [
                'name' => 'Lic. Marcela Beatriz Mercado',
                'position' => '2do Vocal',
                'matricula' => '088',
                'matricula_sin_cero' => '88',
                'email' => 'toenaccionlr@gmail.com',
                'password' => 'Marcela-2026',
            ],
            [
                'name' => 'Lic. María Esther Rodríguez Aguirre',
                'position' => 'Vocal Suplente',
                'matricula' => '237',
                'matricula_sin_cero' => '237',
                'email' => 'esther.rodriguez@cotolar.com',
                'password' => 'Esther-2026',
            ],
            // TRIBUNAL DE ÉTICA Y DISCIPLINA
            [
                'name' => 'Lic. Pablo Daniel Barrionuevo',
                'position' => 'Presidente',
                'matricula' => '192',
                'matricula_sin_cero' => '192',
                'email' => 'barrionuevopablodaniel842@gmail.com',
                'password' => 'Pablo-2026',
            ],
            [
                'name' => 'Lic. Antonia Margarita Álvarez',
                'position' => '1er Vocal',
                'matricula' => '141',
                'matricula_sin_cero' => '141',
                'email' => 'margalvarez_576@hotmail.com',
                'password' => 'Margarita-2026',
            ],
            [
                'name' => 'Lic. Karen Daniela Villarroel',
                'position' => '2do Vocal',
                'matricula' => '220',
                'matricula_sin_cero' => '220',
                'email' => 'karen.villarroel@cotolar.com',
                'password' => 'Karen-2026',
            ],
            [
                'name' => 'Lic. María Eva González',
                'position' => '1er Vocal Suplente',
                'matricula' => '68',
                'matricula_sin_cero' => '68',
                'email' => 'eva.gonzalez@cotolar.com',
                'password' => 'Eva-2026',
            ],
            [
                'name' => 'Lic. Érica Evangelina Corzo',
                'position' => '2do Vocal Suplente',
                'matricula' => '128',
                'matricula_sin_cero' => '128',
                'email' => 'erica.corzo@cotolar.com',
                'password' => 'Erica-2026',
            ],
            // COMISIÓN REVISORA DE CUENTAS
            [
                'name' => 'Lic. Karina Andrea Arias',
                'position' => 'Titular',
                'matricula' => '99',
                'matricula_sin_cero' => '99',
                'email' => 'karina.arias.claude@gmail.com',
                'password' => 'Karina-12345',
            ],
            [
                'name' => 'Lic. Mónica Sandra Zalazar',
                'position' => 'Suplente',
                'matricula' => '180',
                'matricula_sin_cero' => '180',
                'email' => 'monicazlazar@gmail.com',
                'password' => 'Monica-2026',
            ],
        ];

        foreach ($authorities as $item) {
            // Buscamos la ficha del matriculado en COTOLAR aceptando con o sin cero inicial
            $collegiate = Collegiate::where('school_id', $school->id)
                ->where(function ($q) use ($item) {
                    $q->where('registration_number', $item['matricula'])
                      ->orWhere('registration_number', $item['matricula_sin_cero'])
                      ->orWhere('registration_number', '0' . $item['matricula_sin_cero']);
                })
                ->first();

            // Determinamos el correo a utilizar (respetando si el colegiado ya configuró uno propio válido)
            $targetEmail = $item['email'];
            if ($collegiate && !empty($collegiate->email) && filter_var($collegiate->email, FILTER_VALIDATE_EMAIL)) {
                $targetEmail = $collegiate->email;
            }

            // Buscamos si ya existe el usuario por ID vinculado o por email
            $user = null;
            if ($collegiate && $collegiate->user_id) {
                $user = User::find($collegiate->user_id);
            }
            if (!$user) {
                $user = User::where('email', $targetEmail)->orWhere('email', $item['email'])->first();
            }

            if ($user) {
                // Actualizamos su rol a ADMIN_COLEGIO y asignamos su contraseña individual conocida
                $user->role = 'ADMIN_COLEGIO';
                $user->school_id = $school->id;
                $user->password = Hash::make($item['password']);
                $user->is_active = true;
                $user->save();
            } else {
                // Creamos el usuario con rol ADMIN_COLEGIO para esta autoridad
                $user = User::create([
                    'name' => $item['name'],
                    'email' => $targetEmail,
                    'password' => Hash::make($item['password']),
                    'role' => 'ADMIN_COLEGIO',
                    'school_id' => $school->id,
                    'is_active' => true,
                ]);
            }

            // Vinculamos la ficha del matriculado con su usuario administrador
            if ($collegiate) {
                $collegiate->user_id = $user->id;
                if (empty($collegiate->email)) {
                    $collegiate->email = $user->email;
                }
                $collegiate->save();

                // Vinculamos también el registro en board_members para trazabilidad completa
                BoardMember::where('school_id', $school->id)
                    ->where('name', $item['name'])
                    ->update(['collegiate_id' => $collegiate->id]);
            }
        }
    }

    /**
     * Revierte la migración sin eliminar usuarios ni datos (cumpliendo la regla de oro).
     */
    public function down(): void
    {
        // No se eliminan usuarios ni roles para preservar la integridad de datos
    }
};

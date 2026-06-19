<?php

namespace App\Imports;

use App\Models\Collegiate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CollegiatesImport implements ToModel, WithHeadingRow, WithChunkReading
{
    protected $school_id;

    public function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public function model(array $row)
    {
        // Ignorar filas vacías o sin matrícula
        if (empty($row['matricula'])) {
            return null;
        }

        try {
            $firstName = $row['nombre'] ?? $row['nombres'] ?? 'S/N';
            $lastName = $row['apellido'] ?? $row['apellidos'] ?? 'S/A';
            $email = $row['email'] ?? null;
            $dni = $row['dni'] ?? null;

            // Find or create User
            $user = User::where('email', $email)->orWhere('document_number', $dni)->first();
            if (!$user && $email) {
                $user = User::create([
                    'name' => $firstName . ' ' . $lastName,
                    'email' => $email,
                    'password' => Hash::make($dni ?? '12345678'),
                    'role' => 'COLEGIADO',
                    'school_id' => $this->school_id,
                    'document_number' => $dni
                ]);
            }

            return Collegiate::updateOrCreate(
                [
                    'registration_number' => $row['matricula'],
                    'school_id'           => $this->school_id
                ],
                [
                    'user_id'    => $user ? $user->id : null,
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'dni'        => $dni,
                    'email'      => $email,
                    'phone'      => $row['telefono'] ?? null,
                    'status'     => $row['estado'] ?? 'active',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error importando colegiado: ' . $e->getMessage() . ' Row: ' . json_encode($row));
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 50;
    }
}

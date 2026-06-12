<?php

namespace App\Imports;

use App\Models\Collegiate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class CollegiatesImport implements ToModel, WithHeadingRow
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
            return Collegiate::updateOrCreate(
                [
                    'registration_number' => $row['matricula'],
                    'school_id'           => $this->school_id
                ],
                [
                    'first_name' => $row['nombre'] ?? $row['nombres'] ?? 'S/N',
                    'last_name'  => $row['apellido'] ?? $row['apellidos'] ?? 'S/A',
                    'dni'        => $row['dni'] ?? null,
                    'email'      => $row['email'] ?? null,
                    'phone'      => $row['telefono'] ?? null,
                    'status'     => $row['estado'] ?? 'active',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error importando colegiado: ' . $e->getMessage() . ' Row: ' . json_encode($row));
            return null;
        }
    }
}

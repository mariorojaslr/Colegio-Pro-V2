<?php

namespace App\Imports;

use App\Models\Collegiate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CollegiatesImport implements ToModel, WithHeadingRow
{
    protected $school_id;

    public function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public function model(array $row)
    {
        if (!isset($row['matricula'])) {
            return null;
        }

        return Collegiate::updateOrCreate(
            [
                'registration_number' => $row['matricula'],
                'school_id'           => $this->school_id
            ],
            [
                'first_name' => $row['nombres'] ?? 'S/N',
                'last_name'  => $row['apellidos'] ?? 'S/A',
                'dni'        => $row['dni'] ?? null,
                'email'      => $row['email'] ?? null,
                'phone'      => $row['telefono'] ?? null,
                'status'     => $row['estado'] ?? 'active',
            ]
        );
    }
}

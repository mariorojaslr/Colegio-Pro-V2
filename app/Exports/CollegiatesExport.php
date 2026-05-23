<?php

namespace App\Exports;

use App\Models\Collegiate;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollegiatesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $school_id;

    public function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public function collection()
    {
        return Collegiate::where('school_id', $this->school_id)->get();
    }

    public function headings(): array
    {
        return [
            'Matricula',
            'Nombres',
            'Apellidos',
            'DNI',
            'Email',
            'Telefono',
            'Estado',
        ];
    }

    public function map($collegiate): array
    {
        return [
            $collegiate->registration_number,
            $collegiate->first_name,
            $collegiate->last_name,
            $collegiate->dni,
            $collegiate->email,
            $collegiate->phone,
            $collegiate->status,
        ];
    }
}

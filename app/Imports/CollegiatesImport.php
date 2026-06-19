<?php

namespace App\Imports;

use App\Models\Collegiate;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CollegiatesImport implements ToCollection
{
    protected $school_id;

    public function __construct($school_id)
    {
        $this->school_id = $school_id;
    }

    public $errors = [];
    public $importedCount = 0;

    public function collection(Collection $rows)
    {
        $isHeaderFound = false;
        $colMap = [];

        foreach ($rows as $index => $row) {
            // Eliminar valores nulos o vacíos del array para poder buscar
            $rowArray = $row->toArray();
            
            // Si aún no encontramos los encabezados, buscamos en esta fila
            if (!$isHeaderFound) {
                $hasMatricula = collect($rowArray)->contains(function($value) {
                    $val = strtolower((string)$value);
                    return str_contains($val, 'matricula') || str_contains($val, 'mat') || str_contains($val, 'matrícula');
                });

                if ($hasMatricula) {
                    $isHeaderFound = true;
                    // Mapear qué índice corresponde a qué columna
                    foreach ($rowArray as $idx => $val) {
                        $valLower = strtolower(trim((string)$val));
                        if (str_contains($valLower, 'mat')) $colMap['matricula'] = $idx;
                        if (str_contains($valLower, 'nombre')) $colMap['nombre'] = $idx;
                        if (str_contains($valLower, 'apellido')) $colMap['apellido'] = $idx;
                        if (str_contains($valLower, 'dni') || str_contains($valLower, 'doc')) $colMap['dni'] = $idx;
                        if (str_contains($valLower, 'email') || str_contains($valLower, 'correo')) $colMap['email'] = $idx;
                        if (str_contains($valLower, 'situa')) $colMap['situacion'] = $idx;
                        if (str_contains($valLower, 'observa')) $colMap['observaciones'] = $idx;
                        if (str_contains($valLower, 'total adeudado') || str_contains($valLower, 'deuda')) $colMap['deuda'] = $idx;
                        if (str_contains($valLower, 'meses')) $colMap['meses'] = $idx;
                    }
                }
                continue; // Saltar la fila de encabezado
            }

            // Procesamiento de la fila de datos
            $matriculaIdx = $colMap['matricula'] ?? null;
            $matricula = $matriculaIdx !== null ? $rowArray[$matriculaIdx] : null;

            if (empty($matricula)) {
                continue;
            }

            try {
                $firstName = 'S/N';
                $lastName = 'S/A';

                $nombreIdx = $colMap['nombre'] ?? null;
                $apellidoIdx = $colMap['apellido'] ?? null;

                // Si nombre y apellido están en la misma columna
                if ($nombreIdx !== null && $apellidoIdx !== null && $nombreIdx === $apellidoIdx) {
                    $fullName = trim((string)$rowArray[$nombreIdx]);
                    if ($fullName) {
                        $parts = explode(',', $fullName);
                        if (count($parts) >= 2) {
                            $lastName = trim($parts[0]);
                            $firstName = trim($parts[1]);
                        } else {
                            $parts = explode(' ', $fullName, 2);
                            $lastName = trim($parts[0] ?? 'S/A');
                            $firstName = trim($parts[1] ?? 'S/N');
                        }
                    }
                } else {
                    $firstName = $nombreIdx !== null ? (trim((string)$rowArray[$nombreIdx]) ?: 'S/N') : 'S/N';
                    $lastName = $apellidoIdx !== null ? (trim((string)$rowArray[$apellidoIdx]) ?: 'S/A') : 'S/A';
                }

                $emailIdx = $colMap['email'] ?? null;
                $email = $emailIdx !== null ? (trim((string)$rowArray[$emailIdx]) ?: null) : null;

                $dniIdx = $colMap['dni'] ?? null;
                $dni = $dniIdx !== null ? (trim((string)$rowArray[$dniIdx]) ?: null) : null;

                // Find or create User
                $user = $email ? User::where('email', $email)->first() : null;
                if (!$user && $email) {
                    $user = User::create([
                        'name' => $firstName . ' ' . $lastName,
                        'email' => $email,
                        'password' => Hash::make($dni ?? '12345678'),
                        'role' => 'COLEGIADO',
                        'school_id' => $this->school_id
                    ]);
                }

                $situacionIdx = $colMap['situacion'] ?? null;
                $situacion = $situacionIdx !== null ? trim((string)$rowArray[$situacionIdx]) : null;

                $obsIdx = $colMap['observaciones'] ?? null;
                $observaciones = $obsIdx !== null ? trim((string)$rowArray[$obsIdx]) : null;

                $deudaIdx = $colMap['deuda'] ?? null;
                $rawDebt = $deudaIdx !== null ? trim((string)$rowArray[$deudaIdx]) : '0';
                $rawDebt = str_replace(['$', '.', ' '], '', $rawDebt);
                $rawDebt = str_replace(',', '.', $rawDebt);
                $historicalDebt = is_numeric($rawDebt) ? (float) $rawDebt : 0;

                $mesesIdx = $colMap['meses'] ?? null;
                $historicalDebtMonths = $mesesIdx !== null ? (int)trim((string)$rowArray[$mesesIdx]) : 0;

                $status = 'active';
                $situacionLower = strtolower($situacion ?? '');
                $obsLower = strtolower($observaciones ?? '');
                
                if (str_contains($situacionLower, 'fallecida') || str_contains($situacionLower, 'fallecido') || str_contains($obsLower, 'fallecida')) {
                    $status = 'deceased';
                } elseif (str_contains($situacionLower, 'jubilada') || str_contains($situacionLower, 'jubilado') || str_contains($obsLower, 'jubilada')) {
                    $status = 'retired';
                } elseif (str_contains($situacionLower, 'baja') || str_contains($obsLower, 'baja')) {
                    $status = 'inactive';
                }

                $finalObservations = $observaciones;
                if ($situacion && !in_array($status, ['deceased', 'retired'])) {
                    $finalObservations = $situacion . ($observaciones ? ' | ' . $observaciones : '');
                }

                Collegiate::updateOrCreate(
                    [
                        'registration_number' => $matricula,
                        'school_id'           => $this->school_id
                    ],
                    [
                        'user_id'    => $user ? $user->id : null,
                        'first_name' => $firstName,
                        'last_name'  => $lastName,
                        'dni'        => $dni,
                        'email'      => $email,
                        'status'     => $status,
                        'historical_debt' => $historicalDebt,
                        'historical_debt_months' => $historicalDebtMonths,
                        'observations' => $finalObservations,
                    ]
                );
                $this->importedCount++;

            } catch (\Exception $e) {
                $errorMsg = 'Fila ' . ($index + 1) . ': ' . $e->getMessage();
                Log::error('Error importando colegiado: ' . $errorMsg . ' Row: ' . json_encode($rowArray));
                $this->errors[] = $errorMsg;
                continue;
            }
        }
    }
}

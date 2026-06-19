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
        $matriculaKey = collect(array_keys($row))->first(function($key) {
            return str_contains($key, 'matricula') || str_contains($key, 'mat');
        });
        $matricula = $matriculaKey ? $row[$matriculaKey] : null;

        // Ignorar filas vacías o sin matrícula
        if (empty($matricula)) {
            return null;
        }

        try {
            $nombreKey = collect(array_keys($row))->first(function($key) { return str_contains($key, 'nombre'); });
            $apellidoKey = collect(array_keys($row))->first(function($key) { return str_contains($key, 'apellido') && !str_contains($key, 'nombre'); });
            $apellidoYNombreKey = collect(array_keys($row))->first(function($key) { return str_contains($key, 'apellido') && str_contains($key, 'nombre'); });

            $firstName = 'S/N';
            $lastName = 'S/A';

            if ($apellidoYNombreKey && !empty($row[$apellidoYNombreKey])) {
                $parts = explode(',', $row[$apellidoYNombreKey]);
                if (count($parts) >= 2) {
                    $lastName = trim($parts[0]);
                    $firstName = trim($parts[1]);
                } else {
                    $parts = explode(' ', $row[$apellidoYNombreKey], 2);
                    $lastName = trim($parts[0] ?? 'S/A');
                    $firstName = trim($parts[1] ?? 'S/N');
                }
            } else {
                $firstName = $nombreKey ? ($row[$nombreKey] ?? 'S/N') : 'S/N';
                $lastName = $apellidoKey ? ($row[$apellidoKey] ?? 'S/A') : 'S/A';
            }

            $email = $row['email'] ?? null;
            $dniKey = collect(array_keys($row))->first(function($key) { return str_contains($key, 'dni') || str_contains($key, 'documento'); });
            $dni = $dniKey ? ($row[$dniKey] ?? null) : null;

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

            // Parsing Historical Debt and Status
            $situacion = $row['situacion'] ?? null;
            $observaciones = $row['observaciones'] ?? null;
            
            // Clean up currency strings e.g. "$12.000,00" -> "12000.00"
            $rawDebt = $row['total_adeudado'] ?? '0';
            $rawDebt = str_replace(['$', '.', ' '], '', $rawDebt);
            $rawDebt = str_replace(',', '.', $rawDebt);
            $historicalDebt = is_numeric($rawDebt) ? (float) $rawDebt : 0;
            
            $monthsKey = collect(array_keys($row))->first(function($key) {
                return str_contains($key, 'meses_adeudados');
            });
            $historicalDebtMonths = $monthsKey ? (int) ($row[$monthsKey] ?? 0) : 0;

            $status = 'active';
            $situacionLower = strtolower($situacion);
            $obsLower = strtolower($observaciones);
            
            if (str_contains($situacionLower, 'fallecida') || str_contains($situacionLower, 'fallecido') || str_contains($obsLower, 'fallecida')) {
                $status = 'deceased';
            } elseif (str_contains($situacionLower, 'jubilada') || str_contains($situacionLower, 'jubilado') || str_contains($obsLower, 'jubilada')) {
                $status = 'retired';
            } elseif (str_contains($situacionLower, 'baja') || str_contains($obsLower, 'baja')) {
                $status = 'inactive';
            }

            // Mapear observaciones: guardar situación si no cae en estado limpio
            $finalObservations = $observaciones;
            if ($situacion && !in_array($status, ['deceased', 'retired'])) {
                $finalObservations = $situacion . ($observaciones ? ' | ' . $observaciones : '');
            }

            return Collegiate::updateOrCreate(
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
                    'phone'      => $row['telefono'] ?? null,
                    'status'     => $status,
                    'historical_debt' => $historicalDebt,
                    'historical_debt_months' => $historicalDebtMonths,
                    'observations' => $finalObservations,
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

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportTSCollegiates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collegiates:import-ts {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Terapeutas Ocupacionales from their ODS file into Cotolar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        if (!$filePath) {
            $filePath = base_path('ACUERDO DE TRABAJO/Terapistas Ocupacionales/COLEGIO DE TERAPISTAS OCUPACIONALES DE LA RIOJA (respuestas).ods');
        }

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return;
        }

        $this->info("Importing from {$filePath}...");

        $data = \Maatwebsite\Excel\Facades\Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
            public function array(array $array) {}
        }, $filePath);

        if (empty($data) || empty($data[0])) {
            $this->error("No data found in the spreadsheet.");
            return;
        }

        $rows = $data[0];
        $headers = array_shift($rows); // Remove headers row

        // Find cotolar school
        $school = \App\Models\School::where('slug', 'cotolar')->first();
        if (!$school) {
            $school = \App\Models\School::first(); // Fallback
        }

        $count = 0;
        foreach ($rows as $row) {
            if (empty(trim($row[2] ?? ''))) continue; // No name

            // Columns matching:
            // [1] Email
            // [2] Apellido Nombre
            // [3] DNI
            // [4] Fecha de nacimiento
            // [5] Titulo
            // [7] Año desde que ejerce
            // [8] Matricula
            // [9] Lugares trabajo
            // [10] Telefono

            $fullName = trim($row[2]);
            // Attempt to split first and last name (assume first word is last name or first two if it's all uppercase?)
            // Just split by space roughly, or keep it in last_name for now. Let's do simple split:
            $parts = explode(' ', $fullName);
            if (count($parts) >= 2) {
                // Let's assume first word is last name, rest is first name, just as an approximation, or viceversa
                $lastName = array_shift($parts);
                $firstName = implode(' ', $parts);
            } else {
                $firstName = $fullName;
                $lastName = '';
            }

            $email = trim($row[1] ?? '');
            $dni = trim($row[3] ?? '');
            
            // Excel dates can be numeric
            $birthDate = null;
            if (!empty($row[4])) {
                if (is_numeric($row[4])) {
                    $birthDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('Y-m-d');
                } else {
                    // Try parsing string
                    try {
                        $birthDate = \Carbon\Carbon::parse($row[4])->format('Y-m-d');
                    } catch (\Exception $e) {}
                }
            }

            $degree = trim($row[5] ?? '');
            $practicingSince = trim($row[7] ?? null);
            $registrationNumber = trim($row[8] ?? '');
            
            if (empty($registrationNumber)) {
                $registrationNumber = 'TS-' . uniqid(); // fallback
            }

            $workplaces = trim($row[9] ?? '');
            $phone = trim($row[10] ?? '');

            \App\Models\Collegiate::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'registration_number' => $registrationNumber,
                ],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'dni' => $dni,
                    'phone' => $phone,
                    'birth_date' => $birthDate,
                    'degree' => $degree,
                    'workplaces_info' => $workplaces,
                    'practicing_since_year' => is_numeric($practicingSince) ? (int)$practicingSince : null,
                    'status' => 'active',
                ]
            );

            $count++;
        }

        $this->info("Successfully imported/updated {$count} collegiates.");
    }
}

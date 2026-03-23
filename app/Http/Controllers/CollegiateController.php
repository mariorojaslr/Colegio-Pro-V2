<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collegiate;
use Illuminate\Support\Facades\Auth;

class CollegiateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);

        $baseQuery = $user->isOwner() ? Collegiate::query() : $user->school->collegiates();

        // 📊 Calculamos Métricas para las Tarjetas Superiores
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'debt_fees' => (clone $baseQuery)->where('is_fees_compliant', false)->count(),
            'debt_docs' => (clone $baseQuery)->where('is_fully_documented', false)->count(),
            'enabled' => (clone $baseQuery)->where('is_fees_compliant', true)
                                          ->where('is_fully_documented', true)
                                          ->where('is_ethics_compliant', true)
                                          ->count(),
        ];

        $query = clone $baseQuery;

        // 🔍 Búsqueda General por Texto (Nombre, Apellido, Matrícula, DNI, Email, Teléfono)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('registration_number', 'like', "%$search%")
                  ->orWhere('dni', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // 🎯 Filtros Específicos de Gestión
        if ($request->filter === 'morosos') {
            $query->where('is_fees_compliant', false);
        } elseif ($request->filter === 'sin_papeles') {
            $query->where('is_fully_documented', false);
        } elseif ($request->filter === 'habilitados') {
            $query->where('is_fees_compliant', true)
                  ->where('is_fully_documented', true)
                  ->where('is_ethics_compliant', true);
        }

        // 📏 Densidad de Datos (Paginación Personalizada)
        $perPage = (int) $request->get('per_page', 15);
        if (!in_array($perPage, [5, 10, 15, 20, 50, 100])) $perPage = 15;

        $collegiates = $query->orderBy('last_name')->paginate($perPage);
        
        return view('collegiates.index', compact('collegiates', 'stats', 'perPage'));
    }

    public function show(Collegiate $collegiate)
    {
        $user = Auth::user();
        
        // Seguridad: Solo admin del mismo colegio puede ver
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        // Cargamos los documentos entregados y los requisitos de este colegio
        $collegiate->load(['documents.requirement']);
        
        $requirements = \App\Models\ComplianceRequirement::where('school_id', $collegiate->school_id)
            ->get();

        return view('collegiates.show', compact('collegiate', 'requirements'));
    }

    /**
     * Genera el Certificado de Habilitación Profesional.
     */
    public function certificate(Collegiate $collegiate)
    {
        $user = Auth::user();

        // 1. Verificación de permisos
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        // 2. Verificación de Habilitación Real
        if (!$collegiate->isEnabledForCertificates()) {
            return redirect()->route('collegiates.show', $collegiate)
                ->with('error', 'El colegiado no cumple con los requisitos para emitir el certificado.');
        }

        // 3. Retornamos la vista del certificado (diseñada para imprimir/PDF)
        return view('collegiates.certificate', compact('collegiate'));
    }

    public function import()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);
        return view('collegiates.import');
    }

    public function storeImport(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);

        $request->validate([
            'file' => 'required|mimes:csv,txt|max:4096', // Aumentamos límite para 5000+ registros
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");
        
        $count = 0;
        $header = true;
        
        // Iniciamos transacción para integridad de datos masiva
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            while (($data = fgetcsv($handle, 2000, ";")) !== FALSE) { // Cambiamos a ';' que es estándar en Excel LATAM
                if ($header) { $header = false; continue; }
                
                // Formato CSV esperado: Matricula;Nombre;Apellido;Email;DNI;Telefono;Etica(1/0);Cuotas(1/0)
                if (count($data) < 5) continue;

                Collegiate::updateOrCreate(
                    ['registration_number' => $data[0], 'school_id' => $user->school_id],
                    [
                        'first_name' => $data[1],
                        'last_name' => $data[2],
                        'email' => $data[3],
                        'dni' => $data[4],
                        'phone' => $data[5] ?? null,
                        'is_ethics_compliant' => ($data[6] ?? '1') == '1',
                        'is_fees_compliant' => ($data[7] ?? '0') == '1',
                        'status' => 'active',
                    ]
                );
                $count++;
            }
            fclose($handle);
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', "Error en importación masiva: " . $e->getMessage());
        }

        return redirect()->route('collegiates.index')->with('success', "¡Proceso terminado! Se han importado/actualizado $count colegiados exitosamente.");
    }

    /**
     * Exporta el padrón completo a CSV (Compatible con Excel).
     */
    public function export()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);

        $query = $user->isOwner() ? Collegiate::query() : $user->school->collegiates();
        $collegiates = $query->orderBy('last_name')->get();

        $fileName = 'padron_profesional_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Matricula', 'Apellido', 'Nombre', 'DNI', 'Email', 'Telefono', 'Situacion', 'Caja Etica', 'Cuotas');

        $callback = function() use($collegiates, $columns) {
            $file = fopen('php://output', 'w');
            // Añadir BOM para soporte de acentos en Excel
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            fputcsv($file, $columns, ';');

            foreach ($collegiates as $col) {
                $row['Matricula']  = $col->registration_number;
                $row['Apellido']   = $col->last_name;
                $row['Nombre']     = $col->first_name;
                $row['DNI']        = $col->dni;
                $row['Email']      = $col->email;
                $row['Telefono']   = $col->phone;
                $row['Situacion']  = $col->professional_situation ?? 'Activo';
                $row['Caja Etica'] = $col->is_ethics_compliant ? 'OK' : 'DEUDA';
                $row['Cuotas']     = $col->is_fees_compliant ? 'AL DIA' : 'MOROSO';

                fputcsv($file, array($row['Matricula'], $row['Apellido'], $row['Nombre'], $row['DNI'], $row['Email'], $row['Telefono'], $row['Situacion'], $row['Caja Etica'], $row['Cuotas']), ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

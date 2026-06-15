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

        $schoolId = $user->isOwner() ? request('school_id', School::first()->id) : $user->school_id;

        $baseQuery = $user->isOwner() ? Collegiate::query() : $user->school->collegiates();

        // Eager load para evitar N+1 en la tabla interactiva
        $baseQuery->with([
            'dues' => function($q) {
                $q->orderBy('due_date', 'asc');
            }, 
            'documents', 
            'sanctions' => function($q) {
                $q->where('status', 'active');
            }
        ]);

        // Requisitos documentales obligatorios para calcular el progreso
        $requirements = \App\Models\ComplianceRequirement::where('school_id', $schoolId)->get();

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
        
        return view('collegiates.index', compact('collegiates', 'stats', 'perPage', 'requirements'));
    }

    public function show(Collegiate $collegiate)
    {
        $user = Auth::user();
        
        // Seguridad: Solo admin del mismo colegio puede ver
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        // Cargamos los documentos entregados y las cuotas de este colegio
        $collegiate->load(['documents.requirement', 'dues' => function($q) {
            $q->orderBy('due_date', 'desc');
        }]);
        
        $requirements = \App\Models\ComplianceRequirement::where('school_id', $collegiate->school_id)
            ->get();

        return view('collegiates.show', compact('collegiate', 'requirements'));
    }

    public function update(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        
        // Seguridad: Solo admin del mismo colegio puede editar
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'dni' => 'required|string|max:20',
            'registration_number' => 'required|string|max:50',
            'professional_situation' => 'nullable|string|max:100',
            'financial_situation_note' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'workplaces_info' => 'nullable|string',
            'plus_code' => 'nullable|string|max:255',
            'degree' => 'nullable|string|max:255',
        ]);

        // Fix professional_situation mapping to status if necessary, or just rely on custom_attributes.
        // Actually, we'll let it fill custom_attributes or status if we handle it elsewhere, 
        // but since fillable just accepts it, wait, professional_situation and financial_situation_note are NOT in fillable.
        // They were ignored before! Let's put them in custom_attributes.
        $data = $request->all();
        $customAttributes = $collegiate->custom_attributes ?? [];
        if ($request->has('professional_situation')) {
            $customAttributes['professional_situation'] = $request->professional_situation;
        }
        if ($request->has('financial_situation_note')) {
            $customAttributes['financial_situation_note'] = $request->financial_situation_note;
        }
        $data['custom_attributes'] = $customAttributes;

        $collegiate->update($data);

        return back()->with('success', 'Ficha del colegiado actualizada correctamente.');
    }

    /**
     * Actualización rápida de un solo campo (Perfilado Progresivo)
     */
    public function quickUpdate(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        if ($user->id !== $collegiate->user_id && $user->role !== 'ADMIN_COLEGIO') {
            abort(403);
        }

        $request->validate([
            'field' => 'required|string|in:birth_date,address,workplaces_info',
            'value' => 'required|string'
        ]);

        $collegiate->update([
            $request->field => $request->value
        ]);

        return back()->with('success', '¡Gracias! Dato guardado en tu perfil correctamente.');
    }

    /**
     * Refinancia deudas o registra un pago consolidado por tarjeta externa.
     */
    public function refinance(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        $request->validate([
            'due_ids' => 'required|array',
            'financing_type' => 'required|in:external_card,internal_plan',
            'installments' => 'nullable|required_if:financing_type,internal_plan|integer|min:1',
            'installment_amount' => 'nullable|required_if:financing_type,internal_plan|numeric|min:0',
            'first_due_date' => 'nullable|required_if:financing_type,internal_plan|date',
        ]);

        $dues = $collegiate->dues()->whereIn('id', $request->due_ids)->get();
        if ($dues->isEmpty()) {
            return back()->with('error', 'No se seleccionaron cuotas válidas para refinanciar.');
        }

        if ($request->financing_type === 'external_card') {
            // Se asume que la deuda la compró la tarjeta. La institución ya cobró todo.
            foreach ($dues as $due) {
                $due->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payment_method' => 'Tarjeta de Crédito',
                    'notes' => 'Pagado vía Financiación Externa / Tarjeta'
                ]);
            }
            $collegiate->updateComplianceStatus();
            return back()->with('success', 'Las cuotas han sido marcadas como pagadas (Financiación Externa exitosa).');
        } 
        
        // internal_plan
        // 1. "Anular/Refinanciar" las viejas
        foreach ($dues as $due) {
            $due->update([
                'status' => 'refinanced',
                'notes' => 'Refinanciado en nuevo plan de pagos institucional.'
            ]);
        }

        // 2. Generar las N nuevas cuotas extraordinarias
        $installments = (int) $request->installments;
        $amount = (float) $request->installment_amount;
        $date = \Carbon\Carbon::parse($request->first_due_date);

        for ($i = 1; $i <= $installments; $i++) {
            \App\Models\CollegiateDue::create([
                'collegiate_id' => $collegiate->id,
                'due_date' => $date->copy()->addMonths($i - 1),
                'amount' => $amount,
                'status' => 'pending',
                'due_type' => 'extraordinary',
                'notes' => "Cuota Plan de Pagos Institucional ($i/$installments)"
            ]);
        }

        $collegiate->updateComplianceStatus();
        return back()->with('success', "Se generó el Plan de Pagos con $installments cuotas exitosamente.");
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
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\CollegiatesImport($user->school_id), $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Error en importación masiva: " . $e->getMessage());
        }

        return redirect()->route('collegiates.index')->with('success', "¡Proceso terminado! Se ha importado/actualizado el padrón exitosamente.");
    }

    /**
     * Exporta el padrón completo a Excel.
     */
    public function export()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);

        $fileName = 'padron_profesional_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CollegiatesExport($user->school_id), $fileName);
    }

    /**
     * Permite al Administrador entrar como un Colegiado específico.
     */
    public function impersonate(Collegiate $collegiate)
    {
        $admin = Auth::user();
        if ($admin->role !== 'ADMIN_COLEGIO' && !$admin->isOwner()) {
            abort(403, 'No tienes permisos para simular usuarios.');
        }

        // Si es admin de colegio, verificar que el colegiado pertenezca a su colegio
        if ($admin->role === 'ADMIN_COLEGIO' && $collegiate->school_id !== $admin->school_id) {
            abort(403, 'El colegiado no pertenece a su institución.');
        }

        // Asegurar que el colegiado tenga un usuario vinculado
        if (!$collegiate->user_id) {
            return redirect()->back()->with('error', 'Este colegiado no tiene una cuenta de usuario creada en el sistema.');
        }

        // Guardar el ID del admin original en sesión
        session(['impersonator_id' => $admin->id]);
        
        // Loguear al usuario del colegiado
        Auth::loginUsingId($collegiate->user_id);

        return redirect()->route('home')->with('success', 'Estás simulando la cuenta de ' . $collegiate->first_name . '. Para volver a tu cuenta de Administrador, haz clic en el botón de la cabecera.');
    }

    /**
     * Registra una nueva sanción del Tribunal de Ética.
     */
    public function storeSanction(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        $request->validate([
            'severity' => 'required|in:leve,media,grave',
            'reason' => 'required|string|max:255',
            'arguments' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        \App\Models\EthicsSanction::create([
            'collegiate_id' => $collegiate->id,
            'type' => empty($request->end_date) ? 'permanent' : 'temporary',
            'severity' => $request->severity,
            'reason' => $request->reason,
            'arguments' => $request->arguments,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        $collegiate->updateComplianceStatus();

        return back()->with('success', 'La infracción ética ha sido registrada correctamente.');
    }

    /**
     * Sube y actualiza la foto de perfil (avatar) del colegiado.
     */
    public function uploadAvatar(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner() && $user->id !== $collegiate->user_id) abort(403);

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $file = $request->file('avatar');
        $fileName = 'avatar_' . $collegiate->id . '_' . time() . '.jpg';
        
        // Ensure avatars directory exists
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('avatars')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('avatars');
        }

        // Utilizar Intervention Image para auto-centrar (cover), comprimir (75%) y redimensionar
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file->getPathname());
        
        // Recorta de forma inteligente al centro (tipo busto cuadrado) a 400x400px
        $image->cover(400, 400, 'center');
        
        // Guarda la imagen comprimida a JPEG para que sea liviana pero de buena calidad
        $image->toJpeg(75)->save(storage_path('app/public/avatars/' . $fileName));
        
        $collegiate->update([
            'avatar_url' => asset('storage/avatars/' . $fileName)
        ]);

        return redirect()->back()->with('success', 'Foto de perfil procesada, centrada y actualizada correctamente.');
    }
}

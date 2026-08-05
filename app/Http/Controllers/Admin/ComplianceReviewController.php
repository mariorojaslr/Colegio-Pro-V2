<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegiateDocument;
use App\Models\Collegiate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceReviewController extends Controller
{
    /**
     * Muestra la bandeja de documentos pendientes de revisión del colegio.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        
        // Obtener documentos pendientes filtrados por el colegio del admin
        $pendingDocuments = CollegiateDocument::whereHas('collegiate', function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })
        ->where('status', 'pending')
        ->with(['collegiate', 'requirement'])
        ->get();

        return view('admin.compliance.index', compact('pendingDocuments'));
    }

    /**
     * Aprueba un documento y marca el trámite como verificado.
     */
    public function approve(CollegiateDocument $document)
    {
        // Seguridad: El admin debe pertenecer al mismo colegio que el colegiado
        if (Auth::user()->school_id !== $document->collegiate->school_id) abort(403);

        $expiresAt = null;
        if ($document->requirement->expiration_months) {
            $expiresAt = now()->addMonths($document->requirement->expiration_months);
        }

        $document->update([
            'status' => 'approved',
            'admin_notes' => 'Aprobado por auditoría el ' . now()->format('d/m/Y'),
            'expires_at' => $expiresAt,
        ]);

        // Verificar si el colegiado ahora tiene TODOS sus documentos obligatorios aprobados
        $this->updateCollegiateStatus($document->collegiate);

        return back()->with('success', "¡Documento '{$document->requirement->name}' aprobado con éxito!");
    }

    /**
     * Rechaza un documento con una observación escrita para el profesional.
     */
    public function reject(Request $request, CollegiateDocument $document)
    {
        if (Auth::user()->school_id !== $document->collegiate->school_id) abort(403);

        $request->validate([
            'admin_notes' => 'required|string|min:5|max:500'
        ]);

        $document->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes
        ]);

        // Asegurarse de que el estado de habilitación se mantenga en 'no documentado'
        $document->collegiate->update(['is_fully_documented' => false]);

        return back()->with('warning', "El documento ha sido rechazado y se ha enviado la notificación al colegiado.");
    }

    /**
     * Marca un documento físico como entregado.
     */
    public function markPhysical(Collegiate $collegiate, \App\Models\ComplianceRequirement $requirement)
    {
        if (Auth::user()->school_id !== $collegiate->school_id) abort(403);
        if ($requirement->school_id !== $collegiate->school_id) abort(403);

        $expiresAt = null;
        if ($requirement->expiration_months) {
            $expiresAt = now()->addMonths($requirement->expiration_months);
        }

        $document = CollegiateDocument::updateOrCreate(
            [
                'collegiate_id' => $collegiate->id,
                'compliance_requirement_id' => $requirement->id,
            ],
            [
                'file_url' => '#physical',
                'status' => 'approved',
                'admin_notes' => 'Entregado físicamente y verificado por ' . Auth::user()->name . ' el ' . now()->format('d/m/Y'),
                'expires_at' => $expiresAt,
            ]
        );

        $this->updateCollegiateStatus($collegiate);

        return back()->with('success', "¡Requisito presencial '{$requirement->name}' marcado como entregado!");
    }

    /**
     * Lógica interna para actualizar el estado global del colegiado.
     */
    private function updateCollegiateStatus(Collegiate $collegiate)
    {
        $mandatoryReqsCount = $collegiate->school->complianceRequirements()->where('is_mandatory', true)->count();
        $approvedDocsCount = $collegiate->documents()->where('status', 'approved')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->whereHas('requirement', function($q) {
                $q->where('is_mandatory', true);
            })->count();

        $collegiate->update([
            'is_fully_documented' => ($approvedDocsCount >= $mandatoryReqsCount)
        ]);
    }
}

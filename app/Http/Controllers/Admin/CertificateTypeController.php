<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
class CertificateTypeController extends Controller
{
    public function index()
    {
        $types = CertificateType::where('school_id', auth()->user()->school_id)->get();
        return view('admin.certificate_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.certificate_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $backgroundPath = null;
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = 'bg_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate_backgrounds'), $filename);
            $backgroundPath = 'certificate_backgrounds/' . $filename;
        }

        $designSettings = null;
        if ($request->filled('design_settings')) {
            $designSettings = json_decode($request->design_settings, true);
        }

        $type = CertificateType::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'validity_days' => $request->validity_days,
            'is_single_use' => $request->has('is_single_use'),
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'template_content' => $request->template_content,
            'has_qr' => $request->has('has_qr'),
            'background_path' => $backgroundPath,
            'page_size' => $request->page_size ?? 'a4',
            'page_orientation' => $request->page_orientation ?? 'landscape',
            'design_settings' => $designSettings,
            'is_active' => true,
        ]);

        return redirect()->route('admin.certificate_types.edit', $type->id)->with('success', 'Trámite creado exitosamente. Ahora sube la imagen de fondo institucional y arrastra las variables.');
    }

    public function edit(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);
        
        $boardMembers = \App\Models\BoardMember::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();
            
        $selectedSignatoryIds = $certificate_type->signatories()->pluck('board_members.id')->toArray();

        return view('admin.certificate_types.edit', compact('certificate_type', 'boardMembers', 'selectedSignatoryIds'));
    }

    public function update(Request $request, CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $backgroundPath = $certificate_type->background_path;
        if ($request->hasFile('background_image')) {
            // Eliminar anterior si existe
            if ($backgroundPath && file_exists(public_path($backgroundPath))) {
                @unlink(public_path($backgroundPath));
            }
            $file = $request->file('background_image');
            $filename = 'bg_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificate_backgrounds'), $filename);
            $backgroundPath = 'certificate_backgrounds/' . $filename;
        }

        $designSettings = $certificate_type->design_settings;
        if ($request->filled('design_settings')) {
            $designSettings = json_decode($request->design_settings, true);
        }

        $certificate_type->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'validity_days' => $request->validity_days,
            'is_single_use' => $request->has('is_single_use'),
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'template_content' => $request->template_content,
            'has_qr' => $request->has('has_qr'),
            'background_path' => $backgroundPath,
            'page_size' => $request->page_size ?? 'a4',
            'page_orientation' => $request->page_orientation ?? 'landscape',
            'design_settings' => $designSettings,
            'is_active' => $request->has('is_active'),
        ]);

        // Sincronizar firmas
        $signatories = $request->input('signatory_ids', []);
        $certificate_type->signatories()->sync($signatories);

        return redirect()->route('admin.certificate_types.index')->with('success', 'Trámite / Certificado actualizado.');
    }

    public function destroy(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);
        
        // Eliminar fondo si existe
        if ($certificate_type->background_path && file_exists(public_path($certificate_type->background_path))) {
            @unlink(public_path($certificate_type->background_path));
        }

        $certificate_type->delete();
        return back()->with('success', 'Eliminado correctamente.');
    }

    public function preview(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $school = auth()->user()->school;

        // Create a mock collegiate
        $collegiate = new \App\Models\Collegiate([
            'first_name' => 'Karina',
            'last_name' => 'Arias',
            'dni' => '12345678',
            'registration_number' => 'MAT-0001',
            'status' => 'active',
            'is_ethics_compliant' => true
        ]);

        if ($certificate_type->background_path) {
            $pdf = Pdf::loadView('admin.certificate_types.pdf.certificate_design', [
                'certificate_type' => $certificate_type,
                'school' => $school,
                'collegiates' => collect([$collegiate]),
                'is_bulk' => false
            ]);
            $pdf->setPaper($certificate_type->page_size, $certificate_type->page_orientation);
        } else {
            // Mock certificate para la plantilla clásica
            $certificate = new \App\Models\Certificate();
            $certificate->uuid = Str::uuid();
            $certificate->code = 'DEMO-123456';
            $certificate->issued_at = now();
            $certificate->expires_at = $certificate_type->validity_days ? now()->addDays($certificate_type->validity_days) : null;
            $certificate->setRelation('type', $certificate_type);

            $pdf = Pdf::loadView('pdf.certificate', compact('certificate', 'school', 'collegiate'));
            $pdf->setPaper('A4', 'landscape');
        }

        return $pdf->stream('Vista_Previa_Certificado.pdf');
    }

    public function exportBulkView(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $schoolId = auth()->user()->school_id;
        $collegiates = \App\Models\Collegiate::where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.certificate_types.bulk_export', compact('certificate_type', 'collegiates'));
    }

    public function exportBulkPdf(Request $request, CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'collegiate_ids' => 'required|array',
            'collegiate_ids.*' => 'exists:collegiates,id',
        ]);

        $school = auth()->user()->school;
        
        $collegiates = \App\Models\Collegiate::whereIn('id', $request->collegiate_ids)
            ->where('school_id', $school->id)
            ->get();

        $pdf = $this->generateBulkPdfInstance($certificate_type, $school, $collegiates);
        
        return $pdf->download('lote-certificados-' . strtolower(str_replace(' ', '-', $certificate_type->name)) . '.pdf');
    }

    public function emailBulkPdfToImprenta(Request $request, CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'collegiate_ids' => 'required|array',
            'collegiate_ids.*' => 'exists:collegiates,id',
            'email' => 'required|email',
        ]);

        $school = auth()->user()->school;
        
        $collegiates = \App\Models\Collegiate::whereIn('id', $request->collegiate_ids)
            ->where('school_id', $school->id)
            ->get();

        $pdf = $this->generateBulkPdfInstance($certificate_type, $school, $collegiates);
        $pdfContent = $pdf->output();

        $emailDestino = $request->email;
        $nombreTramite = $certificate_type->name;

        try {
            \Illuminate\Support\Facades\Mail::send([], [], function($message) use ($emailDestino, $pdfContent, $nombreTramite, $school) {
                $message->to($emailDestino)
                    ->subject('Lote de Certificados para Imprenta - ' . $nombreTramite)
                    ->html('<h3>Envío Masivo de Certificados para Imprenta</h3><p>Se adjunta el archivo PDF consolidado de certificados de ' . $school->name . ' generado de forma automática.</p>')
                    ->attachData($pdfContent, 'lote-certificados-' . strtolower(str_replace(' ', '-', $nombreTramite)) . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            return back()->with('success', 'El lote de certificados fue enviado a la imprenta (' . $emailDestino . ') correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar correo a la imprenta: ' . $e->getMessage());
        }
    }

    private function generateBulkPdfInstance(CertificateType $certificate_type, $school, $collegiates)
    {
        if ($certificate_type->background_path) {
            $pdf = Pdf::loadView('admin.certificate_types.pdf.certificate_design', [
                'certificate_type' => $certificate_type,
                'school' => $school,
                'collegiates' => $collegiates,
                'is_bulk' => true
            ]);
            $pdf->setPaper($certificate_type->page_size, $certificate_type->page_orientation);
        } else {
            // Creamos una vista simple fallback para la impresion masiva clasica
            $pdf = Pdf::loadView('admin.certificate_types.pdf.certificate_bulk_fallback', [
                'certificate_type' => $certificate_type,
                'school' => $school,
                'collegiates' => $collegiates
            ]);
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf;
    }
}

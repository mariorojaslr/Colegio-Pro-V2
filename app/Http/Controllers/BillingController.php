<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Listado de facturas para la institución actual (ADMIN_COLEGIO).
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        
        $invoices = PaymentRecord::where('school_id', $schoolId)
            ->with(['school.activeSubscription.plan'])
            ->latest()
            ->paginate(12);

        return view('billing.index', compact('invoices'));
    }

    /**
     * Descarga de la factura institucional en PDF.
     */
    public function download(PaymentRecord $invoice)
    {
        // Seguridad: Solo puede descargar facturas de su propia institución
        if ($invoice->school_id !== Auth::user()->school_id) {
            abort(403, 'No tiene permiso para acceder a esta factura.');
        }

        $invoice->load(['school.activeSubscription.plan']);
        
        // Compatibilidad con la plantilla PDF (espera total_amount)
        $invoice->total_amount = $invoice->amount;

        $pdf = Pdf::loadView('admin.billing.invoice_pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Factura_{$invoice->invoice_number}.pdf");
    }
}

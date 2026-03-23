<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    /**
     * Listado de todas las facturas del sistema global.
     */
    public function index()
    {
        $invoices = PaymentRecord::with('school.activeSubscription.plan')
            ->latest()
            ->paginate(20);

        return view('admin.billing.index', compact('invoices'));
    }

    /**
     * Descarga de la Factura Institucional en PDF.
     */
    public function downloadInvoice(PaymentRecord $invoice)
    {
        $invoice->load('school.activeSubscription.plan');

        // Compatibilidad con la plantilla PDF que usa 'total_amount'
        $invoice->total_amount = $invoice->amount;

        $pdf = Pdf::loadView('admin.billing.invoice_pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Factura_{$invoice->invoice_number}.pdf");
    }
}

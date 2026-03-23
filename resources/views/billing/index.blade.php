@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="h3 fw-black text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Gestión de Facturación</h1>
            <p class="text-muted small">Historial de pagos y suscripciones de la institución.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 fw-bold">
                PLAN ACTUAL: {{ auth()->user()->school->activeSubscription->plan->name ?? 'RESERVADO' }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted xx-small fw-bold uppercase ls-1">
                            <tr>
                                <th class="ps-4">NRO FACTURA</th>
                                <th>FECHA</th>
                                <th>MÉTODO DE PAGO</th>
                                <th>ESTADO</th>
                                <th>TOTAL (CLP)</th>
                                <th class="text-end pe-4">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $invoice->invoice_number ?? 'S/N' }}</td>
                                    <td class="text-muted small">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($invoice->payment_method == 'card')
                                                <i class="bi bi-credit-card me-2 text-primary"></i>
                                                <span class="small">Tarjeta de Crédito</span>
                                            @else
                                                <i class="bi bi-bank me-2 text-primary"></i>
                                                <span class="small">Transferencia Bancaria</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($invoice->status == 'paid')
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 xx-small fw-bold">PAGADO</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 xx-small fw-bold text-uppercase">{{ $invoice->status }}</span>
                                        @endif
                                    </td>
                                    <td class="fw-black text-dark fs-5">${{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('billing.download', $invoice->id) }}" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold xx-small shadow-sm">
                                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> DESCARGAR PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="opacity-25 mb-3"><i class="bi bi-wallet2 display-1"></i></div>
                                        <p class="text-muted">No se registran facturas emitidas para esta institución.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($invoices->hasPages())
                    <div class="card-footer bg-white p-4">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
    .fw-black { font-weight: 900; }
</style>
@endsection

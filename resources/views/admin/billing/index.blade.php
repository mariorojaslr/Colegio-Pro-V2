@extends('layouts.admin')

@section('header', 'Gestión de Facturación Global')

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="p-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Historial de Cobros a Instituciones</h5>
            </div>
            <div class="table-responsive p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted xx-small fw-bold uppercase ls-1">
                        <tr>
                            <th class="ps-4">NRO FACTURA</th>
                            <th>COLEGIO / CLIENTE</th>
                            <th>PLAN ACTUAL</th>
                            <th>FECHA</th>
                            <th>MÉTODO</th>
                            <th>TOTAL</th>
                            <th>ESTADO</th>
                            <th class="text-end pe-4">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">#{{ $invoice->invoice_number ?? 'S/N' }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $invoice->school->name }}</div>
                                    <div class="xx-small text-muted text-uppercase">{{ $invoice->school->slug }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 xx-small fw-bold">
                                        {{ $invoice->school->activeSubscription->plan->name ?? 'RESERVADO' }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($invoice->payment_method == 'card')
                                        <i class="bi bi-credit-card me-1"></i> Tarjeta
                                    @else
                                        <i class="bi bi-bank me-1"></i> Transferencia
                                    @endif
                                </td>
                                <td class="fw-black text-dark">${{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 xx-small fw-bold">PAGADO</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1 xx-small fw-bold">{{ strtoupper($invoice->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.billing.download', $invoice->id) }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold xx-small shadow-sm">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted small">No hay registros de facturación aún.</td>
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

<style>
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
    .fw-black { font-weight: 900; }
</style>
@endsection

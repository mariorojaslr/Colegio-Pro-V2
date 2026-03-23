@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-primary text-white">
                <div class="small fw-bold text-uppercase ls-1 opacity-75 mb-2">Ingresos Globales</div>
                <div class="display-6 fw-bold mb-0">${{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                <div class="small mt-2"><i class="bi bi-graph-up me-1"></i> +15% este mes</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="small fw-bold text-muted text-uppercase ls-1 mb-2">Suscripciones Activas</div>
                <div class="display-6 fw-bold mb-0">{{ $stats['active_subs'] }}</div>
                <div class="small text-primary mt-2">Promedio: $45.000 / suscripción</div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="small fw-bold text-muted text-uppercase ls-1 mb-2">Transacciones Totales</div>
                <div class="display-6 fw-bold mb-0">{{ $stats['total_payments'] }}</div>
                <div class="small text-muted mt-2">Procesadas vía Checkout Prestige</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-4 px-5 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Últimos Pagos Recibidos</h4>
            <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Exportar Contabilidad</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light border-0">
                    <tr>
                        <th class="ps-5 py-3 text-uppercase small fw-bold text-muted ls-1">Institución</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Monto</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Método</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Referencia</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted ls-1">Fecha</th>
                        <th class="pe-5 py-4 text-end">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                    <tr>
                        <td class="ps-5 py-4 fw-bold text-primary">{{ $payment->school->name }}</td>
                        <td class="py-4 fw-bold fs-5">${{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="py-4">
                            <span class="small fw-bold"><i class="bi bi-credit-card me-2 opacity-50"></i> TARJETA</span>
                        </td>
                        <td class="py-4 font-monospace small text-muted">{{ $payment->transaction_reference }}</td>
                        <td class="py-4 small">{{ $payment->created_at->format('d M, Y H:i') }}</td>
                        <td class="pe-5 py-4 text-end">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-bold border-0">CONFIRMADO</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">No existen registros financieros aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

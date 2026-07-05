@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Encabezado con Nombre y Matrícula -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0 text-dark">Historial de Cuotas y Pagos</h1>
            <p class="text-muted mb-0">
                Historial económico individual de <strong>{{ $collegiate->last_name }}, {{ $collegiate->first_name }}</strong> &bull; M.P. Nº {{ $collegiate->registration_number }}
            </p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.billing.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver a Finanzas
            </a>
        </div>
    </div>

    <!-- Tarjetas de Resumen Financiero -->
    <div class="row g-4 mb-4">
        <!-- Total Pagado -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small fw-bold text-uppercase text-muted mb-1">Total Pagado</h6>
                        <h3 class="fw-black mb-0 text-success">${{ number_format($dues->where('status', 'paid')->sum('amount'), 2, ',', '.') }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendiente -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small fw-bold text-uppercase text-muted mb-1">Total Pendiente</h6>
                        <h3 class="fw-black mb-0 text-warning">${{ number_format($dues->where('status', 'pending')->sum('amount'), 2, ',', '.') }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Vencido / Deuda -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="small fw-bold text-uppercase text-muted mb-1">Total Vencido (Deuda)</h6>
                        <h3 class="fw-black mb-0 text-danger">${{ number_format($dues->where('status', 'overdue')->sum('amount'), 2, ',', '.') }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de Transacciones -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt text-primary me-2"></i> Detalle de Comprobantes y Cuotas</h6>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-0">
                    <tr class="xx-small fw-bold text-muted text-uppercase ls-1">
                        <th class="py-3 px-4 border-0">Concepto / Periodo</th>
                        <th class="py-3 border-0 text-center">Vencimiento</th>
                        <th class="py-3 border-0 text-center">Estado</th>
                        <th class="py-3 border-0 text-center">Referencia de Pago</th>
                        <th class="py-3 border-0 text-center">Fecha Pago</th>
                        <th class="py-3 border-0 text-end">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dues as $due)
                        <tr class="border-bottom border-light">
                            <td class="py-3 px-4 fw-bold text-dark">
                                @if($due->concept)
                                    {{ $due->concept }}
                                @else
                                    Cuota Societaria - {{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}
                                @endif
                            </td>
                            <td class="py-3 text-center text-muted small">
                                {{ \Carbon\Carbon::parse($due->due_date)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 text-center">
                                @if($due->status === 'paid')
                                    <span class="badge bg-success rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px;">Pagado</span>
                                @elseif($due->status === 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px;">Pendiente</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px;">Vencido</span>
                                @endif
                            </td>
                            <td class="py-3 text-center font-monospace text-muted small">
                                {{ $due->payment_reference ?? 'N/A' }}
                            </td>
                            <td class="py-3 text-center text-muted small">
                                {{ $due->paid_at ? \Carbon\Carbon::parse($due->paid_at)->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="py-3 text-end fw-bold text-dark">
                                ${{ number_format($due->amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted small fw-bold">
                                <i class="bi bi-check-circle display-4 d-block mb-2 text-success opacity-25"></i>
                                No se registran cuotas ni transacciones en el historial de este colegiado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection

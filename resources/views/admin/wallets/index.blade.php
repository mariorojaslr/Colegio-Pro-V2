@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Billeteras Virtuales</h2>
            <p class="text-muted mb-0">Gestión de saldos y auditoría de ingresos</p>
        </div>
        <a href="{{ route('admin.school_settings.edit') }}" class="btn btn-outline-primary rounded-pill">
            <i class="bi bi-gear-fill me-2"></i> Configurar Pasarelas
        </a>
    </div>

    <!-- Tarjetas de Saldos -->
    <div class="row g-4 mb-5">
        @foreach($wallets as $wallet)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-muted mb-0">{{ $wallet->name }}</h6>
                            @if($wallet->type == 'mercadopago')
                                <i class="bi bi-credit-card-fill fs-4 text-primary"></i>
                            @else
                                <i class="bi bi-bank2 fs-4 text-secondary"></i>
                            @endif
                        </div>
                        <div>
                            <h3 class="fw-black mb-0 text-{{ $wallet->balance > 0 ? 'success' : 'dark' }}">
                                ${{ number_format($wallet->balance, 2, ',', '.') }}
                            </h3>
                            <small class="text-muted">Saldo Disponible ({{ $wallet->currency }})</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tabla de Últimos Movimientos -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold"><i class="bi bi-list-columns-reverse text-primary me-2"></i> Últimos Movimientos (Todas las Billeteras)</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted small">
                        <tr>
                            <th>Fecha</th>
                            <th>Billetera</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Referencia</th>
                            <th class="text-end">Monto</th>
                            <th class="text-end">Saldo Resultante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Unificamos los movimientos de todas las billeteras y ordenamos por fecha
                            $allMovements = $wallets->pluck('movements')->flatten()->sortByDesc('created_at');
                        @endphp
                        
                        @forelse($allMovements as $movement)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $movement->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $movement->created_at->format('H:i') }} hs</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $movement->wallet->name }}</span>
                                </td>
                                <td>
                                    @if($movement->type == 'income')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill"><i class="bi bi-arrow-down-left"></i> Ingreso</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill"><i class="bi bi-arrow-up-right"></i> Egreso</span>
                                    @endif
                                </td>
                                <td>{{ $movement->description }}</td>
                                <td class="text-muted small">{{ $movement->reference_id ?? '-' }}</td>
                                <td class="text-end fw-bold {{ $movement->type == 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ $movement->type == 'income' ? '+' : '-' }} ${{ number_format($movement->amount, 2, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold">
                                    ${{ number_format($movement->balance_after, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    Aún no hay movimientos registrados en sus billeteras.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

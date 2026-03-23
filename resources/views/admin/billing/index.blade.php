@extends('layouts.main')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom py-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-dark fw-bold">Ecosistema de Cobranza</h4>
                        <p class="text-secondary small mb-0">Gestión de cuotas societarias y estados de cuenta mensuales.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark rounded-pill px-4 btn-sm" data-bs-toggle="modal" data-bs-target="#feesConfigModal">
                            <i class="bi-gear-fill me-2"></i> Configurar Cuotas
                        </button>
                        <button class="btn btn-primary rounded-pill px-4 btn-sm shadow-sm">
                            <i class="bi-plus-circle-fill me-2"></i> Generar Masivo
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="border-0 px-3 py-3 rounded-start text-uppercase small text-secondary fw-bold">Colegiado</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold">Último Pago</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold text-center">Estado</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold text-end">Saldo Pendiente</th>
                                <th class="border-0 px-3 py-3 rounded-end text-uppercase small text-secondary fw-bold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collegiates as $collegiate)
                            @php
                                $lastPaid = $collegiate->dues->where('status', 'paid')->first();
                                $pendingAmount = $collegiate->pendingDues->sum('amount');
                                $isClean = $pendingAmount == 0;
                            @endphp
                            <tr class="border-bottom">
                                <td class="px-3 py-3 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-gradient-primary text-white rounded-pill d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px; font-weight: bold;">
                                            {{ substr($collegiate->first_name, 0, 1) }}{{ substr($collegiate->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</h6>
                                            <span class="text-secondary small">M: {{ $collegiate->registration_number }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 border-0">
                                    @if($lastPaid)
                                        <span class="text-dark d-block fw-semibold">${{ number_format($lastPaid->amount, 0, ',', '.') }}</span>
                                        <span class="text-secondary small text-lowercase">{{ $lastPaid->paid_at->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-secondary small">Sin pagos registrados</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 border-0 text-center">
                                    @if($isClean)
                                        <span class="badge rounded-pill bg-success-soft text-success border border-success-soft px-3" style="background: #e1f5e6;">
                                            <i class="bi-check-circle-fill me-1"></i> Al Día
                                        </span>
                                    @elseif($collegiate->pendingDues->where('status', 'overdue')->count() > 0)
                                        <span class="badge rounded-pill bg-danger-soft text-danger border border-danger-soft px-3" style="background: #feeaea;">
                                            <i class="bi-exclamation-triangle-fill me-1"></i> Moroso
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-soft text-warning border border-warning-soft px-3" style="background: #fff8e1;">
                                            <i class="bi-clock-fill me-1"></i> Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 border-0 text-end fw-bold {{ $isClean ? 'text-secondary opacity-50' : 'text-danger' }}">
                                    ${{ number_format($pendingAmount, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-3 border-0 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link link-secondary dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown">
                                            <i class="bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><a class="dropdown-item py-2" href="#"><i class="bi-eye me-2"></i> Ver Detalle</a></li>
                                            <li><a class="dropdown-item py-2" href="#"><i class="bi-receipt me-2"></i> Historial Pagos</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 text-primary" href="#"><i class="bi-send me-2"></i> Avisar por WhatsApp</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-3">
                    {{ $collegiates->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Métricas y Configuración -->
    <div class="col-12 col-xl-4">
        <div class="row g-4">
            <!-- Métricas Financieras -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-primary text-white position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="bi-currency-dollar" style="font-size: 5rem; margin-top: -1rem; margin-right: -1rem;"></i>
                    </div>
                    <div class="card-body p-4 position-relative">
                        <span class="text-uppercase small opacity-75 fw-semibold mb-2 d-block">Total Recaudado (Mes Actual)</span>
                        <h2 class="fw-bold mb-3">${{ number_format($stats['total_collected'], 0, ',', '.') }}</h2>
                        <div class="d-flex align-items-center gap-2 small">
                            <span class="badge bg-white text-primary rounded-pill px-2">+12% vs anterior</span>
                            <span class="opacity-75">en comparación al mes de Abril.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de Deuda -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                    <div class="card-body p-4">
                        <h6 class="text-secondary text-uppercase small fw-bold mb-4">Cartera Pendiente</h6>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark fw-semibold">Morosidad Activa</span>
                                <span class="text-danger fw-bold">${{ number_format($stats['total_overdue'], 0, ',', '.') }}</span>
                            </div>
                            <div class="text-secondary small d-block mb-2">Representa al {{ round($stats['count_overdue_users'] / ($stats['count_active'] ?: 1) * 100) }}% de la base activa.</div>
                            <div class="progress rounded-pill bg-light" style="height: 6px;">
                                <div class="progress-bar bg-danger rounded-pill" style="width: {{ $stats['count_overdue_users'] / ($stats['count_active'] ?: 1) * 100 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-dark fw-semibold">Por Recaudar</span>
                                <span class="text-primary fw-bold">${{ number_format($stats['total_to_collect'], 0, ',', '.') }}</span>
                            </div>
                            <div class="text-secondary small d-block mb-2">Cuotas pendientes del mes corriente.</div>
                            <div class="progress rounded-pill bg-light" style="height: 6px;">
                                <div class="progress-bar bg-primary rounded-pill" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuración Vigente -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-pill p-2 me-3">
                                <i class="bi-calendar-check-fill text-white fs-5 px-1"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Cuota Societaria Vigente</h6>
                                <span class="small opacity-50">Válida desde {{ optional($activeFee)->effective_date?->format('F Y') ?: 'Ahora' }}</span>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-3">${{ number_format(optional($activeFee)->amount ?: 0, 0, ',', '.') }} <small class="fs-6 opacity-50 fw-normal">/ mensual</small></h3>
                        <p class="small opacity-75 mb-0 font-italic">Este valor se aplica a todos los colegiados activos automáticamente el día 1 de cada mes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Configuración -->
<div class="modal fade" id="feesConfigModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold">Actualizar Cuota Societaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.billing.update_fee') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-4">El nuevo monto se aplicará a partir de la próxima facturación generada. Los colegiados serán notificados vía email y su historial reflejará el cambio.</p>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small text-uppercase">Nuevo Monto Mensual ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi-cash"></i></span>
                            <input type="number" name="amount" class="form-control border-0 bg-light" placeholder="Ej: 30000" value="{{ optional($activeFee)->amount }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar y Notificar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.main')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-0 text-dark fw-bold">Ecosistema de Cobranza</h4>
                        <p class="text-secondary xx-small mb-0 text-uppercase ls-1">Gestión de cuotas societarias y estados de cuenta</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark rounded-pill px-3 btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#customDueModal">
                            <i class="bi-plus-circle me-1"></i> Nueva Novedad / Deuda
                        </button>
                        <button class="btn btn-outline-dark rounded-pill px-3 btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#feesConfigModal">
                            <i class="bi-gear-fill me-1"></i> Cuotas
                        </button>
                        <button class="btn btn-outline-dark rounded-pill px-3 btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#conceptsModal">
                            <i class="bi-tags-fill me-1"></i> Conceptos
                        </button>
                        <form action="{{ route('admin.billing.generate_dues') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill px-3 btn-sm shadow-sm fw-bold" onclick="return confirm('¿Generar cuotas societarias para todos los socios activos? Esto generará deuda en sus estados de cuenta.')">
                                <i class="bi-lightning-charge-fill me-1"></i> Generar Masivo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Barra de Herramientas: Búsqueda y Filtros -->
                <div class="p-3 bg-light-subtle border-bottom">
                    <form action="{{ route('admin.billing.index') }}" method="GET" class="row g-2">
                        <div class="col-md-7">
                            <div class="input-group input-group-sm shadow-none border rounded-pill px-2 bg-white">
                                <span class="input-group-text bg-transparent border-0 text-secondary"><i class="bi-search"></i></span>
                                <input type="text" id="searchInput" name="search" class="form-control border-0 shadow-none py-1" placeholder="Buscar por Nombre, DNI o Matrícula..." value="{{ $search }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select form-select-sm border rounded-pill shadow-none px-3 bg-white" onchange="this.form.submit()">
                                <option value="">Todos los Estados</option>
                                <option value="compliant" {{ $statusFilter == 'compliant' ? 'selected' : '' }}>Al Día</option>
                                <option value="overdue" {{ $statusFilter == 'overdue' ? 'selected' : '' }}>Morosos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 fw-bold">Buscar</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="border-0 px-3 py-2 text-uppercase xx-small text-white fw-bold ls-1">Colegiado</th>
                                <th class="border-0 px-3 py-2 text-uppercase xx-small text-white fw-bold ls-1 text-center">Último Pago</th>
                                <th class="border-0 px-3 py-2 text-uppercase xx-small text-white fw-bold ls-1 text-center">Estado</th>
                                <th class="border-0 px-3 py-2 text-uppercase xx-small text-white fw-bold ls-1 text-center">S. Pendiente</th>
                                <th class="border-0 px-3 py-2 text-uppercase xx-small text-white fw-bold ls-1 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="membersTable">
                            @fragment('membersTable')
                            @foreach($collegiates as $collegiate)
                            @php
                                $lastPaid = $collegiate->dues->where('status', 'paid')->first();
                                $pendingAmount = $collegiate->pendingDues->sum('amount');
                                $isClean = $pendingAmount == 0;
                            @endphp
                            <tr class="border-bottom">
                                <td class="px-3 py-2 border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-pill d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: 800; min-width: 32px;">
                                            {{ strtoupper(substr($collegiate->first_name, 0, 1)) }}{{ strtoupper(substr($collegiate->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark small">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</h6>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-secondary xx-small fw-semibold">MAT: {{ $collegiate->registration_number }}</span>
                                                @if($collegiate->isSanctioned())
                                                    <span class="badge bg-danger p-0 rounded-circle shadow-sm animate__animated animate__flash animate__infinite" style="width: 8px; height: 8px;" title="SANCIONADO POR ÉTICA"></span>
                                                @endif
                                                @if($collegiate->paymentAgreements()->where('status', 'active')->exists())
                                                    <span class="badge bg-info p-0 rounded-circle shadow-sm" style="width: 8px; height: 8px;" title="CON CONVENIO DE PAGO"></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2 border-0 text-center">
                                    @if($lastPaid)
                                        <span class="text-dark d-block fw-bold small">${{ number_format($lastPaid->amount, 0, ',', '.') }}</span>
                                        <span class="text-secondary xx-small">{{ $lastPaid->paid_at ? $lastPaid->paid_at->format('d/m/Y') : 'Sin fecha' }}</span>
                                    @else
                                        <span class="text-finance-clean small fw-bold">S/P</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 border-0 text-center">
                                    @if($collegiate->isSanctioned())
                                        <span class="badge rounded-pill bg-dark text-white border border-dark px-2 py-1" style="font-size: 0.65rem;">
                                            <i class="bi-shield-x me-1"></i> SUSPENDIDO
                                        </span>
                                    @elseif($isClean)
                                        <span class="badge rounded-pill bg-success-soft text-success border border-success-soft px-2 py-1" style="background: #e1f5e6; font-size: 0.65rem;">
                                            <i class="bi-check-circle-fill me-1"></i> AL DÍA
                                        </span>
                                    @elseif($collegiate->paymentAgreements()->where('status', 'active')->exists())
                                        <span class="badge rounded-pill bg-info-soft text-info border border-info-soft px-2 py-1" style="background: #e0f2f1; font-size: 0.65rem;">
                                            <i class="bi-file-earmark-text-fill me-1"></i> CONVENIO
                                        </span>
                                    @elseif($collegiate->pendingDues->where('status', 'overdue')->count() > 0)
                                        <span class="badge rounded-pill bg-danger-soft text-danger border border-danger-soft px-2 py-1" style="background: #feeaea; font-size: 0.65rem;">
                                            <i class="bi-exclamation-triangle-fill me-1"></i> MOROSO
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-soft text-warning border border-warning-soft px-2 py-1" style="background: #fff8e1; font-size: 0.65rem;">
                                            <i class="bi-clock-fill me-1"></i> PEND.
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 border-0 text-center fw-bold small {{ $isClean ? 'text-finance-clean' : 'text-danger' }}">
                                    ${{ number_format($pendingAmount, 0, ',', '.') }}
                                </td>
                                <td class="px-3 py-2 border-0 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-link link-secondary dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown">
                                            <i class="bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                             <li><a class="dropdown-item py-2" href="{{ route('collegiates.show', $collegiate->id) }}"><i class="bi-eye me-2"></i> Ver Detalle</a></li>
                                             <li><a class="dropdown-item py-2" href="{{ route('admin.billing.history', $collegiate->id) }}"><i class="bi-receipt me-2"></i> Historial Pagos</a></li>
                                             <li><hr class="dropdown-divider"></li>
                                             <li><a class="dropdown-item py-2 text-primary" href="https://wa.me/{{ $collegiate->phone }}?text=Hola%20{{ $collegiate->first_name }},%20te%20escribimos%20del%20Colegio%20para..." target="_blank"><i class="bi-send me-2"></i> Avisar por WhatsApp</a></li>
                                         </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endfragment
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

<!-- Modal Conceptos Facturables -->
<div class="modal fade" id="conceptsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <h5 class="modal-title fw-bold"><i class="bi-tags-fill me-2 text-primary"></i>Catálogo de Conceptos Facturables</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive mb-4">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Concepto</th>
                                <th>Tipo</th>
                                <th>Monto Base</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($billingConcepts as $concept)
                                <tr>
                                    <td class="fw-bold">{{ $concept->name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @if($concept->type == 'enrollment') Inscripción
                                            @elseif($concept->type == 'annual') Anual
                                            @elseif($concept->type == 'penalty') Multa/Recargo
                                            @else Otro @endif
                                        </span>
                                    </td>
                                    <td class="text-primary fw-bold">${{ number_format($concept->default_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($concept->is_active)
                                            <span class="badge bg-success-soft text-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger-soft text-danger">Suspendido</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('admin.billing.toggle_concept', $concept) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $concept->is_active ? 'warning' : 'success' }} px-2 py-1" title="{{ $concept->is_active ? 'Suspender' : 'Activar' }}">
                                                    <i class="bi-{{ $concept->is_active ? 'pause-fill' : 'play-fill' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.billing.destroy_concept', $concept) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar concepto definitivamente? Solo posible si no ha sido usado.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Eliminar">
                                                    <i class="bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted small py-3">No hay conceptos creados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <hr>
                <h6 class="fw-bold mb-3">Crear Nuevo Concepto</h6>
                <form action="{{ route('admin.billing.store_concept') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-5">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Ej. Inscripción Inicial" required>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="other">Otro</option>
                            <option value="enrollment">Inscripción / Matrícula</option>
                            <option value="annual">Pago Anual Anticipado</option>
                            <option value="penalty">Multa / Rehabilitación</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="default_amount" class="form-control form-control-sm" placeholder="$ Monto" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cargar Deuda Histórica / Novedad -->
<div class="modal fade" id="customDueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.billing.store_custom') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-bottom py-3 bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="bi-plus-circle me-2 text-primary"></i>Nueva Novedad / Deuda Histórica</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Colegiado a quien se le aplica</label>
                    <select name="collegiate_id" class="form-select" required>
                        <option value="">Seleccionar Colegiado...</option>
                        @foreach(\App\Models\Collegiate::where('school_id', auth()->user()->school_id)->where('status', 'active')->orderBy('last_name')->get() as $c)
                            <option value="{{ $c->id }}">{{ $c->last_name }} {{ $c->first_name }} (MAT: {{ $c->registration_number }})</option>
                        @endforeach
                    </select>
                    <div class="form-text">Busque y seleccione al profesional.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Concepto de Cobro</label>
                    <select name="billing_concept_id" id="billingConceptSelect" class="form-select" onchange="updateConceptAmount()">
                        <option value="">-- Concepto Manual (Escribir debajo) --</option>
                        @foreach($billingConcepts->where('is_active', true) as $concept)
                            <option value="{{ $concept->id }}" data-amount="{{ $concept->default_amount }}">
                                {{ $concept->name }} (${{ number_format($concept->default_amount, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3" id="manualConceptWrapper">
                    <label class="form-label small fw-bold text-muted">Descripción del Concepto Manual</label>
                    <input type="text" name="concept" id="manualConceptInput" class="form-control" placeholder="Ej. Saldo deudor 2024 / Cuota inscripción curso">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Monto ($)</label>
                        <input type="number" step="0.01" name="amount" class="form-control fw-bold text-primary" required placeholder="0.00">
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Fecha de Vencimiento</label>
                        <input type="date" name="due_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                
                <div class="alert alert-info border-0 rounded-3 small mb-0 mt-2">
                    <i class="bi-info-circle-fill me-2"></i> Esta deuda aparecerá en el estado de cuenta del profesional inmediatamente.
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Generar Cargo</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('membersTable');
    const statusSelect = document.querySelector('select[name="status"]');
    const instance = new Mark(tableBody);

    let debounceTimer;

    function performSearch() {
        const query = searchInput.value;
        const status = statusSelect.value;
        
        // Mostrar estado de carga (opcional)
        tableBody.style.opacity = '0.5';

        fetch(`{{ route('admin.billing.index') }}?search=${encodeURIComponent(query)}&status=${status}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.getElementById('membersTable');
            const newPagination = doc.querySelector('.pagination-container');
            
            if (newTableBody) {
                tableBody.innerHTML = newTableBody.innerHTML;
                
                // Aplicar Resaltado
                instance.unmark();
                if (query.length >= 2) {
                    instance.mark(query, {
                        "accuracy": "partially",
                        "diacritics": true, // IMPORTANTE: Resalta aunque tenga tildes
                        "synonyms": {"o": "ó", "a": "á", "e": "é", "i": "í", "u": "ú"}
                    });
                }
            }
            
            tableBody.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
            tableBody.style.opacity = '1';
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 300);
    });

    statusSelect.addEventListener('change', performSearch);
});

function updateConceptAmount() {
    const select = document.getElementById('billingConceptSelect');
    const amountInput = document.querySelector('input[name="amount"]');
    const manualInputWrapper = document.getElementById('manualConceptWrapper');
    const manualInput = document.getElementById('manualConceptInput');

    if (select.value === "") {
        // Manual entry
        manualInputWrapper.style.display = 'block';
        manualInput.setAttribute('required', 'required');
        amountInput.value = '';
    } else {
        // Selected concept
        manualInputWrapper.style.display = 'none';
        manualInput.removeAttribute('required');
        manualInput.value = '';
        
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption.dataset.amount) {
            amountInput.value = selectedOption.dataset.amount;
        }
    }
}
document.addEventListener('DOMContentLoaded', updateConceptAmount);
</script>
@endsection

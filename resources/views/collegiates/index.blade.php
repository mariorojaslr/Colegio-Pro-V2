@extends('layouts.main')

@section('content')
<style>
@keyframes fadeSlideDown {
    0% { opacity: 0; transform: translateY(-10px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>
<div class="container-fluid py-4">
    {{-- Encabezado del Padrón y Acciones --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">Padrón <span class="text-primary">Profesional</span></h1>
            <p class="x-small text-muted fw-medium mb-0">Gestión integral de matriculados y estados.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('collegiates.export') }}" class="btn btn-outline-dark rounded-pill px-3 fw-bold x-small shadow-sm"><i class="bi bi-download me-1"></i> Excel</a>
            <a href="{{ route('collegiates.import') }}" class="btn btn-primary rounded-pill px-3 fw-bold x-small shadow-sm"><i class="bi bi-file-earmark-arrow-up me-1"></i> Importar</a>
        </div>
    </div>

    {{-- Filtros Rápidos Compactos --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index') }}" class="text-decoration-none">
                <div class="card-prestige p-2 {{ !request('filter') ? 'bg-primary text-white border-primary shadow-lg' : 'bg-white text-dark border' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['total'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Total</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'morosos']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 {{ request('filter') === 'morosos' ? 'bg-danger text-white border-danger shadow-lg' : 'bg-white text-danger border border-danger border-opacity-25' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['debt_fees'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Deuda Cuotas</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'sin_papeles']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 {{ request('filter') === 'sin_papeles' ? 'bg-warning text-dark border-warning shadow-lg' : 'bg-white text-warning border border-warning border-opacity-25' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['debt_docs'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Deuda Docs</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'habilitados']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 {{ request('filter') === 'habilitados' ? 'bg-success text-white border-success shadow-lg' : 'bg-white text-success border border-success border-opacity-25' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['enabled'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Habilitados</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Buscador y Listado --}}
    <div class="card-prestige p-0 bg-white shadow-sm overflow-hidden" style="border-radius: 30px">
        <div class="p-4 border-bottom bg-light">
            <form action="{{ route('collegiates.index') }}" method="GET" class="row g-3 align-items-center">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="col-md-7">
                    <div class="input-group border border-secondary border-opacity-25 rounded-pill overflow-hidden shadow-sm bg-white academy-search-group">
                        <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 py-2 bg-transparent" 
                               placeholder="Buscar por Nombre, DNI o Matrícula..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="per_page" class="form-select border border-secondary border-opacity-25 rounded-pill shadow-sm bg-white" onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 filas</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 filas</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 filas</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 filas</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 filas</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 filas</option>
                    </select>
                </div>
                <div class="col-md-3 text-end text-nowrap">
                    <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">Buscar</button>
                    @if(request('search') || request('filter'))
                        <a href="{{ route('collegiates.index') }}" class="btn btn-outline-danger rounded-pill px-3 ms-1 shadow-sm" title="Limpiar Búsqueda">
                            <i class="bi bi-trash3"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive" id="tableContainer">
            @fragment('membersTable')
            <table class="table table-hover align-middle m-0" id="collegiateTable">
                <thead class="bg-light bg-opacity-50">
                    <tr class="small fw-bold text-muted uppercase ls-1 text-nowrap">
                        <th class="py-1 px-4" style="width: 250px;">Nombre y Apellido</th>
                        <th class="py-1" style="width: 100px;">Matrícula</th>
                        <th class="py-1">Documento / Contacto</th>
                        <th class="py-1 text-center" style="width: 100px;">Finanzas</th>
                        <th class="py-1 text-center" style="width: 120px;">Documentos</th>
                        <th class="py-1 text-center" style="width: 100px;">Ética</th>
                        <th class="py-1 text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($collegiates as $col)
                        @php
                            // Lógica de Finanzas
                            $pendingDues = $col->dues->whereIn('status', ['pending', 'overdue']);
                            $overdueCount = $pendingDues->where('due_date', '<', now())->count();
                            $soonCount = $pendingDues->where('due_date', '>=', now())->where('due_date', '<=', now()->addDays(7))->count();
                            
                            $financeColor = 'success';
                            $financeText = 'Al día';
                            if ($overdueCount > 0) {
                                $financeColor = 'danger';
                                $financeText = 'Deuda';
                            } elseif ($soonCount > 0) {
                                $financeColor = 'warning';
                                $financeText = 'Vence pronto';
                            }

                            // Lógica de Documentación
                            $requiredDocsCount = $requirements->count();
                            $approvedDocsCount = $col->documents->where('status', 'approved')->count();
                            $docsProgress = $requiredDocsCount > 0 ? ($approvedDocsCount / $requiredDocsCount) * 100 : 100;
                            
                            $docsColor = 'success';
                            if ($docsProgress < 50) $docsColor = 'danger';
                            elseif ($docsProgress < 100) $docsColor = 'warning';

                            // Lógica de Ética
                            $activeSanctions = $col->sanctions;
                            $ethicsColor = 'success';
                            $ethicsText = 'Limpio';
                            if ($activeSanctions->count() > 0) {
                                if ($activeSanctions->where('severity', 'grave')->count() > 0) {
                                    $ethicsColor = 'danger';
                                    $ethicsText = 'Sanción Grave';
                                } else {
                                    $ethicsColor = 'warning';
                                    $ethicsText = 'Sanción Leve';
                                }
                            }
                        @endphp
                    <tr class="border-bottom border-light table-row-main" id="row-{{ $col->id }}">
                        <td class="py-2 px-4 searchable" data-field="name">
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $col->last_name }}, {{ $col->first_name }}</span>
                                <span class="x-small text-muted fw-medium">{{ $col->professional_situation ?? 'Activo' }}</span>
                            </div>
                        </td>
                        <td class="py-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">{{ $col->registration_number }}</span>
                        </td>
                        <td class="py-2 searchable" data-field="contact">
                            <div class="d-flex flex-column">
                                <span class="x-small text-dark fw-bold mb-1">DNI {{ is_numeric($col->dni) ? number_format((float)$col->dni, 0, ',', '.') : $col->dni }}</span>
                                <div class="d-flex gap-2">
                                    <span class="x-small text-muted"><i class="bi bi-envelope me-1"></i>{{ $col->email }}</span>
                                    @if($col->phone)
                                        <span class="x-small text-muted"><i class="bi bi-whatsapp me-1"></i>{{ $col->phone }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-2 text-center align-middle">
                            <button class="btn btn-{{ $financeColor }} rounded-pill px-2 py-0 border-0 shadow-sm fw-bold w-100 btn-indicator" style="font-size: 0.75rem; height: 22px; line-height: 22px;" onclick="togglePanel({{ $col->id }}, 'finanzas')">
                                <i class="bi bi-currency-dollar me-1"></i> {{ $financeText }}
                            </button>
                        </td>
                        <td class="py-2 text-center align-middle" style="width: 120px;">
                            <div class="cursor-pointer px-1" onclick="togglePanel({{ $col->id }}, 'docs')" title="Ver detalle de documentos">
                                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.65rem;">
                                    <span class="text-muted fw-bold">PAPELERÍA</span>
                                    <span class="fw-bold text-{{ $docsColor }}">{{ $approvedDocsCount }} de {{ $requiredDocsCount }}</span>
                                </div>
                                <div class="progress bg-secondary bg-opacity-10 rounded-pill overflow-visible" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $docsColor }} rounded-pill position-relative" role="progressbar" style="width: {{ $docsProgress }}%;">
                                        @if($docsProgress == 100)
                                            <span class="position-absolute end-0 top-50 translate-middle-y me-n2 text-success"><i class="bi bi-check-circle-fill" style="font-size: 10px;"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 text-center align-middle">
                            <button class="btn btn-{{ $ethicsColor }} rounded-pill px-2 py-0 border-0 shadow-sm fw-bold w-100 btn-indicator" style="font-size: 0.75rem; height: 22px; line-height: 22px;" onclick="togglePanel({{ $col->id }}, 'etica')">
                                <i class="bi bi-shield-check me-1"></i> {{ $ethicsText }}
                            </button>
                        </td>
                        <td class="py-2 text-end px-4 align-middle">
                            <a href="{{ route('collegiates.show', $col) }}" class="btn btn-outline-primary rounded-pill px-3 py-0 fw-bold shadow-sm" style="font-size: 0.75rem; height: 24px; line-height: 22px;">
                                Ver Perfil
                            </a>
                        </td>
                    </tr>
                    
                    {{-- ACORDEÓN EXPANDIBLE --}}
                    <tr id="panel-row-{{ $col->id }}" class="collapse panel-row border-0">
                        <td colspan="7" class="p-0 border-0 bg-transparent">
                            <div class="mx-3 mb-3 mt-1 p-4 rounded-4 shadow bg-white border-start border-4 border-primary position-relative" style="animation: fadeSlideDown 0.3s ease-out; margin-left: 20px !important;">
                                
                                {{-- PANEL FINANZAS --}}
                                <div id="panel-finanzas-{{ $col->id }}" class="collegiate-panel d-none">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-wallet2 me-2"></i> Estado Financiero</h6>
                                    @if($pendingDues->isEmpty())
                                        <div class="alert alert-success border-0 py-2 x-small fw-bold mb-0"><i class="bi bi-check-circle-fill me-2"></i> El colegiado está al día con sus cuotas.</div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                <thead class="text-muted x-small uppercase">
                                                    <tr>
                                                        <th>Concepto</th>
                                                        <th>Vencimiento</th>
                                                        <th>Monto</th>
                                                        <th class="text-end">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($pendingDues as $due)
                                                    <tr class="border-bottom border-light align-middle">
                                                        <td class="fw-medium x-small text-dark">Cuota {{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}</td>
                                                        <td class="x-small {{ $due->due_date < now() ? 'text-danger fw-bold' : 'text-warning fw-bold' }}">
                                                            {{ \Carbon\Carbon::parse($due->due_date)->format('d/m/Y') }}
                                                            @if($due->due_date < now()) (Vencida) @endif
                                                        </td>
                                                        <td class="fw-bold text-dark x-small">$ {{ number_format($due->amount, 2, ',', '.') }}</td>
                                                        <td class="text-end">
                                                            <button class="btn btn-primary btn-sm x-small py-0 px-2 rounded-pill fw-bold" onclick="alert('Función de cobro en desarrollo')">Pagar</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-outline-danger btn-sm x-small py-1 px-3 rounded-pill fw-bold" onclick="alert('Notificación enviada')"><i class="bi bi-bell-fill me-1"></i> Avisar Deuda</button>
                                        </div>
                                    @endif
                                </div>

                                {{-- PANEL DOCUMENTACIÓN --}}
                                <div id="panel-docs-{{ $col->id }}" class="collegiate-panel d-none">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-folder-check me-2"></i> Requisitos Documentales</h6>
                                    <div class="row g-2">
                                        @foreach($requirements as $req)
                                            @php
                                                $doc = $col->documents->where('compliance_requirement_id', $req->id)->first();
                                                $statusClass = 'bg-danger text-white';
                                                $statusIcon = 'bi-x-circle-fill';
                                                $statusText = 'Faltante';
                                                if($doc && $doc->status === 'approved') {
                                                    $statusClass = 'bg-success text-white';
                                                    $statusIcon = 'bi-check-circle-fill';
                                                    $statusText = 'Aprobado';
                                                } elseif($doc && $doc->status === 'pending') {
                                                    $statusClass = 'bg-warning text-dark';
                                                    $statusIcon = 'bi-clock-fill';
                                                    $statusText = 'En Revisión';
                                                }
                                            @endphp
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center p-2 border rounded bg-white">
                                                    <div class="rounded-circle {{ $statusClass }} d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                                        <i class="bi {{ $statusIcon }} x-small"></i>
                                                    </div>
                                                    <div class="flex-grow-1 x-small text-truncate" title="{{ $req->name }}">
                                                        <span class="fw-bold d-block text-dark">{{ $req->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('collegiates.show', $col) }}#documentos" class="btn btn-outline-primary btn-sm x-small py-1 px-3 rounded-pill fw-bold">Gestionar Documentos</a>
                                    </div>
                                </div>

                                {{-- PANEL ÉTICA --}}
                                <div id="panel-etica-{{ $col->id }}" class="collegiate-panel d-none">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-shield-check me-2"></i> Historial de Ética y Disciplina</h6>
                                    @if($activeSanctions->isEmpty())
                                        <div class="alert alert-success border-0 py-2 x-small fw-bold mb-0"><i class="bi bi-check-circle-fill me-2"></i> El colegiado no registra sanciones activas.</div>
                                    @else
                                        <ul class="list-group list-group-flush mb-0">
                                            @foreach($activeSanctions as $sanction)
                                            <li class="list-group-item bg-transparent px-0 border-light text-dark x-small">
                                                <span class="badge {{ $sanction->severity === 'grave' ? 'bg-danger' : 'bg-warning text-dark' }} me-2 uppercase">{{ $sanction->severity }}</span>
                                                <span class="fw-bold">{{ \Carbon\Carbon::parse($sanction->start_date)->format('d/m/Y') }}</span> - 
                                                {{ $sanction->reason }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                            </div>
                        </td>
                    </tr>
                    @empty
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800505.png" alt="Vacío" width="150" class="mb-3 opacity-25">
                            <p class="text-muted fw-bold">No se encontraron resultados del padrón.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top bg-light bg-opacity-50" id="paginationLinks">
            {{ $collegiates->onEachSide(1)->appends(request()->query())->links() }}
        </div>
        @endfragment
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .uppercase { text-transform: uppercase; }
    .x-small { font-size: 0.75rem; }
    .card-prestige { border-radius: 20px; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); border: 1px solid rgba(0,0,0,0.05); }
    .card-prestige:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
    
    body.dark-mode .card-prestige {
        background: #000 !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    body.dark-mode .academy-search-group {
        background: #000 !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    body.dark-mode #collegiateTable thead {
        background: #000 !important;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-mode #collegiateTable thead th {
        color: rgba(255, 255, 255, 0.6) !important;
    }

    body.dark-mode #collegiateTable tr:hover { 
        background-color: rgba(255, 255, 255, 0.05) !important; 
    }

    mark { background: #fff3cd !important; padding: 0.1em 0 !important; color: #856404; font-weight: 800; border-radius: 2px; }
    
    body.dark-mode mark {
        background: #2563eb !important;
        color: white !important;
    }

    #collegiateTable tr.table-row-main { transition: background 0.2s ease; border-bottom: 1px solid rgba(0,0,0,0.03); }
    #collegiateTable tr.table-row-main:hover { background-color: rgba(248, 250, 252, 0.8) !important; }
    
    body.dark-mode #collegiateTable tr.table-row-main {
        border-bottom-color: rgba(255, 255, 255, 0.05) !important;
    }

    .shadow-inner { box-shadow: inset 0px 10px 15px -10px rgba(0,0,0,0.05); }
    .btn-indicator { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .btn-indicator:hover { transform: scale(1.05); }

    .paddinc-lc { padding-left: 1rem !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const tableBody = document.getElementById('tableBody');
    const paginationLinks = document.getElementById('paginationLinks');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const searchTerm = this.value;
            const filter = new URLSearchParams(window.location.search).get('filter') || '';
            const perPage = document.querySelector('select[name="per_page"]').value;
            
            // Animación de carga sutil
            tableBody.style.opacity = '0.5';
            
            fetch(`{{ route('collegiates.index') }}?search=${searchTerm}&filter=${filter}&per_page=${perPage}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newBody = doc.getElementById('tableBody');
                const newPagination = doc.getElementById('paginationLinks');
                
                tableBody.innerHTML = newBody.innerHTML;
                paginationLinks.innerHTML = newPagination.innerHTML;
                tableBody.style.opacity = '1';
                
                // Aplicar Resaltado Amarillo - ¡Ahora desde el primer carácter!
                if (searchTerm.length > 0) {
                    highlightText(searchTerm);
                }
            });
        }, 300);
    });

    function highlightText(term) {
        const elements = document.querySelectorAll('.searchable');
        const regex = new RegExp(`(${term})`, 'gi');
        
        elements.forEach(el => {
            const children = el.querySelectorAll('div, span');
            children.forEach(child => {
                if (child.childNodes.length === 1 && child.childNodes[0].nodeType === 3) {
                    const originalText = child.textContent;
                    if (regex.test(originalText)) {
                        child.innerHTML = originalText.replace(regex, '<mark>$1</mark>');
                    }
                }
            });
        });
    }

    // Funcionalidad global del Acordeón para evitar problemas con la paginación dinámica
    window.togglePanel = function(colId, panelName) {
        const row = document.getElementById('panel-row-' + colId);
        if (!row) return;

        // Instancia de Bootstrap Collapse o creamos una si no existe
        let bsCollapse = bootstrap.Collapse.getInstance(row);
        if (!bsCollapse) {
            bsCollapse = new bootstrap.Collapse(row, { toggle: false });
        }

        const isCurrentlyOpen = row.classList.contains('show');
        const targetPanel = document.getElementById('panel-' + panelName + '-' + colId);
        const allPanels = row.querySelectorAll('.collegiate-panel');

        if (isCurrentlyOpen) {
            // Si el acordeón está abierto, vemos si el panel clickeado ya está visible
            if (!targetPanel.classList.contains('d-none')) {
                // Era el mismo, cerramos todo
                bsCollapse.hide();
            } else {
                // Era distinto, ocultamos los demás y mostramos este sin cerrar el acordeón
                allPanels.forEach(p => p.classList.add('d-none'));
                targetPanel.classList.remove('d-none');
            }
        } else {
            // Estaba cerrado. Mostramos el correcto y abrimos el acordeón.
            // Para mantener el "acordeón estricto", cerramos todos los demás acordeones
            document.querySelectorAll('.panel-row.show').forEach(openRow => {
                if (openRow.id !== 'panel-row-' + colId) {
                    bootstrap.Collapse.getInstance(openRow)?.hide();
                }
            });

            allPanels.forEach(p => p.classList.add('d-none'));
            targetPanel.classList.remove('d-none');
            bsCollapse.show();
        }
    };
});
</script>
@endsection

@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 bg-light-subtle">
    {{-- Encabezado del Padrón --}}
    <div class="row mb-5 align-items-end">
        <div class="col-lg-7">
            <h1 class="display-6 fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">Padrón <span class="text-primary">Profesional</span></h1>
            <p class="lead text-muted small fw-medium">Gestión integral de matriculados, estados de deuda y cumplimiento documental.</p>
        </div>
        <div class="col-lg-5 text-lg-end">
            <div class="d-flex gap-2 justify-content-lg-end">
                <a href="#" class="btn btn-outline-dark rounded-pill px-4 fw-bold small shadow-sm"><i class="bi bi-download me-2"></i> Descargar Excel</a>
                <a href="{{ route('collegiates.import') }}" class="btn btn-primary rounded-pill px-4 fw-bold small shadow-sm"><i class="bi bi-file-earmark-arrow-up me-2"></i> Importar Masivo</a>
            </div>
        </div>
    </div>

    {{-- Filtros Rápidos (Estilo Auditoría) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <a href="{{ route('collegiates.index') }}" class="text-decoration-none">
                <div class="card-prestige p-4 border-0 {{ !request('filter') ? 'bg-primary text-white' : 'bg-white text-dark' }} transition-all">
                    <h2 class="fw-bold mb-0">Total</h2>
                    <p class="mb-0 small opacity-75 fw-bold">Todos los Matriculados</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'morosos']) }}" class="text-decoration-none">
                <div class="card-prestige p-4 border-0 {{ request('filter') === 'morosos' ? 'bg-danger text-white' : 'bg-white text-danger' }} transition-all">
                    <h2 class="fw-bold mb-0">Deuda Cuotas</h2>
                    <p class="mb-0 small opacity-75 fw-bold">Pendientes de Pago</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'sin_papeles']) }}" class="text-decoration-none">
                <div class="card-prestige p-4 border-0 {{ request('filter') === 'sin_papeles' ? 'bg-warning text-dark' : 'bg-white text-warning' }} transition-all">
                    <h2 class="fw-bold mb-0">Deuda Docs</h2>
                    <p class="mb-0 small opacity-75 fw-bold">Faltan Requisitos</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'habilitados']) }}" class="text-decoration-none">
                <div class="card-prestige p-4 border-0 {{ request('filter') === 'habilitados' ? 'bg-success text-white' : 'bg-white text-success' }} transition-all">
                    <h2 class="fw-bold mb-0">Habilitados</h2>
                    <p class="mb-0 small opacity-75 fw-bold">Cumplimiento 100%</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Buscador y Listado --}}
    <div class="card-prestige p-0 border-0 bg-white shadow-sm overflow-hidden" style="border-radius: 40px">
        <div class="p-4 border-bottom bg-light">
            <form action="{{ route('collegiates.index') }}" method="GET" class="row g-3">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-pill py-2" 
                               placeholder="Buscar por Nombre, DNI o Matrícula..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold">Buscar</button>
                    @if(request('search') || request('filter'))
                        <a href="{{ route('collegiates.index') }}" class="btn btn-light rounded-pill px-3 ms-2"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive" id="tableContainer">
            <table class="table table-hover align-middle m-0" id="collegiateTable">
                <thead class="bg-light bg-opacity-50">
                    <tr class="small fw-bold text-muted uppercase ls-1">
                        <th class="py-3 px-4">Estado</th>
                        <th class="py-3">Profesional (Matrícula)</th>
                        <th class="py-3">Documento / Contacto</th>
                        <th class="py-3">Ubicación</th>
                        <th class="py-3 text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($collegiates as $col)
                    <tr>
                        <td class="py-4 px-4">
                            @if($col->is_fees_compliant && $col->is_fully_documented && $col->is_ethics_compliant)
                                <div class="bg-success rounded-circle shadow-sm" style="width: 15px; height: 15px" title="Habilitado"></div>
                            @elseif(!$col->is_fees_compliant)
                                <div class="bg-danger rounded-circle shadow-sm" style="width: 15px; height: 15px" title="Deuda Cuotas"></div>
                            @else
                                <div class="bg-warning rounded-circle shadow-sm" style="width: 15px; height: 15px" title="Deuda Documental"></div>
                            @endif
                        </td>
                        <td class="py-4 searchable" data-field="name">
                            <div class="fw-bold text-dark fs-6">{{ $col->last_name }}, {{ $col->first_name }}</div>
                            <div class="small fw-bold text-primary">MAT: {{ $col->registration_number }}</div>
                        </td>
                        <td class="py-4 searchable" data-field="contact">
                            <div class="small text-muted mb-1">DNI {{ number_format($col->dni, 0, ',', '.') }}</div>
                            <div class="small text-muted"><i class="bi bi-envelope me-1"></i> {{ $col->email }}</div>
                            @if($col->phone)
                                <div class="small text-muted mt-1"><i class="bi bi-phone me-1"></i> {{ $col->phone }}</div>
                            @endif
                        </td>
                        <td class="py-4">
                            <div class="small fw-medium">{{ $col->professional_situation ?? 'Activo' }}</div>
                        </td>
                        <td class="py-4 text-end px-4">
                            <a href="{{ route('collegiates.show', $col) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-bold">Ficha <i class="bi bi-chevron-right ms-1"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center">
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
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .uppercase { text-transform: uppercase; }
    .card-prestige { border-radius: 25px; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .card-prestige:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    mark { background: #fff3cd !important; padding: 0.1em 0 !important; color: #856404; font-weight: 800; border-radius: 2px; }
    #collegiateTable tr { transition: background 0.2s ease; }
    #collegiateTable tr:hover { background-color: rgba(248, 250, 252, 0.8) !important; }
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
            
            // Animación de carga sutil
            tableBody.style.opacity = '0.5';
            
            fetch(`{{ route('collegiates.index') }}?search=${searchTerm}&filter=${filter}`, {
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
                
                // Aplicar Resaltado Amarillo
                if (searchTerm.length > 2) {
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
});
</script>
@endsection

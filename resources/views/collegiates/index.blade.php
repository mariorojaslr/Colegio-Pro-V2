@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 bg-light-subtle">
    {{-- Encabezado del Padrón y Acciones --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0" style="font-family: 'Outfit', sans-serif;">Padrón <span class="text-primary">Profesional</span></h1>
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
                <div class="card-prestige p-2 border-0 {{ !request('filter') ? 'bg-primary text-white' : 'bg-white text-dark' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['total'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Total</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'morosos']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 border-0 {{ request('filter') === 'morosos' ? 'bg-danger text-white' : 'bg-white text-danger' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['debt_fees'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Deuda Cuotas</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'sin_papeles']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 border-0 {{ request('filter') === 'sin_papeles' ? 'bg-warning text-dark' : 'bg-white text-warning' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['debt_docs'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Deuda Docs</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('collegiates.index', ['filter' => 'habilitados']) }}" class="text-decoration-none">
                <div class="card-prestige p-2 border-0 {{ request('filter') === 'habilitados' ? 'bg-success text-white' : 'bg-white text-success' }} transition-all text-center">
                    <div class="fw-bold h5 mb-0">{{ number_format($stats['enabled'], 0, ',', '.') }}</div>
                    <div class="x-small opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">Habilitados</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Buscador y Listado --}}
    <div class="card-prestige p-0 border-0 bg-white shadow-sm overflow-hidden" style="border-radius: 40px">
        <div class="p-4 border-bottom bg-light">
            <form action="{{ route('collegiates.index') }}" method="GET" class="row g-3 align-items-center">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="col-md-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill paddinc-lc"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 rounded-end-pill py-2" 
                               placeholder="Buscar por Nombre, DNI o Matrícula..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="per_page" class="form-select rounded-pill" onchange="this.form.submit()">
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
            <table class="table table-hover align-middle m-0" id="collegiateTable">
                <thead class="bg-light bg-opacity-50">
                    <tr class="small fw-bold text-muted uppercase ls-1 text-nowrap">
                        <th class="py-1 px-4 text-center" style="width: 50px;">Estado</th>
                        <th class="py-1" style="width: 250px;">Nombre y Apellido</th>
                        <th class="py-1" style="width: 120px;">Matrícula</th>
                        <th class="py-1">Documento / Contacto</th>
                        <th class="py-1">Ubicación</th>
                        <th class="py-1 text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($collegiates as $col)
                    <tr class="border-bottom border-light">
                        <td class="py-1 px-4 text-center">
                            @if($col->is_fees_compliant && $col->is_fully_documented && $col->is_ethics_compliant)
                                <div class="bg-success rounded-circle shadow-sm mx-auto" style="width: 10px; height: 10px" title="Habilitado"></div>
                            @elseif(!$col->is_fees_compliant)
                                <div class="bg-danger rounded-circle shadow-sm mx-auto" style="width: 10px; height: 10px" title="Deuda Cuotas"></div>
                            @else
                                <div class="bg-warning rounded-circle shadow-sm mx-auto" style="width: 10px; height: 10px" title="Deuda Documental"></div>
                            @endif
                        </td>
                        <td class="py-1 searchable" data-field="name">
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $col->last_name }}, {{ $col->first_name }}</span>
                        </td>
                        <td class="py-1">
                            <span class="badge bg-primary bg-opacity-10 text-primary x-small border border-primary border-opacity-25" style="font-size: 0.65rem; width: 100px;">{{ $col->registration_number }}</span>
                        </td>
                        <td class="py-1 searchable" data-field="contact">
                            <span class="x-small text-muted fw-bold me-2">DNI {{ number_format($col->dni, 0, ',', '.') }}</span>
                            <span class="mx-1 text-muted opacity-25">|</span>
                            <span class="x-small text-muted me-2">{{ $col->email }}</span>
                            @if($col->phone)
                                <span class="mx-1 text-muted opacity-25">|</span>
                                <span class="x-small text-secondary"><i class="bi bi-phone me-1"></i>{{ $col->phone }}</span>
                            @endif
                        </td>
                        <td class="py-1 x-small fw-bold text-muted text-uppercase" style="font-size: 0.65rem;">
                            {{ $col->professional_situation ?? 'Activo' }}
                        </td>
                        <td class="py-1 text-end px-4">
                            <a href="{{ route('collegiates.show', $col) }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold x-small py-0 px-3">Ver Perfil</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center">
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
    .x-small { font-size: 0.75rem; }
    .card-prestige { border-radius: 25px; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .card-prestige:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    mark { background: #fff3cd !important; padding: 0.1em 0 !important; color: #856404; font-weight: 800; border-radius: 2px; }
    #collegiateTable tr { transition: background 0.2s ease; }
    #collegiateTable tr:hover { background-color: rgba(248, 250, 252, 0.8) !important; }
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
});
</script>
@endsection

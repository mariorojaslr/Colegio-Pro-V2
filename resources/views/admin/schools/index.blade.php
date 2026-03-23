@extends('layouts.admin')

@section('header', 'Gestión de Empresas')

@section('content')
<div class="container-fluid py-2 min-vh-100 bg-light-subtle">
    {{-- Cabecera Refinada --}}
    <div class="row align-items-center mb-3 px-2">
        <div class="col">
            <h6 class="text-muted xx-small fw-bold uppercase ls-2 mb-0">OWNER - CONTROL</h6>
            <h3 class="fw-black mb-0 ls-n1" style="font-family: 'Outfit', sans-serif; font-size: 1.5rem;">Empresas Registradas</h3>
        </div>
        <div class="col-lg-auto d-flex gap-2">
             <div class="dropdown">
                <button class="btn btn-white btn-sm border-0 shadow-sm rounded-pill px-3 xx-small fw-bold dropdown-toggle" data-bs-toggle="dropdown">
                    Mostrar: <span class="text-primary">{{ request('per_page', 10) }}</span>
                </button>
                <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2">
                    <li><a class="dropdown-item rounded-3 xx-small fw-bold" href="{{ url()->current() }}?per_page=10">10 registros</a></li>
                    <li><a class="dropdown-item rounded-3 xx-small fw-bold" href="{{ url()->current() }}?per_page=25">25 registros</a></li>
                    <li><a class="dropdown-item rounded-3 xx-small fw-bold" href="{{ url()->current() }}?per_page=50">50 registros</a></li>
                </ul>
            </div>
            <a href="{{ route('admin.schools.create') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-black xx-small shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> NUEVA EMPRESA
            </a>
        </div>
    </div>

    {{-- Tabla Ultra-Delgada Rolls-Royce --}}
    <div class="bg-white shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white border-bottom border-light uppercase" style="font-size: 9px; letter-spacing: 1.5px; color: #64748b;">
                    <tr>
                        <th class="ps-4 py-2">ID</th>
                        <th>Institución</th>
                        <th>Plan</th>
                        <th>Alumnos</th>
                        <th>Suscripción</th>
                        <th>Acceso</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-0">
                    @foreach ($schools as $school)
                    <tr style="height: 42px;"> {{-- ALTURA MÍNIMA --}}
                        <td class="ps-4">
                            <span class="fw-bold text-muted" style="font-size: 9px;">#0{{ $school->id }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white fw-black shadow-sm" 
                                     style="width: 26px; height: 26px; background: {{ $school->primary_color }}; font-size: 10px;">
                                    {{ substr($school->name, 0, 1) }}
                                </div>
                                <div style="max-width: 220px;">
                                    <div class="fw-bold text-dark xx-small uppercase ls-1 mb-0 text-truncate">{{ $school->name }}</div>
                                    <code class="text-muted" style="font-size: 7.5px;">{{ $school->slug }}.colegio-pro.cl</code>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="xx-small fw-black text-primary">{{ strtoupper($school->plan_category) }}</span>
                        </td>
                        <td>
                            <div class="fw-black text-dark" style="font-size: 9px;">{{ $school->users_count }} <span class="fw-normal text-muted">activos</span></div>
                        </td>
                        <td>
                            @php $sub = $school->activeSubscription; @endphp
                            @if($sub)
                                <div class="badge bg-success-subtle text-success rounded-pill px-2 py-1 xx-small fw-black border-0">AL DÍA</div>
                            @else
                                <div class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 xx-small fw-black border-0">SIN PLAN</div>
                            @endif
                        </td>
                        <td>
                             <span class="badge {{ $school->is_active ? 'bg-info' : 'bg-secondary' }} rounded-pill px-2 xx-small fw-bold">
                                {{ $school->is_active ? 'ON' : 'OFF' }}
                             </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <a href="{{ route('admin.schools.edit', $school->id) }}" class="text-muted" title="Editar">
                                    <i class="bi bi-pencil-square ls-1" style="font-size: 11px;"></i>
                                </a>
                                <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 xx-small fw-black shadow-none border-1" style="height: 24px; line-height: 1.1;">
                                    VER COMO ADMIN
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación Soberana --}}
        <div class="bg-light bg-opacity-50 px-3 py-2 border-top border-light d-flex justify-content-between align-items-center">
             <div class="xx-small text-muted fw-bold ls-1 uppercase">Empresas: {{ $schools->firstItem() }} - {{ $schools->lastItem() }} de {{ $schools->total() }}</div>
             <div class="pagination-wrapper">
                 {{ $schools->appends(request()->input())->links('pagination::bootstrap-5') }}
             </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .ls-n1 { letter-spacing: -1px; }
    .ls-1 { letter-spacing: 0.5px; }
    .ls-2 { letter-spacing: 2px; }
    .xx-small { font-size: 8.5px; }
    
    .pagination { margin: 0; }
    .page-link { padding: 4px 10px; font-size: 9px; font-weight: 900; border: 0; background: transparent; color: #0F172A; margin: 0 1px; border-radius: 50px !important; }
    .page-item.active .page-link { background: #0F172A; color: white; }
    .table-hover tbody tr:hover { background-color: rgba(15, 23, 42, 0.02); }
    .table td { border-bottom: 1px solid #f1f5f9; }
</style>
@endsection

@extends('layouts.admin')

@section('header', 'Gestión de Empresas')

@section('content')
<div class="container-fluid py-2 min-vh-100 bg-light-subtle">
    {{-- Cabecera Refinada --}}
    <div class="row align-items-center mb-4 px-2">
        <div class="col">
            <h6 class="text-muted small fw-bold text-uppercase ls-1 mb-1">OWNER - CONTROL</h6>
            <h3 class="fw-bold mb-0 text-dark">Empresas Registradas</h3>
        </div>
        <div class="col-lg-auto d-flex gap-2">
            <a href="{{ route('admin.schools.create') }}" class="btn btn-dark rounded-pill px-4 shadow-sm fw-semibold">
                <i class="bi bi-plus-lg me-1"></i> Nueva Empresa
            </a>
        </div>
    </div>

    {{-- Formulario para configurar el prefijo/base domain --}}
    <div class="bg-white shadow-sm border-0 rounded-4 p-4 mb-4">
        <form method="POST" action="{{ route('admin.global_settings.update_domain') }}" class="d-flex align-items-end gap-3">
            @csrf
            <div class="flex-grow-1">
                <label class="form-label text-muted small fw-bold mb-1">Dominio Base del Sistema (Ej: misistema.com)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-light text-muted"><i class="bi bi-globe"></i></span>
                    <input type="text" name="base_domain" class="form-control border-light shadow-none" value="{{ \App\Models\GlobalSetting::getVal('base_domain', 'colegio-pro.cl') }}" placeholder="ejemplo.com">
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">Guardar Dominio</button>
        </form>
    </div>

    {{-- Tabla Optimizada y Legible --}}
    <div class="bg-white shadow-sm border-0 overflow-hidden rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Institución</th>
                        <th class="py-3">Plan</th>
                        <th class="py-3">Colegiados</th>
                        <th class="py-3">Suscripción</th>
                        <th class="py-3">Acceso</th>
                        <th class="text-end pe-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-0">
                    @foreach ($schools as $school)
                    <tr>
                        <td class="ps-4 text-muted fw-semibold">
                            #0{{ $school->id }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($school->logo_url)
                                    <img src="{{ $school->logo_url }}" class="rounded-circle me-4 shadow-sm object-fit-cover bg-white" 
                                         style="width: 80px; height: 80px; border: 2px solid {{ $school->primary_color }};">
                                @else
                                    <div class="rounded-circle me-4 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm fs-3" 
                                         style="width: 80px; height: 80px; background: {{ $school->primary_color }};">
                                        {{ substr($school->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark mb-1">{{ $school->name }}</div>
                                    <code class="text-muted bg-light px-2 py-1 rounded">{{ $school->slug }}.{{ \App\Models\GlobalSetting::getVal('base_domain', 'colegio-pro.cl') }}</code>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold">{{ strtoupper($school->plan_category) }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $school->users_count }} <span class="fw-normal text-muted small">activos</span></div>
                        </td>
                        <td>
                            @php $sub = $school->activeSubscription; @endphp
                            @if($sub)
                                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-semibold shadow-sm"><i class="bi bi-check-circle me-1"></i> AL DÍA</div>
                            @else
                                <div class="badge bg-danger text-white rounded-pill px-3 py-2 fw-semibold shadow-sm"><i class="bi bi-exclamation-circle me-1"></i> SIN PLAN</div>
                            @endif
                        </td>
                        <td>
                             <div class="form-check form-switch fs-5 mb-0">
                                <input class="form-check-input" type="checkbox" disabled {{ $school->is_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" title="Editar">
                                    <i class="bi bi-pencil-square text-muted"></i>
                                </a>
                                <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">
                                    Ver como Admin
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación Soberana --}}
        <div class="bg-light px-4 py-3 border-top border-light d-flex justify-content-between align-items-center">
             <div class="small text-muted fw-semibold">Mostrando {{ $schools->firstItem() }} a {{ $schools->lastItem() }} de {{ $schools->total() }} empresas</div>
             <div class="pagination-wrapper m-0">
                 {{ $schools->appends(request()->input())->links('pagination::bootstrap-5') }}
             </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .pagination { margin: 0; }
    .table td { border-bottom: 1px solid #f8fafc; padding-top: 1rem; padding-bottom: 1rem; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
</style>
@endsection

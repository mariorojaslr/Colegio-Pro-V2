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
                        <th class="py-3 text-center">Plan</th>
                        <th class="py-3 px-4" style="min-width: 200px;">Consumo (Uso / Límite)</th>
                        <th class="py-3 text-center">Suscripción</th>
                        <th class="py-3 text-center">Acceso</th>
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
                                @php
                                    $displayLogo = $school->logo_icon ?? $school->logo;
                                @endphp
                                @if($displayLogo)
                                    <img src="{{ Str::startsWith($displayLogo, 'http') ? $displayLogo : asset($displayLogo) }}" class="rounded-circle me-4 shadow-sm object-fit-cover bg-white" 
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
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold">{{ strtoupper($school->plan_category) }}</span>
                        </td>
                        <td class="px-4">
                            @php 
                                $plan = $school->activeSubscription?->plan;
                                $maxStorage = $plan ? $plan->max_storage : 0; // en MB
                                $usedStorage = round($school->storage_used / 1024 / 1024, 2);
                                $storagePercent = $maxStorage > 0 ? round(($usedStorage / $maxStorage) * 100) : 0;
                                $maxUsers = $plan ? $plan->max_users : 0;
                                $usedUsers = $school->collegiates_count;
                                $usersPercent = $maxUsers > 0 ? round(($usedUsers / $maxUsers) * 100) : 0;
                            @endphp
                            
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 0.75rem;">
                                    <span class="text-muted fw-bold"><i class="bi bi-people-fill me-1"></i> Colegiados</span>
                                    <span class="fw-bold {{ $usedUsers >= $maxUsers && $maxUsers > 0 ? 'text-danger' : 'text-dark' }}">
                                        {{ $usedUsers }} <span class="text-muted fw-normal">/ {{ $maxUsers > 0 ? $maxUsers : '∞' }}</span>
                                    </span>
                                </div>
                                <div class="progress bg-light" style="height: 5px;">
                                    <div class="progress-bar {{ $usersPercent > 90 ? 'bg-danger' : ($usersPercent > 75 ? 'bg-warning' : 'bg-primary') }}" role="progressbar" style="width: {{ min($usersPercent, 100) }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-content-between mb-1" style="font-size: 0.75rem;">
                                    <span class="text-muted fw-bold"><i class="bi bi-hdd-fill me-1"></i> Espacio (MB)</span>
                                    <span class="fw-bold {{ $usedStorage >= $maxStorage && $maxStorage > 0 ? 'text-danger' : 'text-dark' }}">
                                        {{ $usedStorage }} <span class="text-muted fw-normal">/ {{ $maxStorage > 0 ? $maxStorage : '∞' }}</span>
                                    </span>
                                </div>
                                <div class="progress bg-light" style="height: 5px;">
                                    <div class="progress-bar {{ $storagePercent > 90 ? 'bg-danger' : ($storagePercent > 75 ? 'bg-warning' : 'bg-primary') }}" role="progressbar" style="width: {{ min($storagePercent, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @php $sub = $school->activeSubscription; @endphp
                            @if($sub)
                                <div class="badge bg-success text-white rounded-pill px-3 py-2 fw-semibold shadow-sm"><i class="bi bi-check-circle me-1"></i> AL DÍA</div>
                            @else
                                <div class="badge bg-danger text-white rounded-pill px-3 py-2 fw-semibold shadow-sm"><i class="bi bi-exclamation-circle me-1"></i> SIN PLAN</div>
                            @endif
                        </td>
                        <td class="text-center">
                             <div class="form-check form-switch fs-5 mb-0 d-inline-block">
                                <input class="form-check-input m-0" type="checkbox" disabled {{ $school->is_active ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex gap-2 justify-content-end align-items-center">
                                <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" title="Editar">
                                    <i class="bi bi-pencil-square text-muted"></i>
                                </a>
                                <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm" style="font-size: 0.8rem;">
                                    Ver Admin
                                </a>
                                <form action="{{ route('admin.schools.generate_demo', $school->id) }}" method="POST" onsubmit="return confirm('¿Crear o restablecer usuario demo (demo_{{ str_replace(\"-\", \"\", $school->slug) }}@gentepiola.net) para esta empresa?');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;" title="Generar Usuario Demo">
                                        <i class="bi bi-person-badge-fill text-dark"></i>
                                    </button>
                                </form>
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

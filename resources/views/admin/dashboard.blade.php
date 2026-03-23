@extends('layouts.admin')

@section('header', 'Panel Administrativo Global')

@section('content')
{{-- Sección de Tarjetas de Métricas Globales --}}
<div class="row g-4 mb-4">
    {{-- Métrica: Recaudación Global (Financiero) --}}
    <div class="col-md-4">
        <div class="card stat-card p-4 bg-primary text-white border-0 shadow-sm rounded-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="rounded-circle bg-white bg-opacity-20 p-2 me-3">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
                <h6 class="m-0 small text-uppercase fw-bold ls-1 opacity-75">Recaudación Total</h6>
            </div>
            <h2 class="fw-bold m-0">${{ number_format($stats['total_revenue'], 0, ',', '.') }}</h2>
            <div class="mt-2 small opacity-75">Ingresos acumulados del sistema</div>
        </div>
    </div>
    {{-- Métrica: Total de Escuelas (Tenants) --}}
    <div class="col-md-4">
        <div class="card stat-card p-4 bg-white border-0 shadow-sm rounded-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                    <i class="bi bi-building text-warning fs-4"></i>
                </div>
                <h6 class="m-0 small text-uppercase fw-bold ls-1 text-muted">Colegios Activos</h6>
            </div>
            <h2 class="fw-bold m-0 text-dark">{{ $stats['total_schools'] }}</h2>
            <div class="mt-2 small text-muted">Instituciones en plataforma</div>
        </div>
    </div>
    {{-- Métrica: Total de Alumnos en el Sistema --}}
    <div class="col-md-4">
        <div class="card stat-card p-4 bg-white border-0 shadow-sm rounded-4 h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                    <i class="bi bi-mortarboard text-info fs-4"></i>
                </div>
                <h6 class="m-0 small text-uppercase fw-bold ls-1 text-muted">Alumnos Totales</h6>
            </div>
            <h2 class="fw-bold m-0 text-dark">{{ $stats['total_users'] }}</h2>
            <div class="mt-2 small text-muted">Estudiantes activos globales</div>
        </div>
    </div>
</div>

{{-- Segunda Fila: Consumo Cloud --}}
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stat-card p-3 bg-white border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-1">
                <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                    <i class="bi bi-hdd-stack text-info"></i>
                </div>
                <h6 class="m-0 text-muted small">Almacenamiento</h6>
            </div>
            <h4 class="fw-bold m-0">{{ number_format($stats['storage_used'] / 1024, 1) }} <small class="fs-6 fw-normal opacity-50">GB</small></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 bg-white border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-1">
                <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2">
                    <i class="bi bi-files text-success"></i>
                </div>
                <h6 class="m-0 text-muted small">Archivos</h6>
            </div>
            <h4 class="fw-bold m-0">{{ number_format($stats['total_files'], 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 bg-white border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-1">
                <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-2">
                    <i class="bi bi-image text-danger"></i>
                </div>
                <h6 class="m-0 text-muted small">Imágenes</h6>
            </div>
            <h4 class="fw-bold m-0">{{ number_format($stats['total_images'], 0, ',', '.') }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3 bg-white border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center mb-1">
                <div class="rounded-circle bg-dark bg-opacity-10 p-2 me-2">
                    <i class="bi bi-play-btn text-dark"></i>
                </div>
                <h6 class="m-0 text-muted small">Streaming</h6>
            </div>
            <h4 class="fw-bold m-0">{{ number_format($stats['streaming_usage'], 0, ',', '.') }} <small class="fs-6 fw-normal opacity-50">min</small></h4>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        {{-- Acción Global: Envío de alertas a todos los tenants --}}
        <div class="card stat-card p-4 bg-white border-0 shadow-sm rounded-4 text-center d-flex align-items-center justify-content-center cursor-pointer" 
             style="border: 2px dashed #E2E8F0 !important;" data-bs-toggle="modal" data-bs-target="#notifModal">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="var(--primary-color)" class="bi bi-megaphone" viewBox="0 0 16 16"><path d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-11zm-1 .724c-2.067.95-4.539 1.481-7 1.656v6.237a25.222 25.222 0 0 1 7 1.656V3.224zm-8 2.25c-1.848.157-3.678.346-5.41.528A1.5 1.5 0 0 0 0 7.5v1a1.5 1.5 0 0 0 1.59 1.5c1.732.182 3.562.371 5.41.528V5.474zM1.5 9.5a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5c1.705-.178 3.504-.363 5.31-.518v2.036c-1.806-.155-3.605-.34-5.31-.518z"/></svg>
            </div>
            <h6 class="fw-bold m-0" style="color: var(--primary-color)">Enviar Comunicación Global</h6>
            <small class="text-muted">Aviso a todos los Colegios del sistema</small>
        </div>
    </div>
</div>

<!-- Modal de Notificaciones -->
<div class="modal fade" id="notifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold m-0">Enviar Notificación Global</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Destinatarios</label>
                        <select class="form-select rounded-3 border-light-subtle shadow-none" name="school_id">
                            <option value="">Todos los Colegios (SaaS)</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Título del Aviso</label>
                        <input type="text" name="title" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: Mantenimiento de Servidores" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Tipo de Mensaje</label>
                        <select class="form-select rounded-3 border-light-subtle shadow-none" name="type">
                            <option value="info">Información General</option>
                            <option value="alert">Alerta Crítica</option>
                            <option value="success">Novedades Positivas</option>
                            <option value="billing">Facturación y Cobros</option>
                        </select>
                    </div>
                    <div>
                        <label class="small fw-bold text-muted mb-1">Mensaje Detallado</label>
                        <textarea name="message" class="form-control rounded-3 border-light-subtle shadow-none" rows="3" placeholder="Redacte el aviso aquí..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 text-muted fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">Enviar Ahora</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schools Table -->
<div class="table-premium">
    <div class="p-4 d-flex justify-content-between align-items-center bg-white">
        <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Gestión de Colegios (Tenants)</h5>
        <a href="{{ route('admin.schools.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Nueva Empresa
        </a>
    </div>
    <div class="table-responsive px-4 pb-4">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                <tr>
                    <th>Colegio</th>
                    <th>Plan</th>
                    <th>Usuarios</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schools as $school)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 40px; height: 40px; background: {{ $school->primary_color }}">
                                {{ substr($school->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $school->name }}</div>
                                <div class="small text-muted">{{ $school->slug }}.colegio-pro.cl</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($school->activeSubscription && $school->activeSubscription->plan)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-2 xx-small fw-bold uppercase">
                                <i class="bi bi-star-fill me-1"></i> {{ $school->activeSubscription->plan->name }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border rounded-pill px-3 py-2 xx-small fw-bold uppercase">SIN PLAN</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $school->users_count }}</div>
                        <div class="small text-muted">asociados activos</div>
                    </td>
                    <td>
                        @php $sub = $school->activeSubscription; @endphp
                        @if($sub)
                            <div class="d-flex align-items-center text-success fw-bold small">
                                <span class="rounded-circle bg-success me-2" style="width: 8px; height: 8px"></span>
                                Suscrito
                            </div>
                        @else
                            <div class="d-flex align-items-center text-muted fw-bold small">
                                <span class="rounded-circle bg-secondary me-2" style="width: 8px; height: 8px"></span>
                                Sin Suscripción
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-light btn-sm rounded-circle p-2 shadow-sm" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l.002.002 2.083 2.083a.5.5 0 0 1 0 .708l-9.833 9.833a.5.5 0 0 1-.217.135l-3.92 1.307a.5.5 0 0 1-.632-.632l1.307-3.92a.5.5 0 0 1 .135-.217l9.833-9.833zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>
                            </a>
                            <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-none fw-bold" style="font-size: 11px">
                                <i class="bi bi-eye me-1"></i> Ver como Administrador
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

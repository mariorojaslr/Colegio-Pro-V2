@extends('layouts.admin')

@section('header', 'Gestión de Empresas')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Instituciones Registradas</h1>
            <p class="text-muted">Administre los colegios que forman parte del ecosistema Colegio-Pro.</p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('admin.schools.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg me-1"></i> Nueva Empresa
            </a>
        </div>
    </div>

    <div class="table-premium">
        <div class="table-responsive p-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                    <tr>
                        <th>Colegio</th>
                        <th>Plan</th>
                        <th>Alumnos</th>
                        <th>Estado de Suscripción</th>
                        <th>Acceso</th>
                        <th class="text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schools as $school)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                     style="width: 45px; height: 45px; background: {{ $school->primary_color }}">
                                    {{ substr($school->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $school->name }}</div>
                                    <div class="small text-muted">{{ $school->slug }}.colegio-pro.cl</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-plan plan-{{ $school->plan_category }}">{{ strtoupper($school->plan_category) }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $school->users_count }}</div>
                            <div class="small text-muted">alumnos activos</div>
                        </td>
                        <td>
                            @php $sub = $school->activeSubscription; @endphp
                            @if($sub)
                                <div class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold border-0">
                                    {{ $sub->status == 'active' ? 'AL DÍA' : 'PENDIENTE' }} hasta {{ $sub->expires_at->format('d/m/y') }}
                                </div>
                            @else
                                <div class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 fw-bold border-0">SIN PLAN ACTIVO</div>
                            @endif
                        </td>
                        <td>
                             <span class="badge {{ $school->is_active ? 'bg-info' : 'bg-secondary' }} rounded-pill px-2">
                                {{ $school->is_active ? 'Activo' : 'Suspendido' }}
                             </span>
                        </td>
                        <td class="text-end px-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-light btn-sm rounded-circle p-2 shadow-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-none fw-bold" style="font-size: 11px">
                                    <i class="bi bi-eye me-1"></i> Ver como Admin
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

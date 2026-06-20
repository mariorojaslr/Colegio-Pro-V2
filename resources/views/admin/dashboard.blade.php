@extends('layouts.admin')

@section('header', 'Panel Administrativo Global')

@section('styles')
<style>
    /* Forzar fondo oscuro al estilo multipost para el panel Owner */
    body, .main-content {
        background-color: #0b0f19 !important; /* Muy oscuro, casi negro con toque azulado */
        color: #e2e8f0 !important;
    }
    
    .card-multipost {
        background-color: #121826 !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        letter-spacing: -1px;
    }

    .metric-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #94a3b8;
    }

    /* Colores acento neón */
    .text-neon-blue { color: #3b82f6; }
    .text-neon-green { color: #10b981; }
    .text-neon-purple { color: #8b5cf6; }

    /* Línea de tiempo (Activity Log) */
    .timeline-item {
        position: relative;
        padding-left: 1.5rem;
        border-left: 2px solid rgba(255,255,255,0.05);
        margin-bottom: 1.5rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -5px;
        top: 5px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }
    .timeline-time {
        font-size: 0.75rem;
        color: #10b981;
        font-weight: bold;
    }

    /* Botones Panel Control Derecho */
    .control-btn {
        display: flex;
        align-items: center;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 1rem;
        color: #e2e8f0;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 0.75rem;
    }
    .control-btn:hover {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    .control-btn i {
        font-size: 1.25rem;
        margin-right: 1rem;
        color: #3b82f6;
    }
    .control-btn .title {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.1rem;
    }
    .control-btn .desc {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* Tabla Estilo Multipost */
    .table-dark-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-dark-custom th {
        color: #94a3b8;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-weight: 700;
    }
    .table-dark-custom td {
        background-color: rgba(255, 255, 255, 0.02);
        padding: 1rem;
        vertical-align: middle;
        font-size: 0.85rem;
        border-top: 1px solid rgba(255,255,255,0.02);
        border-bottom: 1px solid rgba(255,255,255,0.02);
    }
    .table-dark-custom tr td:first-child {
        border-left: 1px solid rgba(255,255,255,0.02);
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .table-dark-custom tr td:last-child {
        border-right: 1px solid rgba(255,255,255,0.02);
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .badge-estado {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    .badge-activa { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-vencida { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-premium { background: #3b82f6; color: white; }
    .badge-basico { background: #0ea5e9; color: white; }

</style>
@endsection

@section('content')

{{-- Fila 1: Métricas Principales (Adaptadas de Radar de Adquisición / Salud) --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-multipost p-4 h-100">
            <div class="metric-title mb-2">Total Colegios Activos</div>
            <div class="d-flex align-items-baseline">
                <div class="stat-value text-white">{{ $stats['total_schools'] }}</div>
                <div class="ms-2 text-neon-green small fw-bold"><i class="bi bi-arrow-up-right"></i> +2</div>
            </div>
            <div class="text-muted" style="font-size: 0.75rem;">EMPRESAS HOY</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-multipost p-4 h-100">
            <div class="metric-title mb-2">Comunidad Global</div>
            <div class="d-flex align-items-baseline">
                <div class="stat-value text-white">{{ number_format($stats['total_users'], 0, ',', '.') }}</div>
            </div>
            <div class="text-muted" style="font-size: 0.75rem;">USUARIOS ACTIVOS</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-multipost p-4 h-100">
            <div class="metric-title mb-2">Almacenamiento Cloud</div>
            <div class="d-flex align-items-baseline">
                <div class="stat-value text-white">{{ number_format($stats['storage_used'] / 1024, 1) }}</div>
                <div class="ms-1 text-muted fw-bold">GB</div>
            </div>
            <div class="text-muted" style="font-size: 0.75rem;">{{ number_format($stats['total_files'], 0, ',', '.') }} ARCHIVOS</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-multipost p-4 h-100">
            <div class="metric-title mb-2">Conversión MRR (Plataforma)</div>
            <div class="d-flex align-items-baseline">
                <div class="stat-value text-white">${{ number_format($stats['mrr'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="text-muted" style="font-size: 0.75rem;">EFECTIVIDAD DE COBROS</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    {{-- Columna Izquierda: Monitor de Operaciones (Logs) --}}
    <div class="col-lg-8">
        <div class="card card-multipost p-0 h-100">
            <div class="px-4 py-3 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold" style="font-family: 'Outfit', sans-serif; font-size: 0.9rem; color: #94a3b8;">
                        <i class="bi bi-clock-history me-2 text-neon-green"></i> MONITOR DE OPERACIONES GLOBAL (PLATAFORMA)
                    </h6>
                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3" style="font-size: 0.7rem;">TIEMPO REAL</span>
                </div>
            </div>
            <div class="p-4" style="max-height: 400px; overflow-y: auto;">
                @forelse($recentLogs as $log)
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="timeline-time mb-1">{{ $log->created_at->format('H:i') }} <span class="text-muted fw-normal ms-1">{{ $log->created_at->format('d/m') }}</span></div>
                                <div class="text-white fw-bold mb-1" style="font-size: 0.85rem;">de {{ $log->user->name ?? 'Sistema' }}</div>
                                <div class="text-muted" style="font-size: 0.8rem;"><i class="bi bi-caret-right-fill text-warning" style="font-size: 0.6rem;"></i> {{ $log->description }}</div>
                            </div>
                            <span class="badge bg-dark border text-uppercase" style="font-size: 0.6rem; border-color: rgba(255,255,255,0.1) !important;">
                                {{ $log->user->role ?? 'SYS' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">No hay operaciones recientes registradas en el sistema.</div>
                @endforelse
            </div>
            <div class="px-4 py-3 border-top text-end" style="border-color: rgba(255,255,255,0.05) !important;">
                <a href="{{ route('admin.activity_logs.index') }}" class="text-decoration-none text-muted" style="font-size: 0.8rem; font-weight: bold;">VER LOGS COMPLETOS &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Columna Derecha: Panel de Control (Multipost Style) --}}
    <div class="col-lg-4">
        <div class="card card-multipost p-0 h-100">
            <div class="px-4 py-3 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
                <h6 class="m-0 fw-bold" style="font-family: 'Outfit', sans-serif; font-size: 0.9rem; color: #94a3b8;">
                    <i class="bi bi-list-nested me-2"></i> PANEL DE CONTROL
                </h6>
            </div>
            <div class="p-3">
                <a href="{{ route('admin.schools.index') }}" class="control-btn">
                    <i class="bi bi-building"></i>
                    <div>
                        <span class="title">Gestión de Empresas</span>
                        <span class="desc">Suscripciones y despliegues</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.billing.global') }}" class="control-btn">
                    <i class="bi bi-wallet2 text-neon-green"></i>
                    <div>
                        <span class="title">Centro Financiero</span>
                        <span class="desc">Cobranzas y MRR</span>
                    </div>
                </a>

                <a href="{{ route('admin.tickets.index') }}" class="control-btn">
                    <i class="bi bi-headset text-danger"></i>
                    <div>
                        <span class="title">Central de Soporte</span>
                        <span class="desc">Tickets y resolución</span>
                    </div>
                </a>

                <a href="{{ route('admin.plans.index') }}" class="control-btn">
                    <i class="bi bi-gear-fill text-info"></i>
                    <div>
                        <span class="title">Planes Plataforma</span>
                        <span class="desc">Precios y servicios</span>
                    </div>
                </a>

                <a href="#" class="control-btn" data-bs-toggle="modal" data-bs-target="#notifModal">
                    <i class="bi bi-broadcast text-neon-purple"></i>
                    <div>
                        <span class="title">Comunicados</span>
                        <span class="desc">Logs de actualización y avisos</span>
                    </div>
                </a>

                <a href="#" class="control-btn">
                    <i class="bi bi-bell-fill text-warning"></i>
                    <div>
                        <span class="title">Notificaciones</span>
                        <span class="desc">Historial de avisos Plataforma</span>
                    </div>
                </a>

                <a href="#" class="control-btn" style="border-color: rgba(245, 158, 11, 0.3);">
                    <i class="bi bi-sliders text-warning"></i>
                    <div>
                        <span class="title">Ajustes Globales</span>
                        <span class="desc">Configuración maestra</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Tabla de Empresas Estilo Multipost --}}
<div class="card card-multipost p-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0 text-white" style="font-family: 'Outfit', sans-serif;">Empresas Activas en el Sistema</h5>
        <div>
            <a href="{{ route('admin.schools.create') }}" class="btn btn-primary btn-sm fw-bold px-3">+ Nueva empresa</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Plan</th>
                    <th class="text-center">Colegiados</th>
                    <th class="text-center">Usuarios</th>
                    <th class="text-center">Multimedia</th>
                    <th>Vencimiento</th>
                    <th class="text-center">Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr>
                    <td>
                        <div class="text-white fw-bold mb-1">{{ $school->name }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $school->slug }}.colegio-pro.cl</div>
                    </td>
                    <td>
                        @if($school->activeSubscription && $school->activeSubscription->plan)
                            <span class="badge badge-premium" style="font-size: 0.65rem;">{{ $school->activeSubscription->plan->name }}</span>
                        @else
                            <span class="badge badge-basico" style="font-size: 0.65rem;">Básico</span>
                        @endif
                    </td>
                    <td class="text-center text-white fw-bold">{{ $school->collegiates_count }}</td>
                    <td class="text-center text-white fw-bold">{{ $school->users_count }}</td>
                    <td class="text-center">
                        <div class="d-inline-flex align-items-center bg-dark rounded px-2 py-1 border" style="border-color: rgba(255,255,255,0.1) !important; font-size: 0.75rem;">
                            <i class="bi bi-image me-1 text-muted"></i> <span class="text-white">{{ $school->total_files ?? 0 }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="text-white" style="font-size: 0.8rem;">
                            {{ $school->activeSubscription && $school->activeSubscription->ends_at ? $school->activeSubscription->ends_at->format('d/m/Y') : 'Ilimitado' }}
                        </div>
                    </td>
                    <td class="text-center">
                        @if($school->activeSubscription)
                            <span class="badge-estado badge-activa">Activa</span>
                            <div class="mt-1 text-muted" style="font-size: 0.65rem;">Pactado: ${{ number_format($school->activeSubscription->plan->price ?? 0, 0, ',', '.') }}</div>
                        @else
                            <span class="badge-estado badge-vencida">Vencida</span>
                            <div class="mt-1 text-muted" style="font-size: 0.65rem;">Pactado: $0</div>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="#" class="btn btn-sm btn-outline-light px-2" style="font-size: 0.7rem;">Usuarios</a>
                            <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-sm btn-primary px-2 fw-bold" style="font-size: 0.7rem;"><i class="bi bi-box-arrow-in-right me-1"></i> ENTRAR</a>
                            <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-sm bg-dark text-white border px-2" style="border-color: rgba(255,255,255,0.1) !important; font-size: 0.7rem;">Editar</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Notificaciones -->
<div class="modal fade" id="notifModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content card-multipost border-0 shadow-lg" style="background-color: #1a2235 !important;">
            <div class="modal-header border-bottom pb-3" style="border-color: rgba(255,255,255,0.05) !important;">
                <h5 class="fw-bold m-0 text-white">Enviar Notificación Global</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Destinatarios</label>
                        <select class="form-select bg-dark text-white border-0 shadow-none" name="school_id">
                            <option value="">Todos los Colegios (Plataforma)</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Título del Aviso</label>
                        <input type="text" name="title" class="form-control bg-dark text-white border-0 shadow-none" placeholder="Ej: Mantenimiento de Servidores" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">Tipo de Mensaje</label>
                        <select class="form-select bg-dark text-white border-0 shadow-none" name="type">
                            <option value="info">Información General</option>
                            <option value="alert">Alerta Crítica</option>
                            <option value="success">Novedades Positivas</option>
                            <option value="billing">Facturación y Cobros</option>
                        </select>
                    </div>
                    <div>
                        <label class="small fw-bold text-muted mb-1">Mensaje Detallado</label>
                        <textarea name="message" class="form-control bg-dark text-white border-0 shadow-none" rows="3" placeholder="Redacte el aviso aquí..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Enviar Ahora</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@extends('layouts.admin')

@section('header', 'Consola de Auditoría')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-bottom border-light py-4 px-5 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="m-0 fw-bold">Historial de Actividad Global</h5>
                <p class="text-muted small mb-0">Rastreo de acciones, IPs y cambios en el sistema.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-filter me-1"></i> Filtrar Acciones
                </button>
                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                    <i class="bi bi-download me-1"></i> Exportar CSV
                </button>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-muted text-uppercase fw-bold ls-1">
                        <th class="ps-5 py-3">Fecha y Hora</th>
                        <th class="py-3">Usuario</th>
                        <th class="py-3">Acción</th>
                        <th class="py-3">Institución / Contexto</th>
                        <th class="py-3">Dirección IP</th>
                        <th class="pe-5 py-3 text-end">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="ps-5 py-4 small fw-bold">
                            {{ $log->created_at->format('d/m/y H:i:s') }}
                            <div class="text-muted fw-normal" style="font-size: 10px">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-light me-2 d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 32px; height: 32px; font-size: 12px">
                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="fw-bold">{{ $log->user->name ?? 'Sistema' }}</span>
                            </div>
                        </td>
                        <td class="py-4">
                            @php
                                $badgeColor = match($log->action) {
                                    'login' => 'success',
                                    'impersonate' => 'danger',
                                    'create_school' => 'primary',
                                    'update' => 'info',
                                    'delete' => 'dark',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} rounded-pill px-3 py-1 fw-bold border-0 text-uppercase" style="font-size: 10px">
                                {{ strtoupper($log->action) }}
                            </span>
                        </td>
                        <td class="py-4">
                            @if($log->school)
                                <span class="text-primary fw-bold">{{ $log->school->name }}</span>
                            @else
                                <span class="text-muted fw-bold italic">Panel Global</span>
                            @endif
                        </td>
                        <td class="py-4">
                            <code class="text-muted small">{{ $log->ip_address ?? '::1' }}</code>
                        </td>
                        <td class="pe-5 py-4 text-end">
                            <span class="text-dark fw-medium">{{ $log->description }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-5">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

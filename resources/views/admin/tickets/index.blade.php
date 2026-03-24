@extends('layouts.admin')

@section('header', 'Soporte y Tickets')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-5">
        <div class="col-lg-3">
            <div class="card card-premium border-crystalline p-4 text-center h-100">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto mb-3 shadow-sm" style="width: 60px">
                    <i class="bi bi-envelope-open text-primary fs-4"></i>
                </div>
                <h6 class="text-secondary xx-small uppercase fw-black ls-2 mb-1">Tickets Abiertos</h6>
                <h2 class="fw-black mb-0 text-finance-clean">{{ $tickets->where('status', 'open')->count() }}</h2>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 mx-auto mb-3" style="width: 60px">
                    <i class="bi bi-clock-history text-warning fs-4"></i>
                </div>
                <h6 class="text-muted small uppercase fw-bold ls-1 mb-1">Pendientes</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $tickets->where('status', 'pending')->count() }}</h2>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white h-100">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-3" style="width: 60px">
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <h6 class="text-muted small uppercase fw-bold ls-1 mb-1">Resueltos</h6>
                <h2 class="fw-bold mb-0 text-dark">{{ $tickets->where('status', 'resolved')->count() }}</h2>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-primary text-white h-100 d-flex flex-column justify-content-center">
                <p class="small mb-0 opacity-75">SLA de Respuesta</p>
                <h4 class="fw-bold mb-0">Menos de 2h</h4>
            </div>
        </div>
    </div>

    <div class="card card-premium border-crystalline overflow-hidden">
        <div class="card-header bg-transparent border-bottom py-4 px-5 d-flex justify-content-between align-items-center" style="border-bottom: 2px solid rgba(255,255,255,0.4) !important;">
            <h5 class="m-0 fw-black ls-n1">Todas las Solicitudes</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light btn-sm rounded-pill px-3">Filtrar por Colegio</button>
                <button class="btn btn-light btn-sm rounded-pill px-3">Exportar Logs</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-0">
                    <tr class="small text-muted text-uppercase fw-bold ls-1">
                        <th class="ps-5 py-3">Referencia / Asunto</th>
                        <th class="py-3">Institución</th>
                        <th class="py-3">Categoría</th>
                        <th class="py-3">Prioridad</th>
                        <th class="py-3">Última Actividad</th>
                        <th class="pe-5 py-3 text-end">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($tickets as $ticket)
                    <tr>
                        <td class="ps-5 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-decoration-none d-block">
                                <span class="fw-bold text-dark d-block">#TKT-{{ $ticket->id }} - {{ $ticket->subject }}</span>
                                <span class="text-muted small">Iniciado por {{ $ticket->user->name }}</span>
                            </a>
                        </td>
                        <td class="py-4 fw-bold text-primary">{{ $ticket->school->name }}</td>
                        <td class="py-4">
                            @if($ticket->category == 'technical')
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1 fw-bold">Técnico</span>
                            @elseif($ticket->category == 'billing')
                                <span class="badge bg-dark-subtle text-dark rounded-pill px-3 py-1 fw-bold">Facturación</span>
                            @else
                                <span class="badge bg-light text-muted rounded-pill px-3 py-1 fw-bold">Otros</span>
                            @endif
                        </td>
                        <td class="py-4">
                            @if($ticket->priority == 'high')
                                <span class="text-danger fw-bold"><i class="bi bi-fire me-1"></i> ALTA</span>
                            @elseif($ticket->priority == 'medium')
                                <span class="text-warning fw-bold"><i class="bi bi-exclamation-circle me-1"></i> NORMAL</span>
                            @else
                                <span class="text-muted">BAJA</span>
                            @endif
                        </td>
                        <td class="py-4 small">{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td class="pe-5 py-4 text-end">
                            @if($ticket->status == 'open')
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold border-0 shadow-sm px-4">ABIERTO</span>
                            @elseif($ticket->status == 'pending')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold border-0 shadow-sm px-4">PENDIENTE</span>
                            @elseif($ticket->status == 'resolved')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-bold border-0 px-4">RESUELTO</span>
                            @else
                                <span class="badge bg-light text-muted rounded-pill px-3 py-2 fw-bold border-0 px-4">CERRADO</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

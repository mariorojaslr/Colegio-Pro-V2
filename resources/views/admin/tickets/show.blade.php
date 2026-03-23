@extends('layouts.admin')

@section('header', 'Ticket Soporte: #TKT-' . $ticket->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h5 class="fw-bold m-0" style="color: var(--primary-color)">Hilo de Conversación</h5>
                    <div class="small text-muted">Iniciado el {{ $ticket->created_at->format('d M, Y H:i') }}</div>
                </div>

                {{-- Hilo de mensajes --}}
                <div class="d-grid gap-4 mb-5">
                    @foreach($ticket->messages as $msg)
                    <div class="d-flex gap-3 {{ $msg->user->isOwner() ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 45px; height: 45px; background: {{ $msg->user->isOwner() ? 'var(--primary-color)' : '#94A3B8' }}">
                                {{ substr($msg->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 {{ $msg->user->isOwner() ? 'text-end' : '' }}">
                            <div class="d-flex flex-column {{ $msg->user->isOwner() ? 'align-items-end' : 'align-items-start' }}">
                                <div class="small fw-bold mb-1 text-dark">{{ $msg->user->name }} <span class="text-muted fw-normal ms-2" style="font-size: 11px">{{ $msg->created_at->diffForHumans() }}</span></div>
                                <div class="px-4 py-3 rounded-4 shadow-sm fw-medium d-inline-block {{ $msg->user->isOwner() ? 'bg-primary text-white text-start' : 'bg-light text-dark' }}" style="max-width: 80%">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Responder --}}
                @if($ticket->status != 'closed' && $ticket->status != 'resolved')
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" class="form-control rounded-4 p-4 border-light shadow-none bg-light" rows="4" placeholder="Escriba su respuesta institucional aquí..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                            Enviar Respuesta <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-light p-5 rounded-4 text-center border border-light-subtle">
                    <i class="bi bi-lock-fill fs-2 text-muted mb-3 d-block"></i>
                    <p class="m-0 text-muted fw-bold">Este ticket se encuentra finalizado y no acepta más mensajes.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 bg-white sticky-top" style="top: 100px">
                <h5 class="fw-bold mb-4">Detalles del Caso</h5>
                <ul class="list-unstyled d-grid gap-3 small">
                    <li class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Estado Actual:</span>
                        <span class="badge bg-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'pending' ? 'warning' : 'success') }} rounded-pill px-3">{{ strtoupper($ticket->status) }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Prioridad:</span>
                        <span class="fw-bold text-dark">{{ strtoupper($ticket->priority) }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Institución:</span>
                        <span class="fw-bold text-primary">{{ $ticket->school->name }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Categoría:</span>
                        <span class="fw-bold text-dark">{{ strtoupper($ticket->category) }}</span>
                    </li>
                </ul>

                <hr class="my-4 opacity-50">

                <div class="d-grid gap-2">
                    @if($ticket->status != 'resolved' && $ticket->status != 'closed')
                    <form method="POST" action="{{ route('admin.tickets.resolve', $ticket) }}">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill w-100 py-3 fw-bold shadow-sm">
                            Marcar como Resuelto <i class="bi bi-check-lg ms-2"></i>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark rounded-pill w-100 py-3 fw-bold border-light opacity-75">
                            Cerrar Ticket Definitivamente <i class="bi bi-archive ms-2"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.impersonate', $ticket->school_id) }}" class="btn btn-light rounded-pill w-100 py-3 fw-bold border-light mt-2 small">
                        <i class="bi bi-eye me-1"></i> Ver contexto del cliente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('header', 'Ticket Soporte: #TKT-' . $ticket->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card card-premium p-4 p-lg-5 mb-4 mb-lg-0">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h5 class="fw-black m-0 ls-1 text-uppercase text-primary small">Hilo de Conversación</h5>
                    <div class="xx-small text-muted fw-bold text-uppercase ls-2">Iniciado el {{ $ticket->created_at->format('d M, Y H:i') }}</div>
                </div>

                {{-- Hilo de mensajes --}}
                <div class="d-grid gap-4 mb-5">
                    @foreach($ticket->messages as $msg)
                    @php $isOwner = $msg->user->isOwner(); @endphp
                    <div class="d-flex gap-3 {{ $isOwner ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="rounded-pill d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 42px; height: 42px; background: {{ $isOwner ? 'var(--primary-color)' : '#64748b' }}; border: 2px solid {{ $isOwner ? 'var(--primary-light)' : '#94A3B8' }}">
                                {{ substr($msg->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 {{ $isOwner ? 'text-end' : '' }}">
                            <div class="d-flex flex-column {{ $isOwner ? 'align-items-end' : 'align-items-start' }}">
                                <div class="xx-small fw-black mb-1 text-muted text-uppercase ls-1">
                                    {{ $msg->user->name }} • <span class="fw-medium">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="px-4 py-3 rounded-4 shadow-sm fw-medium d-inline-block border-crystalline {{ $isOwner ? 'bg-primary text-white text-start shadow-lg' : 'bg-light text-dark shadow-sm' }}" style="max-width: 85%; font-size: 0.95rem;">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Responder --}}
                @if($ticket->status != 'closed' && $ticket->status != 'resolved')
                <div class="mt-5 pt-4 border-top border-light border-opacity-10">
                    <h6 class="xx-small text-muted fw-bold text-uppercase ls-2 mb-4">REDACTAR RESPUESTA INSTITUCIONAL</h6>
                    <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
                        @csrf
                        <div class="mb-4">
                            <textarea name="message" class="form-control rounded-4 p-4 border shadow-none bg-light-subtle academy-search-group" rows="4" placeholder="Describa la solución técnica o administrativa..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                                Enviar Respuesta <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @else
                <div class="bg-light-subtle p-5 rounded-4 text-center border-crystalline mt-5">
                    <i class="bi bi-lock-fill fs-2 text-muted mb-3 d-block opacity-25"></i>
                    <p class="m-0 text-muted fw-bold xx-small ls-1 text-uppercase">Este ticket se encuentra finalizado y no acepta más mensajes.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-premium p-4 p-lg-5 mb-4 sticky-top" style="top: 100px">
                <h5 class="fw-black mb-4 ls-1 text-uppercase small">Resumen del Caso</h5>
                <ul class="list-unstyled d-grid gap-3 small">
                    <li class="d-flex justify-content-between border-bottom border-light border-opacity-10 pb-2">
                        <span class="text-muted fw-medium">Estado:</span>
                        <span class="badge bg-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'pending' ? 'warning text-dark' : 'success') }} rounded-pill px-3 py-1 xx-small fw-black ls-1">
                            {{ strtoupper($ticket->status) }}
                        </span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom border-light border-opacity-10 pb-2">
                        <span class="text-muted fw-medium">Prioridad:</span>
                        <span class="fw-black text-danger text-uppercase xx-small ls-2">{{ $ticket->priority }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom border-light border-opacity-10 pb-2">
                        <span class="text-muted fw-medium">Institución:</span>
                        <span class="fw-bold text-primary">{{ $ticket->school->name }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted fw-medium">Categoría:</span>
                        <span class="fw-bold text-dark text-uppercase xx-small">{{ $ticket->category }}</span>
                    </li>
                </ul>

                <hr class="my-4 opacity-10">

                <div class="d-grid gap-2">
                    @if($ticket->status != 'resolved' && $ticket->status != 'closed')
                    <form method="POST" action="{{ route('admin.tickets.resolve', $ticket) }}">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill w-100 py-3 fw-black shadow-sm xx-small ls-1">
                            MARCAR COMO RESUELTO <i class="bi bi-check-lg ms-2"></i>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.tickets.close', $ticket) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark rounded-pill w-100 py-3 fw-bold border-light opacity-50 xx-small ls-1">
                            CERRAR DEFINITIVAMENTE <i class="bi bi-archive-fill ms-2"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.impersonate', $ticket->school_id) }}" class="btn btn-light-subtle border-crystalline rounded-pill w-100 py-3 fw-bold mt-2 xx-small ls-1 text-uppercase">
                        <i class="bi bi-eye-fill me-1"></i> Análisis de Contexto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

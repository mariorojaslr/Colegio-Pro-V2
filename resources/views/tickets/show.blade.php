@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Outfit', sans-serif;">Detalle del <span class="text-primary text-uppercase">Ticket #TKT-{{ $ticket->id }}</span></h1>
            <p class="text-muted">Estado Actual: <span class="badge bg-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'pending' ? 'warning' : 'success') }} rounded-pill px-3">{{ strtoupper($ticket->status) }}</span></p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-none">
                <i class="bi bi-arrow-left me-2"></i> Volver a Soporte
            </a>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white mb-4">
                <h5 class="fw-bold mb-5" style="color: var(--primary-color)">Hilo de Mensajes</h5>

                <div class="d-grid gap-4 mb-5">
                    @foreach($ticket->messages as $msg)
                    <div class="d-flex gap-3 {{ $msg->user->id == Auth::id() ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                 style="width: 45px; height: 45px; background: {{ $msg->user->id == Auth::id() ? 'var(--primary-color)' : '#94A3B8' }}">
                                {{ substr($msg->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="flex-grow-1 {{ $msg->user->id == Auth::id() ? 'text-end' : '' }}">
                            <div class="d-flex flex-column {{ $msg->user->id == Auth::id() ? 'align-items-end' : 'align-items-start' }}">
                                <div class="small fw-bold mb-1 text-dark">{{ $msg->user->name }} <span class="text-muted fw-normal ms-2" style="font-size: 11px">{{ $msg->created_at->diffForHumans() }}</span></div>
                                <div class="px-4 py-3 rounded-4 shadow-sm fw-medium d-inline-block {{ $msg->user->id == Auth::id() ? 'bg-primary text-white text-start' : 'bg-light text-dark' }}" style="max-width: 80%">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($ticket->status != 'closed' && $ticket->status != 'resolved')
                <form method="POST" action="{{ route('tickets.reply', $ticket) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" class="form-control rounded-4 p-4 border-light shadow-none bg-light" rows="4" placeholder="Escriba su mensaje aquí..." required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                            Responder Ticket <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-light p-5 rounded-4 text-center border border-light-subtle">
                    <i class="bi bi-lock-fill fs-2 text-muted mb-3 d-block"></i>
                    <p class="m-0 text-muted fw-bold">Este ticket se encuentra cerrado.</p>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 bg-white sticky-top" style="top: 100px">
                <h5 class="fw-bold mb-4">Información Adicional</h5>
                <ul class="list-unstyled d-grid gap-3 small">
                    <li class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Prioridad:</span>
                        <span class="fw-bold text-dark">{{ strtoupper($ticket->priority) }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Categoría:</span>
                        <span class="fw-bold text-dark">{{ strtoupper($ticket->category) }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Actualizado:</span>
                        <span class="fw-bold text-dark">{{ $ticket->updated_at->format('d/m/y H:i') }}</span>
                    </li>
                </ul>

                <hr class="my-4 opacity-50">

                <div class="p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                    <h6 class="fw-bold text-primary mb-2">Ayuda Inmediata?</h6>
                    <p class="small text-muted mb-0">Nuestro equipo de soporte está operando de lunes a viernes, de 9:00 a 18:00 (GMT-3).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

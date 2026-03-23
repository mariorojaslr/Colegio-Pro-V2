@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="display-5 fw-bold mb-1" style="font-family: 'Outfit', sans-serif;">Centro de <span class="text-primary">Soporte</span></h1>
            <p class="text-muted fs-5">Canal directo de comunicación con nuestro equipo técnico y comercial.</p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('tickets.create') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg">
                <i class="bi bi-plus-lg me-2"></i> Nuevo Ticket
            </a>
        </div>
    </div>

    <div class="row g-4">
        @forelse($tickets as $ticket)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white hover-up transition-all">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge bg-{{ $ticket->status == 'open' ? 'primary' : ($ticket->status == 'pending' ? 'warning' : 'success') }} rounded-pill px-3 py-1 fw-bold">
                        {{ strtoupper($ticket->status) }}
                    </span>
                    <span class="small text-muted fw-bold">#TKT-{{ $ticket->id }}</span>
                </div>
                
                <h5 class="fw-bold text-dark mb-2">{{ $ticket->subject }}</h5>
                <p class="text-muted small flex-grow-1">{{ Str::limit($ticket->messages->first()->message ?? 'Sin mensaje', 100) }}</p>

                <hr class="my-3 opacity-25">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-uppercase fw-bold text-muted ls-1" style="font-size: 10px">
                        {{ $ticket->category == 'technical' ? '🔧 Técnico' : '💳 Facturación' }}
                    </span>
                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-light btn-sm rounded-pill px-4 fw-bold">Ver Detalles</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="rounded-circle bg-light p-4 d-inline-block mb-3">
                <i class="bi bi-chat-heart fs-1 text-muted"></i>
            </div>
            <h4 class="fw-bold text-muted">Todo en orden</h4>
            <p class="text-muted">No tienes solicitudes pendientes en este momento.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .hover-up:hover { transform: translateY(-5px); }
</style>
@endsection

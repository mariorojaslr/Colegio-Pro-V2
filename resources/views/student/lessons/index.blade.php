@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="display-6 fw-bold mb-1" style="font-family: 'Outfit', sans-serif;">Mi Aula <span class="text-primary text-gradient">Virtual</span></h1>
            <p class="text-muted">Accede a tus clases, talleres y material de video exclusivo.</p>
        </div>
        <div class="col-lg-auto d-flex gap-2">
            <button class="btn btn-light rounded-pill px-4 fw-bold border-light shadow-sm">Todas las Clases</button>
            <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-lg">En Vivo <i class="bi bi-circle-fill text-danger ms-1 small animate-pulse"></i></button>
        </div>
    </div>

    <div class="row g-4">
        @forelse($lessons as $lesson)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 rounded-4 overflow-hidden bg-white shadow-lg transition-all hover-scale position-relative">
                {{-- Badge de Tipo --}}
                <div class="position-absolute top-0 start-0 m-3 z-3">
                    @if($lesson->is_live)
                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold shadow-sm animate-pulse"><i class="bi bi-broadcast me-1"></i> EN VIVO</span>
                    @else
                    <span class="badge bg-dark bg-opacity-75 rounded-pill px-3 py-2 fw-bold shadow-sm">GRABADO</span>
                    @endif
                </div>

                {{-- Thumbnail simulado --}}
                <div class="position-relative overflow-hidden" style="height: 200px; background: #0f172a">
                    <div class="d-flex align-items-center justify-content-center h-100 opacity-50">
                         <i class="bi bi-play-circle display-1 text-white opacity-25"></i>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-2 text-truncate">{{ $lesson->title }}</h5>
                    <p class="text-muted small mb-4 lh-sm">{{ Str::limit($lesson->description, 80) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted fw-bold"><i class="bi bi-clock me-1"></i> {{ $lesson->created_at->format('d M') }}</span>
                        <a href="{{ route('student.lessons.show', $lesson) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Ver Clase</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
             <i class="bi bi-collection-play fs-1 text-muted mb-3 d-block"></i>
             <h4 class="fw-bold text-muted">Aún no hay clases publicadas</h4>
        </div>
        @endforelse
    </div>
</div>

<style>
    .hover-scale { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important; z-index: 10; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .animate-pulse { animation: pulse 1.5s infinite ease-in-out; }
</style>
@endsection

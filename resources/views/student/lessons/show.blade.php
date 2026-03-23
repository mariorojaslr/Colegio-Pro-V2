@extends('layouts.main')

@section('content')
<div class="container-fluid px-4 py-4 min-vh-100 bg-light-subtle">
    <div class="row g-4">
        {{-- Área del Jugador / Contenido Principal --}}
        <div class="col-lg-9">
            <div class="card border-0 shadow-lg overflow-hidden mb-4" style="border-radius: 24px; background: #0f172a;">
                {{-- Contenedor del reproductor Bunny.net --}}
                @if($lesson->is_live)
                    <div class="ratio ratio-16x9 bg-black d-flex align-items-center justify-content-center text-center">
                        <div class="position-absolute z-3 p-4">
                            <div class="animate-pulse mb-3">
                                <span class="badge bg-danger rounded-pill px-3 py-1 fw-black xx-small ls-2">CLASE EN VIVO</span>
                            </div>
                            <h1 class="text-white fw-black mb-4 h3" style="font-family: 'Outfit', sans-serif;">{{ $lesson->title }}</h1>
                            <p class="text-white-50 mb-4 small fw-light mx-auto" style="max-width: 500px;">Esta sesión se está transmitiendo en tiempo real a través de nuestra infraestructura dedicada.</p>
                            <a href="{{ $lesson->live_url }}" target="_blank" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-black shadow-lg">
                                <i class="bi bi-broadcast me-2"></i> UNIRME AHORA
                            </a>
                        </div>
                        <div class="bg-black w-100 h-100 opacity-50"></div>
                    </div>
                @else
                    <div style="position:relative;padding-top:56.25%;">
                        <iframe src="https://iframe.mediadelivery.net/embed/{{ config('services.bunny.stream.library_id') }}/{{ $lesson->bunny_video_id }}?autoplay=false&loop=false&muted=false&preload=true&responsive=true" 
                                loading="lazy" 
                                style="border:0;position:absolute;top:0;left:0;width:100%;height:100%;" 
                                allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
                                allowfullscreen="true">
                        </iframe>
                    </div>
                @endif
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                <div class="row align-items-center gy-4">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 xx-small fw-black ls-1 uppercase">
                                {{ $lesson->category ?? 'CURSO ACADÉMICO' }}
                            </span>
                            <span class="text-muted xx-small fw-bold border-start ps-2 ls-1 uppercase">DRM PROTECTED</span>
                        </div>
                        <h1 class="h2 fw-black text-dark mb-4" style="font-family: 'Outfit', sans-serif;">{{ $lesson->title }}</h1>
                        <p class="text-secondary fw-light lh-lg mb-0" style="font-size: 1.05rem;">{{ $lesson->description }}</p>
                    </div>
                    <div class="col-md-4 text-md-end d-flex flex-column gap-3">
                        <div class="p-3 bg-light rounded-4 border border-light-subtle shadow-none">
                            <div class="text-muted xx-small fw-bold uppercase ls-1 mb-1">DURACIÓN ESTIMADA</div>
                            <div class="fw-black h5 text-dark m-0">{{ $lesson->duration ?? '4 Semanas' }}</div>
                        </div>
                        <div class="d-flex gap-2 justify-content-md-end">
                            <button class="btn btn-outline-dark btn-sm rounded-pill px-3 py-2 fw-black xx-small shadow-none"><i class="bi bi-star me-1"></i> FAVORITOS</button>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-black xx-small shadow-none"><i class="bi bi-download me-1"></i> MATERIAL PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar de Información --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="text-primary xx-small fw-bold ls-2 uppercase mb-4">DETALLES INSTITUCIONALES</h6>
                
                <div class="d-flex align-items-center gap-3 mb-4 last-no-border">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted xx-small fw-bold uppercase ls-1">INSTITUCIÓN</div>
                        <div class="fw-bold text-dark small" style="font-family: 'Outfit', sans-serif;">{{ $lesson->school->name }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4 last-no-border">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted xx-small fw-bold uppercase ls-1">DOCENTE TITULAR</div>
                        <div class="fw-bold text-dark small">{{ $lesson->lecturer ?? 'Staff Académico' }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4 last-no-border">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 text-primary">
                        <i class="bi bi-patch-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted xx-small fw-bold uppercase ls-1">CERTIFICACIÓN</div>
                        <div class="fw-bold text-dark small">Técnica Profesional</div>
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                @if($lesson->resources->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-white-50 xx-small fw-bold ls-2 uppercase mb-3"><i class="bi bi-box-arrow-in-down me-2"></i> MATERIALES DE CLASE</h6>
                        <div class="d-flex flex-column gap-2">
                            @foreach($lesson->resources as $resource)
                                @php
                                    $icon = match($resource->type) {
                                        'pdf' => 'bi-file-pdf text-danger',
                                        'slides' => 'bi-file-earmark-ppt text-warning',
                                        'link' => 'bi-link-45deg text-primary',
                                        'word' => 'bi-file-earmark-word text-info',
                                        'excel' => 'bi-file-earmark-excel text-success',
                                        default => 'bi-file-earmark'
                                    };
                                @endphp
                                <a href="{{ $resource->file_url }}" target="_blank" class="text-decoration-none d-flex align-items-center gap-3 p-2 rounded-3 transition-all resource-link" style="background: rgba(255,255,255,0.03)">
                                    <i class="bi {{ $icon }} fs-5"></i>
                                    <span class="xx-small fw-bold text-white-50 uppercase ls-1">{{ $resource->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($lesson->exam)
                    <div class="p-4 rounded-4 bg-primary bg-opacity-10 mb-4 border border-primary border-opacity-10 text-center">
                        <h6 class="text-primary xx-small fw-bold ls-2 uppercase mb-3"><i class="bi bi-card-checklist me-2"></i> EVALUACIÓN FINAL</h6>
                        <p class="xx-small text-muted mb-4 uppercase ls-1">{{ $lesson->exam->questions->count() }} PREGUNTAS • {{ $lesson->exam->passing_score }}% PARA APROBAR</p>
                        <a href="{{ route('student.exams.take', $lesson->exam->id) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-black xx-small shadow-sm">
                            REALIZAR EXAMEN
                        </a>
                    </div>
                @endif

                <div class="bg-dark rounded-4 p-3 text-white">
                    <h6 class="xx-small fw-bold ls-2 uppercase mb-2 text-warning"><i class="bi bi-shield-lock me-2"></i> SEGURIDAD</h6>
                    <p class="xx-small text-white-50 m-0 fw-light">Suscripción verificada. IP registrada. Prohibida la redistribución.</p>
                </div>
            </div>

            <a href="{{ route('student.lessons.index') }}" class="btn btn-dark w-100 rounded-pill py-3 fw-black shadow-sm" style="font-size: 0.8rem;">
                <i class="bi bi-arrow-left me-2"></i> VOLVER A LA ACADEMIA
            </a>
        </div>
    </div>
</div>

<style>
    .xx-small { font-size: 9px; }
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .fw-black { font-weight: 900; }
    @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } 100% { transform: scale(1); opacity: 1; } }
    .animate-pulse { animation: pulse 2s infinite ease-in-out; }
    .last-no-border:last-child { margin-bottom: 0 !important; }
    .resource-link:hover {
        background: rgba(255,255,255,0.08) !important;
        transform: translateX(5px);
    }
    .resource-link:hover span {
        color: white !important;
    }
</style>
@endsection

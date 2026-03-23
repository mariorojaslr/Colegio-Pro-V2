@extends('layouts.main')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="row g-5">
        <div class="col-lg-9">
            <div class="card-prestige bg-dark rounded-5 overflow-hidden shadow-2xl position-relative mb-5" style="border: 4px solid #1e293b">
                {{-- Contenedor del reproductor Bunny.net --}}
                @if($lesson->is_live)
                    {{-- Si es en vivo y se usa una URL externa (Zoom/Meet/YouTube) --}}
                    <div class="ratio ratio-16x9 bg-black d-flex align-items-center justify-content-center text-center">
                        <div class="position-absolute z-3 p-4">
                            <i class="bi bi-broadcast text-danger fs-1 mb-3 d-block animate-pulse"></i>
                            <h2 class="text-white fw-bold mb-4">Esta clase se transmitirá en vivo</h2>
                            <a href="{{ $lesson->live_url }}" target="_blank" class="btn btn-danger btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg">Unirme a la Sesión en Vivo</a>
                        </div>
                        <div class="bg-black w-100 h-100 opacity-50"></div>
                    </div>
                @else
                    {{-- Si es grabado, usamos el embed de Bunny.net Stream --}}
                    <div style="position:relative;padding-top:56.25%;">
                        <iframe src="https://iframe.mediadelivery.net/embed/{{ env('BUNNY_LIBRARY_ID', '216543') }}/{{ $lesson->bunny_video_id }}?autoplay=false&loop=false&muted=false&preload=true&responsive=true" 
                                loading="lazy" 
                                style="border:0;position:absolute;top:0;left:0;width:100%;height:100%;" 
                                allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
                                allowfullscreen="true">
                        </iframe>
                    </div>
                @endif
            </div>

            <div class="row align-items-start gy-4 mb-5">
                <div class="col">
                    <h1 class="display-6 fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">{{ $lesson->title }}</h1>
                    <p class="text-muted fs-5 lh-lg mb-0">{{ $lesson->description }}</p>
                </div>
                <div class="col-lg-auto d-flex flex-column gap-3 text-end">
                    <button class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-none"><i class="bi bi-star me-2"></i> Favoritos</button>
                    <button class="btn btn-outline-info rounded-pill px-4 py-2 fw-bold shadow-none"><i class="bi bi-file-earmark-pdf me-2"></i> Material PDF</button>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card-prestige bg-white rounded-5 p-5 shadow-2xl mb-4 border-0">
                <h5 class="fw-bold mb-4 text-center">Detalles Institucionales</h5>
                <hr class="mb-4 opacity-50">
                
                <ul class="list-unstyled d-grid gap-4 small mb-5">
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-building fs-3 text-primary"></i>
                        <div>
                             <div class="text-muted fw-bold small text-uppercase ls-1">Centro Educativo</div>
                             <div class="fw-bold fs-6">{{ $lesson->school->name }}</div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-calendar-check fs-3 text-primary"></i>
                        <div>
                             <div class="text-muted fw-bold small text-uppercase ls-1">Fecha de Publicación</div>
                             <div class="fw-bold fs-6">{{ $lesson->created_at->format('d/m/Y') }}</div>
                        </div>
                    </li>
                </ul>

                <hr class="mb-4 opacity-50">

                <div class="p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10 mb-4">
                    <h6 class="fw-bold text-primary mb-2 small"><i class="bi bi-shield-lock me-1"></i> Contenido Protegido</h6>
                    <p class="small text-muted mb-0">Esta clase está protegida por DRM en la infraestructura de Bunny.net.</p>
                </div>

                <a href="{{ route('student.lessons.index') }}" class="btn btn-primary rounded-pill w-100 py-3 fw-bold shadow-lg">Volver a mis clases <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .animate-pulse { animation: pulse 1.5s infinite ease-in-out; }
</style>
@endsection

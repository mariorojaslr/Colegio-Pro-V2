@extends('layouts.main')

@section('content')
@push('css')
<style>
    .academy-hero {
        position: relative;
        height: 60vh;
        min-height: 450px;
        border-radius: 40px;
        overflow: hidden;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80') center/cover;
        display: flex;
        align-items: center;
        padding: 5%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        margin-bottom: 3rem;
    }
    .hero-content {
        max-width: 600px;
        z-index: 2;
    }
    .hero-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 1px;
        color: white;
    }
    .course-card-netflix {
        background: white;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
    }
    .course-card-netflix:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        z-index: 10;
        border-color: var(--bs-primary);
    }
    .progress-compact {
        height: 4px;
        background: rgba(0,0,0,0.1);
        border-radius: 2px;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
    }
    .glass-nav {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
    }
</style>
@endpush

<div class="container-fluid px-4 py-4 bg-light-subtle">
    
    {{-- Billboard / Hero Style Netflix --}}
    <div class="academy-hero text-white animate__animated animate__fadeIn">
        <div class="hero-content">
            <div class="hero-badge mb-3 text-uppercase">
                <i class="bi bi-play-circle-fill me-2"></i> Continuar viendo
            </div>
            <h1 class="display-3 fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">RCP y Primeros <br><span class="text-primary">Auxilios</span></h1>
            <p class="lead mb-4 opacity-75 fw-medium">Domine las técnicas vitales de reanimación cardiopulmonar con certificación oficial obligatoria para profesionales.</p>
            
            <div class="d-flex gap-3 align-items-center">
                <a href="#" class="btn btn-white btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="bi bi-play-fill me-2"></i> Reproducir ahora
                </a>
                <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold" style="border-width: 2px; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px);">
                    <i class="bi bi-info-circle me-2"></i> Detalles
                </a>
            </div>
            
            <div class="mt-4 d-flex align-items-center gap-2">
                <div class="progress" style="width: 200px; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 40%"></div>
                </div>
                <span class="small fw-bold opacity-75">40% completado</span>
            </div>
        </div>
    </div>

    {{-- Categorías y Cursos --}}
    <div class="section-courses mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <h4 class="fw-bold mb-0">Catálogo de <span class="text-primary">Especializaciones</span></h4>
                <p class="text-muted small mb-0">Formación continua con certificación oficial</p>
            </div>
            <a href="#" class="text-primary fw-bold text-decoration-none small ls-1">FILTRAR POR ÁREA <i class="bi bi-funnel"></i></a>
        </div>

    {{-- Categorías y Cursos --}}
    <div class="section-courses mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <h4 class="fw-bold mb-0">Catálogo de <span class="text-primary">Especializaciones</span></h4>
                <p class="text-muted small mb-0">Haga clic en un curso para ver detalles y aranceles</p>
            </div>
            <a href="#" class="text-primary fw-bold text-decoration-none small ls-1">FILTRAR ÁREAS <i class="bi bi-funnel"></i></a>
        </div>

    {{-- Categorías y Cursos --}}
    <div class="section-courses mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <h4 class="fw-bold mb-0">Catálogo de <span class="text-primary">Especializaciones</span></h4>
                <p class="text-muted small mb-0">Haga clic en el póster para ver detalles y aranceles</p>
            </div>
            <a href="#" class="text-primary fw-bold text-decoration-none small ls-1 text-uppercase">Áreas de Estudio <i class="bi bi-funnel ms-1"></i></a>
        </div>

        <div class="row g-4 mb-5">
            @php
                $especialidades = [
                    ['Salud', 'RCP & Primeros Auxilios', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_rcp_course_1774222864134.png', 'Dr. Roberto García', '12h', '25.000'],
                    ['Legal Tech', 'Arquitectura Legal en Salud', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_legal_health_1774222880824.png', 'Dra. Elena Martínez', '15h', '38.000'],
                    ['Gestión', 'Innovación en Gestión Judicial', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_judicial_innovation_1774222897356.png', 'Dr. Juan Pérez', '8h', '45.000'],
                    ['Penal', 'Reformas Procesales 2026', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f', 'Dr. Sergio Massa', '20h', '55.000'],
                    ['Sucesiones', 'Práctica en Juicios Sucesorios', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85', 'Dra. María Lopez', '10h', '40.000'],
                    ['Tributario', 'Actualización AFIP 2026', 'https://images.unsplash.com/photo-1554224155-16974301755d', 'Cont. Ana Sosa', '14h', '42.000'],
                    ['Comercial', 'Contratos Modernos y Startups', 'https://images.unsplash.com/photo-1454165833767-0275084927ed', 'Dr. Luis Caputo', '11h', '35.000'],
                    ['Admin', 'Procedimiento Administrativo', 'https://images.unsplash.com/photo-1423592707957-3b212afa6733', 'Dra. Patricia Bull', '9h', '28.000'],
                    ['Mediación', 'Resolución de Conflictos', 'https://images.unsplash.com/photo-1573164773501-229ef2159f81', 'Dr. Jorge Macri', '12h', '30.000'],
                    ['Inmobiliario', 'Inmobiliario y Práctica', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa', 'Dra. Victoria V.', '10h', '34.000'],
                    ['Civil', 'Responsabilidad Civil Prof.', 'https://images.unsplash.com/photo-1505664194779-8beaceb93744', 'Dr. Ricardo Darín', '6h', '25.000'],
                    ['Idiomas', 'Legal English for Lawyers', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b', 'Prof. Sarah Connor', '30h', '60.000'],
                ];
            @endphp

            @foreach($especialidades as $index => $esp)
            <div class="col-md-3">
                <div class="course-poster-wrapper cursor-pointer shadow-hover transition-all" 
                     data-bs-toggle="modal" data-bs-target="#courseModal{{ $index }}" 
                     style="border-radius: 20px; overflow: hidden; aspect-ratio: 2/3;">
                    <div class="position-relative h-100 poster-zoom-container">
                        <img src="{{ Str::startsWith($esp[2], 'http') ? $esp[2] : asset($esp[2]) }}?auto=format&fit=crop&w=800&q=80" 
                             alt="{{ $esp[1] }}" class="img-fluid h-100 w-100 object-fit-cover transition-all">
                        
                        {{-- Overlay con información básica (estilo Netflix) --}}
                        <div class="poster-overlay p-4 d-flex flex-column justify-content-end text-white">
                            <span class="badge bg-primary rounded-pill x-small px-3 py-1 mb-2 d-inline-block">{{ $esp[0] }}</span>
                            <h6 class="fw-bold mb-0 ls-n1">{{ $esp[1] }}</h6>
                            <div class="mt-2 opacity-0 hover-opacity-100 transition-all">
                                <span class="x-small fw-bold"><i class="bi bi-play-circle-fill text-white"></i> Ver Detalles</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal detallado (se mantiene la lógica que ya funciona) --}}
            <div class="modal fade" id="courseModal{{ $index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
                        <div class="row g-0">
                            <div class="col-md-5 d-none d-md-block" style="background: url('{{ $esp[2] }}?auto=format&fit=crop&w=800&q=80') center/cover;">
                                <div class="h-100 min-vh-50 bg-black bg-opacity-25"></div>
                            </div>
                            <div class="col-md-7 p-4 p-lg-5 bg-white">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold ls-1">{{ $esp[0] }}</span>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <h2 class="fw-bold text-dark mb-3">{{ $esp[1] }}</h2>
                                <p class="text-muted mb-4 small">Llevamos la formación profesional al siguiente nivel con casos prácticos y material digital exclusivo diseñado para el ejercicio actual del derecho.</p>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="bg-light p-3 rounded-4">
                                            <p class="x-small text-muted mb-1 fw-bold text-uppercase ls-1">Duración</p>
                                            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock me-1 text-primary"></i> {{ $esp[4] }} totales</h6>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-light p-3 rounded-4">
                                            <p class="x-small text-muted mb-1 fw-bold text-uppercase ls-1">Docente</p>
                                            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-person-badge me-1 text-primary"></i> {{ $esp[3] }}</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <p class="x-small text-muted mb-0 fw-bold text-uppercase ls-1">Valor del curso</p>
                                        <h3 class="mb-0 fw-bold text-dark">${{ $esp[5] }} <span class="x-small text-muted fw-normal">AR$</span></h3>
                                    </div>
                                    <button class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm">
                                        Empezar ahora <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <div class="d-flex gap-2">
                                    <span class="badge bg-light text-dark border rounded-pill x-small px-3 fw-normal">Certificado con QR</span>
                                    <span class="badge bg-light text-dark border rounded-pill x-small px-3 fw-normal">Material Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </div>

    {{-- Sección de Certificados Estilo "Timeline" o Premium --}}
    <div class="section-awards mt-5 px-2">
        <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-award-fill me-2 text-warning"></i> Trayectoria y <span class="text-primary">Certificaciones</span></h4>
        <div class="glass-nav p-4 shadow-sm bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr class="x-small fw-bold text-muted text-uppercase">
                            <th class="py-3 px-4">Capacitación</th>
                            <th class="py-3">Especialidad</th>
                            <th class="py-3">Calificación</th>
                            <th class="py-3 text-end px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded-3 text-danger"><i class="bi bi-file-earmark-pdf-fill fs-4"></i></div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Diplomado en Derecho Administrativo</h6>
                                        <p class="x-small text-muted mb-0">Emitido el 15/03/2026</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border rounded-pill px-3">Administrativo</span></td>
                            <td><span class="fw-bold text-primary">Aprobado (10/10)</span></td>
                            <td class="text-end px-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-dark rounded-start-pill px-3 fw-bold">Descargar</button>
                                    <button class="btn btn-sm btn-dark rounded-end-pill px-3 fw-bold"><i class="bi bi-qr-code"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

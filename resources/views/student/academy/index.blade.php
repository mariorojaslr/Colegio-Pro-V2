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
                <h4 class="fw-bold mb-0">Lo más nuevo en <span class="text-primary">Derecho</span></h4>
                <p class="text-muted small mb-0">Especializaciones actualizadas para el ciclo 2026</p>
            </div>
            <a href="#" class="text-primary fw-bold text-decoration-none small ls-1">VER TODO <i class="bi bi-chevron-right"></i></a>
        </div>

        <div class="row g-4 mb-5">
            {{-- Tarjeta de Curso 1 --}}
            <div class="col-md-3">
                <div class="course-card-netflix shadow-sm bg-white h-100 p-0 border-0">
                    <img src="https://images.unsplash.com/photo-1505664194779-8beaceb93744?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Curso" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="x-small fw-bold text-primary text-uppercase">Derecho Civil</span>
                            <span class="x-small text-muted fw-bold">12h 45m</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Estrategia en Gestión Judicial Predictiva</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                            <span class="x-small text-success fw-bold">Actualizado hoy</span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-dark btn-sm rounded-pill fw-bold">Empezar ahora</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Curso 2 --}}
            <div class="col-md-3">
                <div class="course-card-netflix shadow-sm bg-white h-100 p-0 border-0">
                    <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Curso" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="x-small fw-bold text-warning text-uppercase">Legal Tech</span>
                            <span class="x-small text-muted fw-bold">8h 20m</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">IA Aplicada al Ejercicio Profesional</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-primary rounded-circle" style="width: 8px; height: 8px;"></div>
                            <span class="x-small text-primary fw-bold">Nuevo Lanzamiento</span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-dark btn-sm rounded-pill fw-bold">Empezar ahora</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Curso 3 --}}
            <div class="col-md-3">
                <div class="course-card-netflix shadow-sm bg-white h-100 p-0 border-0">
                    <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Curso" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="x-small fw-bold text-danger text-uppercase">Penal</span>
                            <span class="x-small text-muted fw-bold">15h 00m</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Reformas Procesales 2026: Guía Práctica</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-secondary rounded-circle" style="width: 8px; height: 8px;"></div>
                            <span class="x-small text-muted fw-bold">Ciclo Regular</span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-dark btn-sm rounded-pill fw-bold">Empezar ahora</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarjeta de Curso 4 --}}
            <div class="col-md-3">
                <div class="course-card-netflix shadow-sm bg-white h-100 p-0 border-0">
                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2923216?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Curso" class="img-fluid" style="height: 180px; width: 100%; object-fit: cover;">
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="x-small fw-bold text-success text-uppercase">Laboral</span>
                            <span class="x-small text-muted fw-bold">6h 10m</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">Negociación Colectiva y Teletrabajo</h6>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                            <span class="x-small text-success fw-bold">Certificado QR incluído</span>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-dark btn-sm rounded-pill fw-bold">Empezar ahora</button>
                        </div>
                    </div>
                </div>
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

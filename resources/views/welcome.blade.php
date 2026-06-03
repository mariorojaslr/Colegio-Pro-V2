@extends('layouts.main')

@section('title', 'Colegio de Terapistas Ocupacionales')

@section('content')
<!-- Barra de Navegación Institucional Superior -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="#">
            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 10px; display:flex; justify-content:center; align-items:center;">
                <i class="bi bi-shield-plus text-white fs-4"></i>
            </div>
            <span style="font-family: 'Outfit', sans-serif; letter-spacing: 1px;">Colegio de Terapistas <span class="text-primary">Ocupacionales</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-medium" style="font-size: 0.9rem;">
                @if(isset($mainMenu) && $mainMenu)
                    @foreach($mainMenu->items as $item)
                        @if($item->children->count() > 0)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    {{ $item->title }}
                                </a>
                                <ul class="dropdown-menu shadow-lg border-0 bg-dark">
                                    @foreach($item->children as $child)
                                        @if($child->is_active)
                                            <li><a class="dropdown-item text-white hover-gold" href="{{ $child->page_id ? route('public.page', $child->page->slug) : $child->url }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ $item->page_id ? route('public.page', $item->page->slug) : $item->url }}" target="{{ $item->target }}">{{ $item->title }}</a></li>
                        @endif
                    @endforeach
                @else
                    <li class="nav-item"><a class="nav-link active" href="#institucional">Institucional</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ejercicio">Ejercicio Profesional</a></li>
                    <li class="nav-item"><a class="nav-link" href="#capacitaciones">Capacitaciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                @endif
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 shadow-sm" style="font-size: 0.9rem;">Portal de Autogestión</a>
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-size: 0.9rem; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border:none;">Pagar Cuota</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section Espectacular / Slider Dinámico -->
@if(isset($slider) && $slider->items->count() > 0)
    <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" style="min-height: 100vh;">
        <div class="carousel-inner h-100">
            @foreach($slider->items->where('is_active', true) as $index => $slide)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }} h-100 position-relative" style="min-height: 100vh; background: url('{{ $slide->image_url }}') center/cover fixed;">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.7) 100%);"></div>
                    <section class="h-100 d-flex align-items-center position-relative pt-5">
                        <div class="container mt-5">
                            <div class="row">
                                <div class="col-lg-7 text-white">
                                    <h1 class="display-3 fw-black mb-4 animate__animated animate__fadeInUp" style="font-family: 'Outfit', sans-serif; line-height: 1.1;">
                                        {{ $slide->title }}
                                    </h1>
                                    <p class="lead mb-5 opacity-75 fw-light animate__animated animate__fadeInUp animate__delay-1s" style="font-size: 1.25rem; max-width: 600px;">
                                        {{ $slide->subtitle }}
                                    </p>
                                    @if($slide->button_text)
                                        <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                                            <a href="{{ $slide->button_link }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-dark shadow-lg">{{ $slide->button_text }}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>
        @if($slider->items->where('is_active', true)->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        @endif
    </div>
@else
    <div class="position-relative overflow-hidden" style="min-height: 100vh; background: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center/cover fixed;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to right, rgba(15,23,42,0.95) 0%, rgba(15,23,42,0.7) 100%);"></div>
        <section class="h-100 d-flex align-items-center position-relative pt-5">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-7 text-white">
                        <span class="badge rounded-pill bg-primary bg-opacity-25 text-info mb-3 px-3 py-2 border border-info border-opacity-25">ASOCIACIÓN PROFESIONAL</span>
                        <h1 class="display-3 fw-black mb-4" style="font-family: 'Outfit', sans-serif; line-height: 1.1;">
                            Excelencia y Ética en la <br><span style="background: linear-gradient(120deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Terapia Ocupacional</span>
                        </h1>
                        <p class="lead mb-5 opacity-75 fw-light" style="font-size: 1.25rem; max-width: 600px;">
                            Promovemos el desarrollo científico, ético y profesional de nuestros colegiados, garantizando la calidad de la atención en salud para toda la comunidad.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-dark shadow-lg">Ingresar al Portal</a>
                            <a href="#quienes-somos" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-medium backdrop-blur">Conocer más</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endif

<!-- Quiénes Somos & Organigrama -->
<section id="institucional" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-5">
        <div class="text-center mb-5 pb-3">
            <h6 class="text-primary fw-bold text-uppercase tracking-wider">Nuestro Equipo</h6>
            <h2 class="display-5 fw-bold text-dark" style="font-family: 'Outfit', sans-serif;">Comisión Directiva</h2>
            <p class="text-muted fs-5 mx-auto" style="max-width: 700px;">Conozca a los profesionales que lideran nuestra institución, dedicados a fortalecer la práctica de la Terapia Ocupacional.</p>
        </div>

        <!-- Organigrama Glassmorphism -->
        <div class="row justify-content-center g-4">
            <!-- Presidenta -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 organigram-card position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.8) !important;">
                    <div style="height: 120px; background: linear-gradient(135deg, #3b82f6, #8b5cf6);"></div>
                    <div class="position-absolute w-100 text-center" style="top: 50px;">
                        @php
                            $presPhoto = asset('images/presidenta_colegio_1779502458929.png');
                        @endphp
                        <img src="{{ $presPhoto }}" alt="Presidenta" class="rounded-circle shadow-lg border border-4 border-white" style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <div class="card-body text-center pt-5 mt-4 pb-4 px-4">
                        <h4 class="fw-bold mb-1 mt-3" style="color: #1e293b;">Dra. Elena Vargas</h4>
                        <p class="text-primary fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Presidenta</p>
                        <p class="text-muted small mb-4">Especialista en Rehabilitación Neurológica con más de 20 años de trayectoria institucional.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="btn btn-sm btn-light text-primary rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secretario -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 organigram-card position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.8) !important;">
                    <div style="height: 120px; background: linear-gradient(135deg, #0ea5e9, #3b82f6);"></div>
                    <div class="position-absolute w-100 text-center" style="top: 50px;">
                        @php
                            $secPhoto = asset('images/secretario_colegio_1779502476148.png');
                        @endphp
                        <img src="{{ $secPhoto }}" alt="Secretario" class="rounded-circle shadow-lg border border-4 border-white" style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <div class="card-body text-center pt-5 mt-4 pb-4 px-4">
                        <h4 class="fw-bold mb-1 mt-3" style="color: #1e293b;">Lic. Martín Rossi</h4>
                        <p class="text-info fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Secretario General</p>
                        <p class="text-muted small mb-4">Coordinador de políticas públicas y normativas de la práctica profesional y legislación.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-light text-info rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="btn btn-sm btn-light text-info rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tesorera -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 organigram-card position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.8) !important;">
                    <div style="height: 120px; background: linear-gradient(135deg, #f59e0b, #ef4444);"></div>
                    <div class="position-absolute w-100 text-center" style="top: 50px;">
                        @php
                            $tesPhoto = asset('images/tesorera_colegio_1779502490381.png');
                        @endphp
                        <img src="{{ $tesPhoto }}" alt="Tesorera" class="rounded-circle shadow-lg border border-4 border-white" style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <div class="card-body text-center pt-5 mt-4 pb-4 px-4">
                        <h4 class="fw-bold mb-1 mt-3" style="color: #1e293b;">Lic. Valeria Montes</h4>
                        <p class="text-warning fw-bold small text-uppercase mb-3" style="letter-spacing: 1px;">Tesorera</p>
                        <p class="text-muted small mb-4">Especialista en administración institucional, encargada del fondo de becas y matriculación.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-sm btn-light text-warning rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="btn btn-sm btn-light text-warning rounded-circle" style="width: 35px; height:35px;"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ubicación y Contáctenos -->
<section id="contacto" class="py-5 bg-white position-relative">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Info Contacto -->
            <div class="col-lg-5">
                <h2 class="display-6 fw-bold mb-4 text-dark" style="font-family: 'Outfit', sans-serif;">Estamos para <span class="text-primary">Asistirlo</span></h2>
                <p class="lead text-muted mb-5">Si tiene alguna duda sobre matriculación, tribunal de ética o beneficios, no dude en acercarse o escribirnos.</p>
                
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-geo-alt-fill fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Nuestra Sede</h5>
                        <p class="text-muted mb-0">Av. San Martín 1234, Centro<br>La Rioja, Argentina</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-whatsapp fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">WhatsApp Institucional</h5>
                        <p class="text-muted mb-0">+54 9 380 412-3456</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-paper-fill fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Mesa de Entrada Digital</h5>
                        <p class="text-muted mb-0">contacto@colegioterapistas.org.ar</p>
                    </div>
                </div>
            </div>

            <!-- Mapa Decorativo y Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg" style="border-radius: 30px; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-12 p-5 bg-light">
                            <h4 class="fw-bold mb-4">Envíenos su consulta</h4>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-lg rounded-pill px-4" placeholder="Nombre completo">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control form-control-lg rounded-pill px-4" placeholder="Correo electrónico">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control form-control-lg rounded-pill px-4" placeholder="Asunto (Ej. Matrícula, Certificado Ética)">
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control rounded-4 p-4" rows="4" placeholder="Su mensaje..."></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="button" class="btn btn-primary btn-lg rounded-pill w-100 fw-bold shadow-sm" style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); border:none;">Enviar Mensaje Rápidamente</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Simple -->
<footer class="py-4 text-center text-white" style="background-color: #0f172a;">
    <div class="container">
        <p class="mb-0 opacity-75 small">© {{ date('Y') }} Colegio de Terapistas Ocupacionales. Todos los derechos reservados.</p>
        <p class="mb-0 opacity-50 small mt-1">Desarrollado con ♥ por Terapista SaaS</p>
    </div>
</footer>

@endsection

@section('styles')
<style>
    .fw-black { font-weight: 900; }
    .tracking-wider { letter-spacing: 2px; }
    
    .organigram-card {
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .organigram-card:hover {
        transform: translateY(-15px);
    }
    
    .backdrop-blur {
        backdrop-filter: blur(5px);
        background: rgba(255,255,255,0.1) !important;
    }
    .backdrop-blur:hover {
        background: rgba(255,255,255,0.2) !important;
    }
</style>
@endsection

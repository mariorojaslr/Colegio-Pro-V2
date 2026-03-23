@extends('layouts.main')

@section('title', 'Colegio-Pro | La Nueva Era de la Gestión Institucional')

@section('content')
<!-- Espectacular Hero Section with Mesh Gradient Background -->
<div class="position-relative overflow-hidden" style="background: radial-gradient(at 0% 0%, rgba(234, 179, 8, 0.1) 0px, transparent 50%), radial-gradient(at 100% 100%, rgba(15, 23, 42, 0.05) 0px, transparent 50%);">
    <section class="py-5 pt-lg-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start animate-fade-in">
                    <span class="badge rounded-pill mb-3 px-3 py-2 text-uppercase fw-bold" style="background: rgba(234, 119, 8, 0.1); color: #B45309; letter-spacing: 1px;">SaaS de Alto Rendimiento</span>
                    <h1 class="display-2 fw-black mb-4" style="font-family: 'Outfit', sans-serif; line-height: 1.1; color: var(--primary-color);">
                        Gestione su Colegio con <span style="background: linear-gradient(90deg, #0F172A, #EAB308); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Excelencia Digital</span>
                    </h1>
                    <p class="lead text-muted mb-5 fs-4 fw-light opacity-90 pe-lg-4">
                        La infraestructura definitiva para Colegios Profesionales que exigen seguridad, velocidad y una experiencia de usuario sin precedentes.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('demo.fast') }}" class="btn btn-premium btn-lg px-5 py-3 shadow-lg fs-5">Entrar como Probador (Demo)</a>
                        <a href="#ventajas" class="btn btn-white btn-lg px-5 py-3 shadow-sm border border-light-subtle" style="background: white; border-radius: var(--border-radius)">Explorar Ventajas</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-lg-0 mt-5 position-relative">
                    <div class="hero-image-wrapper p-3 glass-card" style="border-radius: 40px; transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);">
                        <img src="{{ asset('media/landing_hero.png') }}" alt="Plataforma Colegio-Pro" class="img-fluid rounded-4 shadow-2xl">
                    </div>
                    <!-- Decorative Elements -->
                    <div class="position-absolute d-none d-lg-block" style="top: -20px; right: -20px; width: 100px; height: 100px; background: var(--accent-color); border-radius: 20px; opacity: 0.2; z-index: -1;"></div>
                    <div class="position-absolute d-none d-lg-block" style="bottom: -10px; left: -10px; width: 60px; height: 60px; border: 4px solid var(--primary-color); border-radius: 15px; opacity: 0.1; z-index: -1;"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Ventajas Section -->
<section id="ventajas" class="py-5 bg-white">
    <div class="container py-lg-5">
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-4 fw-bold mb-3" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">Infraestructura <span style="color: var(--accent-color)">Poderosa</span></h2>
                <p class="text-dark fs-5 fw-medium">Tecnología de punta diseñada para la estabilidad y escalabilidad de su institución.</p>
            </div>
        </div>
        <div class="row g-4 overflow-hidden">
            <div class="col-md-4">
                <div class="feature-card p-5 h-100 transition-all border-0 glass-card">
                    <div class="mb-4">
                        <div class="icon-circle bg-primary-subtle d-flex align-items-center justify-content-center rounded-4" style="width: 64px; height: 64px; background-color: #F1F5F9">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="var(--primary-color)" class="bi bi-hdd-network" viewBox="0 0 16 16">
                                <path d="M4.5 5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM3 4.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1h-1V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v1H0V4zm0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1h-1V7a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v1H0V7zm0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1h-1v-1a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v1H0v-1z"/>
                                <path d="M2.614 12.614A3 3 0 1 0 0 13h1.614a2 2 0 1 1 1.5.7h1.39a3 3 0 1 0 0-1h-1.39a2 2 0 0 1-1.5-.7z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--primary-color)">Escalabilidad Ilimitada</h4>
                    <p class="text-dark opacity-75">Procesamiento de datos optimizado para manejar miles de colegiados con latencia cero.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-5 h-100 transition-all border-0 glass-card" style="border-top: 4px solid var(--accent-color) !important;">
                    <div class="mb-4">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-4" style="width: 64px; height: 64px; background-color: #FFFBEB">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#B45309" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                <path d="M8 .5c-.662 0-1.77.9-1.77 1.83V3h3.54v-.67C9.77 1.4 8.662.5 8 .5zM5.887 3V1.67C5.887.55 6.78 0 8 0s2.113.55 2.113 1.67V3h1.594a1 1 0 0 1 .951.69l.41 1.25a2 2 0 0 1 .052.46V8.5a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5.4c0-.156.018-.31.052-.46l.41-1.25a1 1 0 0 1 .951-.69h1.464z"/>
                                <path d="M3.193 10.735A8.07 8.07 0 0 1 8 10c1.883 0 3.618.63 5.007 1.685l.63-.805C12.188 9.8 10.188 9 8 9s-4.188.8-5.637 1.93l.83.805z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--primary-color)">Seguridad Industrial</h4>
                    <p class="text-dark opacity-75">Aislamiento de bases de datos por cada Colegio, garantizando que su información sensible jamás se exponga.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-5 h-100 transition-all border-0 glass-card">
                    <div class="mb-4">
                        <div class="icon-circle d-flex align-items-center justify-content-center rounded-4" style="width: 64px; height: 64px; background-color: #F1F5F9">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="var(--primary-color)" class="bi bi-stack" viewBox="0 0 16 16">
                                <path d="m14.12 10.163 1.715.858c.22.11.22.424 0 .534L8.267 15.34a.598.598 0 0 1-.534 0L.165 11.555a.299.299 0 0 1 0-.534l1.716-.858 5.317 2.659c.505.252 1.1.252 1.604 0l5.317-2.66zM7.733.063a.598.598 0 0 1 .534 0l7.568 3.784a.3.3 0 0 1 0 .535L8.267 8.165a.598.598 0 0 1-.534 0L.165 4.382a.299.299 0 0 1 0-.535L7.733.063z"/>
                                <path d="m14.12 6.576 1.715.858c.22.11.22.424 0 .534l-7.568 3.784a.598.598 0 0 1-.534 0L.165 7.968a.299.299 0 0 1 0-.534l1.716-.858 5.317 2.659c.505.252 1.1.252 1.604 0l5.317-2.659z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--primary-color)">Media de Alta Velocidad</h4>
                    <p class="text-dark opacity-75">Servidores dedicados para el almacenamiento de trámites, planos y videos, con descarga inmediata.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Expansión Global e Innovación -->
<section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);">
    <div class="container py-lg-5 text-center">
        <h2 class="display-5 fw-bold mb-5" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">Preparado para el <span style="color: var(--accent-color)">Mundo</span></h2>
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="glass-card p-5 h-100 border-0 shadow-sm text-start" style="border-radius: 35px;">
                    <div class="badge bg-primary rounded-pill px-3 py-2 mb-3">GLOBAL READY</div>
                    <h3 class="fw-bold mb-3">Soporte Multimoneda</h3>
                    <p class="text-muted fs-5 mb-4">No importa dónde se encuentre su institución. Configure cobros en Pesos, Dólares, Euros o cualquier moneda local con un solo clic.</p>
                    <div class="d-flex align-items-center gap-2 text-primary fw-bold">
                        <i class="bi bi-globe2 fs-4"></i>
                        <span>Venda sus servicios internacionalmente</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card p-5 h-100 border-0 shadow-sm text-start" style="border-radius: 35px;">
                    <div class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">EXPERIENCIA PREMIUM</div>
                    <h3 class="fw-bold mb-3">Academia Estilo Netflix</h3>
                    <p class="text-muted fs-5 mb-4">Ofrezca cursos y capacitaciones con una interfaz visual impactante. Sus asociados disfrutarán de una navegación fluida, profesional y adictiva.</p>
                    <div class="d-flex align-items-center gap-2 text-warning fw-bold">
                        <i class="bi bi-play-btn-fill fs-4"></i>
                        <span>Educar nunca fue tan visual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <div class="container py-lg-5">
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-7 order-2 order-lg-1">
                <div class="glass-card p-2 rounded-4 shadow-2xl">
                    <img src="{{ asset('media/dashboard_preview.png') }}" alt="Colegio-Pro Dashboard" class="img-fluid rounded-3">
                </div>
            </div>
            <div class="col-lg-5 order-1 order-lg-2 mb-5 mb-lg-0">
                <h2 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">La <span style="color: var(--accent-color)">experiencia</span> que sus asociados merecen.</h2>
                <div class="d-flex gap-3 mb-4">
                    <div class="mt-1 shadow-sm px-2 py-1 bg-accent rounded-circle d-flex align-items-center justify-content-center" style="background-color: var(--accent-color); width: 28px; height: 28px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="white" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold" style="color: var(--primary-color)">Instituciones de Formación</h5>
                        <p class="text-dark opacity-75 fw-medium">Ideal para Colegios Profesionales, Escuelas de Coaching, Centros de PNL y Federaciones de alto nivel.</p>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="mt-1 shadow-sm px-2 py-1 bg-accent rounded-circle d-flex align-items-center justify-content-center" style="background-color: var(--accent-color); width: 28px; height: 28px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="white" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                        </svg>
                    </div>
                    <div>
                        <h5 class="fw-bold" style="color: var(--primary-color)">Pagos y Facturación Automática</h5>
                        <p class="text-dark opacity-75 fw-medium">Sistema integrado para el cobro de cuotas colegiales con historial transparente.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h2 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">Gestión <span style="color: var(--accent-color)">Móvil</span></h2>
                <p class="text-dark fs-5 mb-4 fw-medium">Lleve su Colegio en el bolsillo. Una aplicación web diseñada para profesionales modernos.</p>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex align-items-center gap-2 fw-bold text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--accent-color)" class="bi bi-check2-circle" viewBox="0 0 16 16"><path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/><path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/></svg> Acceso a Credencial Digital</li>
                    <li class="mb-3 d-flex align-items-center gap-2 fw-bold text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--accent-color)" class="bi bi-check2-circle" viewBox="0 0 16 16"><path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/><path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/></svg> Notificaciones de Vencimientos</li>
                    <li class="mb-3 d-flex align-items-center gap-2 fw-bold text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--accent-color)" class="bi bi-check2-circle" viewBox="0 0 16 16"><path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/><path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/></svg> Inscripción a Eventos</li>
                </ul>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="text-center">
                    <img src="{{ asset('media/mobile_app.png') }}" alt="Colegio-Pro App" class="img-fluid" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section con GeoIP -->
<section id="pricing" class="py-5 bg-white">
    <div class="container py-lg-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">Planes <span style="color: var(--accent-color)">Hechos a Medida</span></h2>
            <div class="d-inline-flex align-items-center gap-2 bg-light px-4 py-2 rounded-pill mb-4 border shadow-sm">
                <i class="bi bi-geo-alt-fill text-primary"></i>
                <span class="small fw-bold">Precios detectados para: 
                    <span class="text-primary">{{ app(\App\Services\LocationService::class)->isFromArgentina() ? 'ARGENTINA' : 'EL EXTERIOR' }}</span>
                </span>
            </div>
            <p class="text-muted fs-5 mx-auto" style="max-width: 600px">Escoja la infraestructura que mejor se adapte al volumen de su institución profesional.</p>
        </div>

        <div class="row g-4">
            @foreach($plans as $plan)
            <div class="col-md-3">
                <div class="feature-card p-5 h-100 border-0 glass-card text-center d-flex flex-column {{ $plan->slug === 'professional' ? 'position-relative border-top border-5 border-info' : '' }}" 
                     style="border-radius: 30px; transition: all 0.3s ease;">
                    
                    @if($plan->slug === 'professional')
                    <div class="position-absolute top-0 start-50 translate-middle bg-info text-white px-3 py-1 rounded-pill small fw-bold mt-2" style="font-size: 10px">RECOMENDADO</div>
                    @endif

                    <h5 class="fw-bold text-dark mb-4 text-uppercase ls-1 opacity-75">{{ $plan->name }}</h5>
                    
                    <div class="mb-4">
                        <span class="display-5 fw-black text-primary">{{ $plan->getDisplayCurrencySymbol() }}</span>
                        <span class="display-5 fw-black text-primary">{{ number_format($plan->getDisplayPrice(), 0, ',', '.') }}</span>
                        @if($plan->interval === 'monthly')
                        <span class="text-muted small">/mes</span>
                        @endif
                    </div>

                    <ul class="list-unstyled text-start mb-5 flex-grow-1">
                        <li class="mb-3 small d-flex gap-2">
                            <i class="bi bi-person-check text-success"></i>
                            <strong>Hasta {{ number_format($plan->max_users, 0, ',', '.') }}</strong> usuarios
                        </li>
                        <li class="mb-3 small d-flex gap-2">
                            <i class="bi bi-hdd text-info"></i>
                            <strong>{{ $plan->max_storage }} GB</strong> de Almacenamiento
                        </li>
                        @foreach($plan->features as $feature)
                        <li class="mb-3 small d-flex gap-2">
                            <i class="bi bi-check2 text-primary"></i>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('demo.register', ['plan' => $plan->slug]) }}" 
                       class="btn {{ $plan->slug === 'professional' ? 'btn-primary' : 'btn-outline-dark' }} w-100 rounded-pill py-3 fw-bold shadow-sm">
                       Elegir este Plan
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <p class="text-center mt-5 small text-muted italic">¿Desea un plan a medida o soporte multimoneda específico? <a href="#" class="text-primary fw-bold">Contactar Ventas Globales</a></p>
    </div>
</section>
    <div class="container py-lg-5">
        <div class="glass-card p-5 p-lg-5 bg-primary text-white text-center position-relative overflow-hidden" style="border-radius: 40px; background-color: var(--primary-color) !important;">
            <div class="position-absolute opacity-10" style="bottom: -50px; left: -50px; width: 250px; height: 250px; background: white; border-radius: 50%"></div>
            <div class="position-absolute opacity-10" style="top: -30px; right: -30px; width: 150px; height: 150px; background: var(--accent-color); border-radius: 50%"></div>
            
            <h2 class="display-5 fw-bold mb-4 position-relative" style="font-family: 'Outfit', sans-serif;">¿Está listo para dar el salto profesional?</h2>
            <p class="lead mb-5 opacity-75 position-relative">Únase a las instituciones que ya están digitalizando su futuro con Colegio-Pro.</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center position-relative">
                <button class="btn btn-accent btn-lg px-5 py-3 fs-5">Ver Demo Interactiva</button>
                <button class="btn btn-outline-light btn-lg px-5 py-3 fs-5" style="border-radius: var(--border-radius)">Contactar a un Consultor</button>
            </div>
        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
    .fw-black { font-weight: 900; }
    .animate-fade-in { animation: fadeIn 1s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .hero-image-wrapper {
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .hero-image-wrapper:hover {
        transform: perspective(1000px) rotateY(-2deg) rotateX(2deg) scale(1.02) !important;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        background: white !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05) !important;
    }
    
    .btn-white:hover {
        background: #f8fafc !important;
    }
</style>
@endsection

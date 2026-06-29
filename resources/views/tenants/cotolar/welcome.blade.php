<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio-Pro' }}</title>
    
    <!-- Favicon dinámico -->
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    
    <!-- Sistema de Temas Dinámico Multi-Tenant -->
    @php
        // Colores por defecto (Estilo Cotolar) si no tiene colores definidos
        $primary = $school->primary_color ?? '#2980B9';
        $secondary = $school->secondary_color ?? '#2E86C1';
        
        // Colores de fondo y texto (Calculamos dependiendo de si es "Prueba" o tiene colores custom)
        // Para simplificar, si es la empresa de prueba (o tiene un primario diferente), cambiamos los fondos.
        $bgLight = '#D6EAF8';
        $bgCard = '#EAF2F8';
        $textDark = '#154360';
        $border = '#A9CCE3';

        // Si es la empresa de prueba (verde esmeralda), le damos un tema gris/blanco limpio
        if($primary == '#10B981') {
            $bgLight = '#f8fafc';
            $bgCard = '#ffffff';
            $textDark = '#1e293b';
            $border = '#e2e8f0';
        }
    @endphp
    <style>
        :root {
            --theme-primary: {{ $primary }};
            --theme-secondary: {{ $secondary }};
            --theme-bg-light: {{ $bgLight }};
            --theme-bg-card: {{ $bgCard }};
            --theme-text-dark: {{ $textDark }};
            --theme-border: {{ $border }};
        }
        
        .hero-overlay {
            background: linear-gradient(135deg, {{ $primary }}dd, {{ $secondary }}dd);
        }
        
        #chatbot-trigger {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        #chatbot-trigger:hover {
            transform: scale(1.15) rotate(-5deg);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2) !important;
        }

        .card-magic {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            box-shadow: 3px 3px 0px rgba(0,0,0,0.1);
            border: 2px solid var(--theme-primary) !important;
        }
        .card-magic:hover {
            transform: translateY(-5px) translateX(-2px);
            box-shadow: 8px 8px 0px var(--theme-secondary);
        }
        .stat-magic {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 2px 2px 0px rgba(0,0,0,0.05);
            border: 2px solid transparent;
        }
        .stat-magic:hover {
            transform: translateY(-3px);
            border: 2px solid var(--theme-primary);
            box-shadow: 5px 5px 0px var(--theme-secondary);
            background-color: white;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg bg-theme-card border-bottom border-theme sticky-top shadow-sm py-3">
        <div class="container-fluid px-4 px-xl-5">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="bg-white rounded-4 shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 100px; height: 100px; padding: 10px; overflow: hidden;">
                    @if(isset($school) && $school->logo)
                        <img src="{{ asset($school->logo) }}" alt="Logo" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                    @else
                        <span class="material-icons text-theme-primary" style="font-size: 3rem;">school</span>
                    @endif
                </div>
                <div>
                    <h1 class="h3 mb-0 fw-bold text-theme-dark" style="letter-spacing: -0.5px;">{{ $school->name ?? 'Institución' }}</h1>
                    <small class="text-theme-secondary d-block fw-semibold" style="font-size: 0.9rem;">Colegio Profesional</small>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="material-icons text-theme-primary">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#quienes-somos">Quiénes Somos</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#autoridades">Autoridades</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="/login" class="btn bg-theme-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2 text-white text-decoration-none">
                        <span class="material-icons" style="font-size: 1.2rem;">login</span> Ingresar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    @php
        $sliderItems = isset($slider) && $slider->items->count() > 0 ? $slider->items : collect([]);
    @endphp
    
    @if($sliderItems->count() > 0)
        <!-- SLIDER ACTIVO: Muestra solo las imágenes en el carrusel (Tapa todo) -->
        <style>
            .hero-slider-section { height: 800px; width: 100%; }
            @media (max-width: 991px) { .hero-slider-section { height: 500px; } }
            @media (max-width: 768px) { .hero-slider-section { height: 400px; } }
            @media (max-width: 576px) { .hero-slider-section { height: 300px; } }
        </style>
        <section class="p-0 position-relative hero-slider-section" style="overflow: hidden; background-color: #000;">
            <div id="heroCarousel" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                    @foreach($sliderItems as $index => $item)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">
                            @php
                                $imgSrc = Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/'.$item->image_url);
                            @endphp
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" class="d-block w-100 h-100">
                                    <img src="{{ $imgSrc }}" class="d-block w-100 h-100" style="object-fit: cover; object-position: center;" alt="{{ $item->title ?? 'Slider' }}">
                                </a>
                            @else
                                <img src="{{ $imgSrc }}" class="d-block w-100 h-100" style="object-fit: cover; object-position: center;" alt="{{ $item->title ?? 'Slider' }}">
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($sliderItems->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.4); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.4); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
                @endif
            </div>
        </section>
    @else
        <!-- SIN SLIDER: Muestra el diseño tradicional azul con texto -->
        <section class="hero-bg position-relative" style="background-color: var(--theme-primary); padding: 120px 0;">
            <div class="hero-overlay" style="position: absolute; top:0; left:0; width:100%; height:100%;"></div>
            <div class="container hero-content text-center position-relative" style="z-index: 3;">
                <p class="text-white fw-bold mb-3 text-uppercase tracking-wider small opacity-75">Órgano oficial de regulación profesional</p>
                <h1 class="display-4 fw-bold text-white mb-4 shadow-sm">{{ $school->name ?? 'Bienvenido' }}</h1>
                <p class="lead text-white mx-auto" style="max-width: 800px; opacity: 0.9;">
                    Organismo encargado de habilitar, regular y fiscalizar el ejercicio ético y legal de la profesión en toda la jurisdicción.
                </p>
            </div>
        </section>
    @endif

    <!-- STATS -->
    <section class="bg-theme-card py-5 border-bottom border-theme">
        <div class="container-fluid px-4 px-xl-5">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-magic" data-bs-toggle="modal" data-bs-target="#modalTerapeutas">
                        <h2 class="display-5 fw-bold text-theme-primary mb-0">+{{ $collegiates->count() ?? 0 }}</h2>
                        <p class="text-theme-secondary small mt-2 fw-semibold mb-0">{{ $school->member_plural ?? 'Profesionales Matriculados' }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-magic" data-bs-toggle="modal" data-bs-target="#modalAnios">
                        <h2 class="display-5 fw-bold text-theme-primary mb-0">{{ \Carbon\Carbon::parse('1990-12-20')->age }}</h2>
                        <p class="text-theme-secondary small mt-2 fw-semibold mb-0">Años de Trayectoria</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-magic" data-bs-toggle="modal" data-bs-target="#modalDepartamentos">
                        <h2 class="display-5 fw-bold text-theme-primary mb-0">18</h2>
                        <p class="text-theme-secondary small mt-2 fw-semibold mb-0">Departamentos de La Rioja</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-magic" data-bs-toggle="modal" data-bs-target="#modalConvenios">
                        <h2 class="display-5 fw-bold text-theme-primary mb-0">{{ isset($agreements) ? $agreements->count() : 0 }}</h2>
                        <p class="text-theme-secondary small mt-2 fw-semibold mb-0">Convenios Vigentes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="container-fluid px-4 px-xl-5 py-5">
        
        <!-- INSTITUCIONAL -->
        <div id="quienes-somos" class="row align-items-center mb-5 pb-5 border-bottom border-theme pt-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="material-icons text-theme-primary fs-1">info</span>
                    <h2 class="h2 fw-bold text-theme-dark mb-0">¿Quiénes somos?</h2>
                </div>
                <div class="bg-theme-card rounded-4 p-4 shadow-sm border border-theme">
                    <p class="text-theme-dark mb-0" style="line-height: 1.8;">
                        El <strong>{{ $school->name }}</strong> es la institución que agrupa y regula a todos los profesionales que ejercen en nuestra jurisdicción. 
                        Somos el organismo encargado de controlar la matrícula profesional, hacer cumplir el código de ética y defender los derechos de los colegiados, fomentando la excelencia y la actualización continua.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                @php
                    $aboutImage = $primary == '#10B981' 
                                    ? 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' // Imagen corporativa
                                    : 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'; // Imagen médicos
                @endphp
                <img src="{{ $aboutImage }}" alt="Institucional" class="img-fluid rounded-4 shadow-lg border border-theme" style="max-height: 350px; width: 100%; object-fit: cover;">
            </div>
        </div>

        <!-- SERVICIOS CARDS -->
        <div class="row g-4 mb-5 pb-5 border-bottom border-theme">
            <div class="col-md-4">
                <div class="card card-magic h-100 rounded-4 p-4 text-center bg-theme-card" data-bs-toggle="modal" data-bs-target="#modalMatricula">
                    <div class="mb-3">
                        <i class="bi bi-shield-check text-theme-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Matrícula Habilitante</h4>
                    <p class="text-theme-secondary small mb-0">Verificá la habilitación legal de cualquier profesional en nuestra jurisdicción.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-magic h-100 rounded-4 p-4 text-center bg-theme-card" data-bs-toggle="modal" data-bs-target="#modalTramites">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text text-theme-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Trámites y Requisitos</h4>
                    <p class="text-theme-secondary small mb-0">Toda la información necesaria para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-magic h-100 rounded-4 p-4 text-center bg-theme-card" data-bs-toggle="modal" data-bs-target="#modalAtencion">
                    <div class="mb-3">
                        <i class="bi bi-headset text-theme-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Atención al Colegiado</h4>
                    <p class="text-theme-secondary small mb-0">Consultas, trámites presenciales, certificaciones y asistencia administrativa.</p>
                </div>
            </div>
        </div>

        <!-- NOTICIAS Y NOVEDADES -->
        <div id="novedades" class="mb-5 pb-5 border-bottom border-theme pt-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="h2 fw-bold text-theme-dark mb-2">Noticias y Novedades</h2>
                    <p class="text-theme-secondary mb-0">Últimas actualizaciones e información de interés para colegiados</p>
                </div>
                <a href="{{ route('news.index') }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">Ver todas en nueva pestaña <span class="material-icons align-middle ms-1" style="font-size: 1.1rem;">open_in_new</span></a>
            </div>
            
            @if(isset($latestNews) && $latestNews->count() > 0)
            <style>
                .card-prestige {
                    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                }
                .card-prestige:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.5) !important;
                }
                .ls-1 { letter-spacing: 1px; }
            </style>
            <div class="row g-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none text-start">
                        <div class="card card-prestige h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: all 0.3s ease; text-align: left;">
                            @if($news->featured_image_url)
                                <img src="{{ str_starts_with($news->featured_image_url, 'http') ? $news->featured_image_url : asset($news->featured_image_url) }}" class="card-img-top" alt="{{ $news->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-newspaper display-4 text-white opacity-50"></i>
                                </div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-2 text-muted small fw-bold">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $news->published_at->format('d M, Y') }}
                                </div>
                                <h5 class="card-title fw-bold text-dark">{{ $news->title }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ $news->excerpt ?? Str::limit(strip_tags($news->content), 100) }}</p>
                                <div class="mt-3 text-primary fw-bold small text-uppercase ls-1">Leer más <i class="bi bi-arrow-right"></i></div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center p-5 bg-theme-card rounded-4 border border-theme shadow-sm">
                <span class="material-icons text-muted fs-1 mb-3">newspaper</span>
                <p class="text-muted mb-0">Aún no se han publicado noticias recientes.</p>
            </div>
            @endif
        </div>

        <!-- COMISION DIRECTIVA (ORG CHART) -->
        <div id="autoridades" class="mb-5 pb-4 pt-5">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold text-theme-dark mb-2">Comisión Directiva</h2>
                <p class="text-theme-secondary">Autoridades que guían nuestra institución</p>
            </div>

            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <div class="mb-5">
                        <h4 class="text-center text-theme-primary fw-bold mb-4">{{ $department }}</h4>
                        @php
                            $president = null;
                            $others = [];
                            foreach($members as $m) {
                                if(!$president && (stripos($m->role, 'president') !== false || stripos($m->role, 'titular') !== false || stripos($m->role, 'director') !== false)) {
                                    $president = $m;
                                } else {
                                    $others[] = $m;
                                }
                            }
                            if(!$president && count($others) > 0) {
                                $president = array_shift($others);
                            }
                        @endphp

                        <div class="org-chart-wrapper d-flex flex-column align-items-center">
                            @if($president)
                            <div class="org-chart-node shadow-hover-up" style="width: 280px;">
                                @php
                                    $presImageUrl = $president->collegiate && $president->collegiate->avatar_url ? $president->collegiate->avatar_url : $president->image_path;
                                    $presName = $president->collegiate ? $president->collegiate->first_name . ' ' . $president->collegiate->last_name : $president->name;
                                @endphp
                                <img src="{{ $presImageUrl }}" alt="{{ $presName }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($presName) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                                <h5 class="fw-bold text-theme-dark mb-1">{{ $presName }}</h5>
                                <p class="text-theme-primary fw-bold small mb-0">{{ $president->role }}</p>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="org-chart-connector"></div>
                            @if(count($others) > 1)
                            <div class="org-line-horizontal" style="max-width: {{ min((count($others)-1) * 290, 800) }}px;"></div>
                            @endif

                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                                @foreach($others as $m)
                                <div class="org-chart-node shadow-hover-up" style="width: 250px;">
                                    @php
                                        $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                                        $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                    @endphp
                                    <img src="{{ $mImageUrl }}" alt="{{ $mName }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                                    <h5 class="fw-bold text-theme-dark mb-1">{{ $mName }}</h5>
                                    <p class="text-theme-primary fw-bold small mb-0">{{ $m->role }}</p>
                                    @if($m->is_substitute)
                                        <span class="badge bg-secondary mt-2">Suplente</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-5 bg-theme-card rounded-4 border border-theme shadow-sm">
                    <span class="material-icons text-muted fs-1 mb-3">groups</span>
                    <p class="text-muted mb-0">La comisión directiva aún no ha sido publicada.</p>
                </div>
            @endif
        </div>

        <!-- CONTACTO Y UBICACIÓN -->
        <div id="contacto" class="mb-5 pt-5">
            <div class="mb-4 text-center">
                <h2 class="h2 fw-bold text-theme-dark mb-2">Dónde Estamos</h2>
                <p class="text-theme-secondary">Encuéntranos en nuestras oficinas o contáctanos por nuestros canales oficiales.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="bg-theme-card rounded-4 p-4 shadow-sm border border-theme h-100 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">location_on</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Dirección</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->address ?? 'Calle Falsa 123, Ciudad' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">phone</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Teléfono</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->phone ?? '(123) 456-7890' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">email</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Correo Electrónico</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->email ?? 'contacto@colegio.com' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-2 pt-4 border-top border-theme">
                            <h6 class="fw-bold text-theme-dark mb-3">Síguenos en Redes</h6>
                            <div class="d-flex gap-2">
                                @php
                                    $fb = !empty($school->facebook_url) ? $school->facebook_url : '';
                                    $ig = !empty($school->instagram_url) ? $school->instagram_url : '';
                                    $tw = !empty($school->twitter_url) ? $school->twitter_url : '';
                                @endphp
                                <a {!! $fb ? 'href="'.$fb.'" target="_blank"' : 'href="javascript:void(0)"' !!} class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-facebook fs-6"></i></a>
                                <a {!! $ig ? 'href="'.$ig.'" target="_blank"' : 'href="javascript:void(0)"' !!} class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-instagram fs-6"></i></a>
                                <a {!! $tw ? 'href="'.$tw.'" target="_blank"' : 'href="javascript:void(0)"' !!} class="btn btn-outline-info btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="bi bi-twitter-x fs-6"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    @php
                        $mapQuery = null;
                        if(isset($school) && $school->latitude && $school->longitude) {
                            $mapQuery = $school->latitude . ',' . $school->longitude;
                        } elseif (isset($school) && $school->plus_code) {
                            $mapQuery = $school->plus_code . ' ' . $school->address;
                        } elseif (isset($school) && $school->address) {
                            $mapQuery = $school->address;
                        }
                    @endphp

                    @if(isset($school) && $school->map_embed_code)
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 400px; border: 1px solid var(--theme-border);">
                            {!! $school->map_embed_code !!}
                        </div>
                    @elseif($mapQuery)
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 400px; border: 1px solid var(--theme-border);">
                            <iframe width="100%" height="100%" style="border:0; min-height: 400px;" loading="lazy" allowfullscreen 
                                src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                    @else
                        <div class="rounded-4 bg-theme-card d-flex align-items-center justify-content-center h-100 shadow-sm border border-theme" style="min-height: 400px;">
                            <div class="text-center">
                                <span class="material-icons text-theme-secondary mb-2" style="font-size: 3rem;">map</span>
                                <p class="text-theme-secondary mb-0">Mapa no configurado</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-theme-card py-5 mt-auto border-top border-theme">
        <div class="container text-center text-theme-dark">
            <div class="mb-4">
                <h4 class="fw-bold">{{ $school->name }}</h4>
                <p class="text-theme-secondary small mb-0">Órgano oficial de regulación profesional</p>
            </div>
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="#" class="text-theme-primary text-decoration-none shadow-hover-up"><span class="material-icons">facebook</span></a>
                <a href="#" class="text-theme-primary text-decoration-none shadow-hover-up"><span class="material-icons">email</span></a>
                <a href="#" class="text-theme-primary text-decoration-none shadow-hover-up"><span class="material-icons">location_on</span></a>
            </div>
            <p class="text-theme-dark small mb-0">&copy; {{ date('Y') }} Graficar Software de Mario Rojas. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chatbot Widget -->
    <div id="chatbot-widget" class="position-fixed" style="bottom: 120px; right: 25px; z-index: 1050; width: 400px; height: 550px; display: none; resize: both; overflow: hidden; min-width: 300px; min-height: 400px; max-width: 90vw; max-height: 90vh; background: transparent;">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; overflow: hidden; display: flex; flex-direction: column;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3" id="chatbot-header" style="cursor: move;">
                <div class="fw-bold d-flex align-items-center">
                    <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" class="me-2 shadow-sm" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; pointer-events: none;">
                    Asistente Virtual
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
            </div>
            <div class="card-body bg-light flex-grow-1" id="chatbot-messages" style="overflow-y: auto;">
                <div class="d-flex mb-3">
                    <div class="bg-white text-dark p-3 rounded-4 shadow-sm" style="max-width: 85%;">
                        Hola 👋 Soy el asistente virtual del {{ $school->name ?? 'Colegio' }}. ¿En qué te puedo ayudar hoy?
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <form id="chatbot-form" class="d-flex gap-2" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatbot-input" class="form-control rounded-pill bg-light border-0 px-3" placeholder="Escribe tu consulta..." required>
                    <button type="submit" class="btn btn-primary rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <button id="chatbot-trigger" class="btn btn-light border border-2 border-primary rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center p-0" style="bottom: 25px; right: 25px; z-index: 1040; width: 95px; height: 95px; background-color: white !important; overflow: hidden;" onclick="toggleChatbot()">
        <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" style="width: 100%; height: 100%; object-fit: cover;">
    </button>

    <!-- Modales Estadisticas -->
    <!-- Modal 1: Terapeutas -->
    <div class="modal fade" id="modalTerapeutas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-theme-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-theme-dark"><i class="bi bi-people me-2 text-theme-primary"></i> Padrón de Terapeutas Ocupacionales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="input-group mb-4 shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 py-2" id="searchTerapeutas" placeholder="Buscar por nombre, matrícula o DNI...">
                    </div>
                    <div class="list-group list-group-flush rounded-3 border shadow-sm" id="listTerapeutas">
                        @if(isset($collegiates))
                            @foreach($collegiates as $colegiado)
                                <div class="list-group-item list-group-item-action p-3 terapeuta-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold t-name">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                            <div class="small text-muted">
                                                <span class="me-3 t-mat"><i class="bi bi-card-text me-1"></i> MP: {{ $colegiado->registration_number }}</span>
                                                <span class="t-dni"><i class="bi bi-person-vcard me-1"></i> DNI: {{ $colegiado->dni }}</span>
                                            </div>
                                        </div>
                                        @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                            <span class="badge bg-success rounded-pill">Activo</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $colegiado->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Años Trayectoria -->
    <div class="modal fade" id="modalAnios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <div class="display-1 text-theme-primary mb-3"><i class="bi bi-award"></i></div>
                    <h3 class="fw-bold text-theme-dark mb-3">{{ \Carbon\Carbon::parse('1990-12-20')->age }} Años de Trayectoria</h3>
                    <p class="text-muted">Desde nuestra fundación el <strong>20 de Diciembre de 1990</strong>, hemos trabajado incansablemente para regular y jerarquizar la profesión en toda la provincia de La Rioja.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Departamentos -->
    <div class="modal fade" id="modalDepartamentos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-theme-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-theme-dark"><i class="bi bi-geo-alt me-2 text-theme-primary"></i> Delegaciones por Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Conocé a los profesionales matriculados en cada uno de los 18 departamentos de la provincia de La Rioja.</p>
                    <div class="accordion accordion-flush border rounded-4 overflow-hidden" id="accordionDeptos">
                        @php
                            $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martín', 'Independencia', 'Rosario Vera Peñaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela', 'Sanagasta'];
                        @endphp
                        @foreach($departamentos as $index => $depto)
                            @php
                                $deptCollegiates = isset($collegiates) ? $collegiates->where('city', $depto) : collect();
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed fw-bold text-theme-dark" type="button" data-bs-toggle="collapse" data-bs-target="#depto{{ $index }}" aria-expanded="false" aria-controls="depto{{ $index }}">
                                        {{ $depto }}
                                        <span class="badge bg-theme-primary ms-2 rounded-pill">{{ $deptCollegiates->count() }}</span>
                                    </button>
                                </h2>
                                <div id="depto{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}">
                                    <div class="accordion-body bg-light p-0">
                                        @if($deptCollegiates->count() > 0)
                                            <div class="list-group list-group-flush">
                                                @foreach($deptCollegiates as $colegiado)
                                                    <div class="list-group-item bg-transparent p-3 border-bottom">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                                                <div class="small text-muted">
                                                                    <span class="me-3"><i class="bi bi-card-text me-1"></i> MP: {{ $colegiado->registration_number }}</span>
                                                                    <span><i class="bi bi-person-vcard me-1"></i> DNI: {{ $colegiado->dni }}</span>
                                                                </div>
                                                            </div>
                                                            @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                                                <span class="badge bg-success rounded-pill">Activo</span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill">{{ $colegiado->status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4 small">
                                                <i class="bi bi-info-circle fs-4 d-block mb-2 text-theme-secondary"></i> 
                                                No hay profesionales registrados en este departamento.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 4: Convenios -->
    <div class="modal fade" id="modalConvenios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-theme-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-theme-dark"><i class="bi bi-briefcase me-2 text-theme-primary"></i> Convenios Comerciales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    @if(isset($agreements) && $agreements->count() > 0)
                        <div class="row g-3">
                            @foreach($agreements as $agreement)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm rounded-4">
                                        <div class="card-body p-4 d-flex flex-column text-center">
                                            <div class="mb-3 mx-auto">
                                                @if($agreement->logo_url)
                                                    <img src="{{ asset($agreement->logo_url) }}" alt="{{ $agreement->name }}" class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                                @else
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                                        <i class="bi bi-shop fs-1 text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="fw-bold mb-1">{{ $agreement->name }}</h5>
                                            @if($agreement->discount_percentage)
                                                <span class="badge bg-success rounded-pill mx-auto mb-3 px-3 py-2 fs-6">{{ $agreement->discount_percentage }}</span>
                                            @endif
                                            <p class="text-muted small mt-auto mb-0">{{ $agreement->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="display-1 text-muted mb-3"><i class="bi bi-inbox"></i></div>
                            <h5 class="fw-bold text-muted">Aún no hay convenios activos</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales Servicios -->
    <div class="modal fade" id="modalMatricula" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-theme-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-theme-dark"><i class="bi bi-shield-check text-theme-primary me-2"></i> Matrícula Habilitante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-4">
                    <p class="text-muted small mb-4">Validá la habilitación legal de cualquier profesional activo en nuestra jurisdicción ingresando su DNI, Matrícula o Nombre completo.</p>
                    <form id="formValidarMatricula" onsubmit="event.preventDefault(); validarMatricula();">
                        <div class="input-group mb-3 shadow-sm rounded-pill overflow-hidden border">
                            <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="inputMatriculaSearch" class="form-control border-0 shadow-none" placeholder="Buscar por DNI, Matrícula o Nombre..." required>
                            <button class="btn bg-theme-primary text-white fw-bold px-4 border-0" type="submit" id="btnValidar">Validar</button>
                        </div>
                    </form>
                    
                    <div id="resultadoMatricula" class="mt-4 d-none">
                        <!-- Aquí va el resultado -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTramites" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text text-theme-primary me-2"></i> Trámites y Requisitos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Fotocopia del DNI</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Título Original legalizado</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Certificado de Antecedentes</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> 2 Fotos Carnet</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAtencion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-headset text-theme-primary me-2"></i> Atención al Colegiado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Nuestro equipo está disponible de Lunes a Viernes de 8:00 a 13:00 hs para asesorarte en:</p>
                    <ul>
                        <li>Renovación de matrícula</li>
                        <li>Certificados de Ética</li>
                        <li>Consulta de estado de cuenta</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BUSCADOR EN VIVO -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTerapeutas');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const items = document.querySelectorAll('.terapeuta-item');
                    
                    items.forEach(item => {
                        const name = item.querySelector('.t-name').textContent.toLowerCase();
                        const mat = item.querySelector('.t-mat').textContent.toLowerCase();
                        const dni = item.querySelector('.t-dni').textContent.toLowerCase();
                        
                        if(name.includes(value) || mat.includes(value) || dni.includes(value)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>

    <script>
        const chatbotWidget = document.getElementById('chatbot-widget');

        function toggleChatbot() {
            if (chatbotWidget.style.display === 'none' || chatbotWidget.style.display === '') {
                chatbotWidget.style.display = 'block';
            } else {
                chatbotWidget.style.display = 'none';
            }
        }
        
        // Draggable logic
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;

        const header = document.getElementById("chatbot-header");

        header.addEventListener("mousedown", dragStart);
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("mousemove", drag);

        function dragStart(e) {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            if (e.target === header || e.target.parentNode === header) {
                isDragging = true;
            }
        }

        function dragEnd(e) {
            initialX = currentX;
            initialY = currentY;
            isDragging = false;
        }

        function drag(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;
                setTranslate(currentX, currentY, chatbotWidget);
            }
        }

        function setTranslate(xPos, yPos, el) {
            el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatbot-messages');
            
            // Append user message
            messagesDiv.innerHTML += `
                <div class="d-flex mb-3 justify-content-end">
                    <div class="bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 85%;">${message}</div>
                </div>
            `;
            
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Fetch response
            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: message, school_id: '{{ $school->id ?? 1 }}' })
                });

                const data = await response.json();
                
                // Append bot message
                messagesDiv.innerHTML += `
                    <div class="d-flex mb-3">
                        <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 85%;">${data.answer}</div>
                    </div>
                `;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
    
    <!-- Bootstrap JS for Modals -->
    <!-- Scripts para validación de matrícula -->
    <script>
        function validarMatricula() {
            const query = document.getElementById('inputMatriculaSearch').value;
            const btn = document.getElementById('btnValidar');
            const resContainer = document.getElementById('resultadoMatricula');
            
            if(!query) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            resContainer.classList.add('d-none');

            fetch('{{ route("public.validate.matricula") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: query })
            })
            .then(response => response.json())
            .then(data => {
                resContainer.classList.remove('d-none');
                btn.disabled = false;
                btn.innerHTML = 'Validar';

                if (data.success) {
                    const col = data.collegiate;
                    const badgeClass = col.is_active ? 'bg-success' : 'bg-danger';
                    const iconClass = col.is_active ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger';
                    const msg = col.is_active ? 'El profesional se encuentra habilitado para ejercer.' : 'El profesional NO se encuentra habilitado en este momento.';

                    resContainer.innerHTML = `
                        <div class="card border-0 shadow-sm rounded-4" style="background-color: #f8f9fa; border-left: 5px solid ${col.is_active ? '#198754' : '#dc3545'} !important;">
                            <div class="card-body p-4 text-center">
                                <i class="bi ${iconClass} mb-2" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold text-dark mb-1">${col.name}</h5>
                                <p class="text-muted mb-3 small">DNI: ${col.document} | Matrícula: ${col.registration}</p>
                                <span class="badge ${badgeClass} px-3 py-2 fs-6 mb-3 rounded-pill">${col.status}</span>
                                <p class="mb-0 small fw-bold text-dark">${msg}</p>
                            </div>
                        </div>
                    `;
                } else {
                    resContainer.innerHTML = `
                        <div class="alert alert-warning border-0 shadow-sm rounded-4 text-center p-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning mb-2" style="font-size: 2.5rem;"></i>
                            <h6 class="fw-bold mt-2">Búsqueda sin resultados</h6>
                            <p class="mb-0 small text-muted">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = 'Validar';
                resContainer.classList.remove('d-none');
                resContainer.innerHTML = '<div class="alert alert-danger rounded-4 small">Ocurrió un error al consultar el servidor. Intente más tarde.</div>';
            });
        }
    </script>
</body>
</html>

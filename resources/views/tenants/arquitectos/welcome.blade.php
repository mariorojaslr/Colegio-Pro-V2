<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio de Arquitectos' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Fuente moderna y geométrica para arquitectos -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;600;800&family=Oswald:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1a1a1a;
            --accent: #ff6b00; /* Naranja fuerte/constructivo */
            --light: #f8f9fa;
            --dark: #0f0f0f;
            --gray: #7f8c8d;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--primary);
            background-color: var(--light);
        }

        h1, h2, h3, h4, h5, h6, .oswald {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }

        /* NAVBAR */
        .navbar-arq {
            background-color: var(--dark);
            border-bottom: 3px solid var(--accent);
            padding: 0.5rem 0;
        }
        .navbar-arq .navbar-brand {
            color: #fff !important;
            letter-spacing: 2px;
            font-weight: 700;
        }
        .btn-arq-nav {
            border: 1px solid var(--accent);
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 0;
            padding: 10px 20px;
            transition: 0.3s;
        }
        .btn-arq-nav:hover {
            background-color: var(--accent);
            color: var(--dark);
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-arq {
            height: 100vh;
            /* Background handled inline or by carousel */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            position: relative;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
        }
        .hero-arq h1 {
            font-size: 5rem;
            color: #fff;
            letter-spacing: 4px;
            line-height: 1;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        }
        .hero-arq p {
            font-size: 1.3rem;
            color: #ffffff;
            font-weight: 500;
            letter-spacing: 1px;
            max-width: 600px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.9);
        }

        /* SECTIONS */
        .section-title {
            font-size: 3rem;
            color: var(--dark);
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 80%;
            background-color: var(--accent);
        }

        /* CARDS & NEWS */
        .card-arq {
            border: none;
            border-radius: 0;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: 0.4s ease;
            height: 100%;
        }
        .card-arq:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .card-arq-img {
            height: 250px;
            object-fit: cover;
            filter: grayscale(100%);
            transition: 0.4s ease;
        }
        .card-arq:hover .card-arq-img {
            filter: grayscale(0%);
        }
        .card-arq-body {
            padding: 2rem;
        }
        
        /* ORG CHART */
        .node-arq {
            background: #fff;
            border-left: 4px solid var(--dark);
            padding: 1.5rem;
            width: 260px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
        }
        .node-arq.president {
            border-left-color: var(--accent);
        }
        .node-arq img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            position: absolute;
            top: -30px;
            right: 20px;
            border: 3px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* FOOTER */
        .footer-arq {
            background-color: var(--dark);
            color: #fff;
            padding: 4rem 0 2rem;
            border-top: 5px solid var(--accent);
        }
    
        #chatbot-trigger {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        #chatbot-trigger:hover {
            transform: scale(1.15) rotate(-5deg);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2) !important;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-arq fixed-top">
        <div class="container-fluid px-4 px-xl-5 d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 180px; max-height: 250px;">
                @else
                    <span class="material-icons text-white">architecture</span>
                @endif
                <span>{{ $school->name ?? 'CAPLaR' }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#arqNav">
                <span class="material-icons text-white">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="arqNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-white oswald" href="#quienes-somos" style="letter-spacing: 1px;">QUIÉNES SOMOS</a></li>
                    <li class="nav-item"><a class="nav-link text-white oswald" href="#novedades" style="letter-spacing: 1px;">NOVEDADES</a></li>
                    <li class="nav-item"><a class="nav-link text-white oswald" href="#autoridades" style="letter-spacing: 1px;">AUTORIDADES</a></li>
                    <li class="nav-item"><a class="nav-link text-white oswald" href="#contacto" style="letter-spacing: 1px;">CONTACTO</a></li>
                </ul>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-arq-nav">Plataforma Colegiados</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    @php
        $sliderItems = isset($slider) && $slider->items->count() > 0 ? $slider->items : collect([]);
    @endphp

    @if($sliderItems->count() > 0)
        <!-- SLIDER ACTIVO: Muestra solo las imágenes (Tapa todo) -->
        <style>
            .hero-slider-section { height: 100vh; }
            @media (max-width: 991px) { .hero-slider-section { height: 400px; } }
            @media (max-width: 768px) { .hero-slider-section { height: 280px; } }
            @media (max-width: 576px) { .hero-slider-section { height: 220px; } }
        </style>
        <section class="p-0 position-relative hero-slider-section" style="overflow: hidden; background-color: #000; clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);">
            <div id="heroCarouselArq" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
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
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarouselArq" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarouselArq" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
                @endif
            </div>
        </section>
    @else
        <!-- SIN SLIDER: Muestra el diseño tradicional azul con texto -->
        <section class="hero-arq" style="background-color: var(--dark);">
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)); z-index: 2;"></div>
            <div class="container-fluid px-4 px-xl-5 position-relative h-100 d-flex align-items-center" style="z-index: 3;">
                <div class="row">
                    <div class="col-lg-8">
                        <h1>DISEÑO,<br><span style="color: var(--accent);">VANGUARDIA</span><br>& ÉTICA.</h1>
                        <p>Órgano oficial de regulación profesional. Defendiendo las incumbencias y promoviendo la excelencia arquitectónica en nuestra jurisdicción.</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="container-fluid px-4 px-xl-5 py-5" style="margin-top: -50px; position: relative; z-index: 10;">
        
        <!-- STATS & SERVICES -->
        <div class="row g-4 mb-5 pb-5 border-bottom pt-4 bg-white p-4 shadow-sm">
            <div class="col-12 mb-4 text-center">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">+{{ $school->collegiates()->count() ?? 0 }}</h2>
                        <p class="text-muted small mt-2 fw-bold">Profesionales Matriculados</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">{{ \Carbon\Carbon::parse('1990-12-20')->age }}</h2>
                        <p class="text-muted small mt-2 fw-bold">Años de Trayectoria</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">18</h2>
                        <p class="text-muted small mt-2 fw-bold">Departamentos de La Rioja</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">1</h2>
                        <p class="text-muted small mt-2 fw-bold">Convenios Vigentes</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--accent);">badge</span>
                    <h4 class="oswald mb-2" style="font-size: 1.2rem;">Matrícula Habilitante</h4>
                    <p class="text-muted small mb-0">Verificá la habilitación legal de cualquier profesional.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--accent);">assignment</span>
                    <h4 class="oswald mb-2" style="font-size: 1.2rem;">Trámites</h4>
                    <p class="text-muted small mb-0">Información para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--accent);">support_agent</span>
                    <h4 class="oswald mb-2" style="font-size: 1.2rem;">Atención</h4>
                    <p class="text-muted small mb-0">Consultas presenciales y asistencia administrativa.</p>
                </div>
            </div>
        </div>

        <!-- INSTITUCIONAL -->
        <div id="quienes-somos" class="row mb-5 pb-5 border-bottom pt-5">
            <div class="col-lg-5 mb-4">
                <h2 class="section-title">Quiénes Somos</h2>
                <p style="line-height: 1.8; color: var(--gray);">
                    El <strong>{{ $school->name }}</strong> agrupa, regula y defiende a los profesionales del diseño y la construcción.
                    Buscamos jerarquizar la profesión, asegurar el cumplimiento ético y proveer herramientas de vanguardia para nuestros matriculados.
                </p>
            </div>
            <div class="col-lg-7">
                @php
                    $aboutImage = isset($slider) && $slider->items->count() > 1 ? $slider->items[1]->image_url : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80';
                @endphp
                <img src="{{ $aboutImage }}" alt="Arquitectura" class="img-fluid" style="width: 100%; height: 350px; object-fit: cover; filter: grayscale(50%);">
            </div>
        </div>

        <!-- NOTICIAS -->
        <div id="novedades" class="mb-5 pb-5 border-bottom pt-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h2 class="section-title mb-0">Novedades</h2>
                <a href="{{ route('news.index') }}" class="btn btn-outline-dark rounded-0 px-4 py-2 oswald">Ver Archivo</a>
            </div>
            
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="card-arq">
                        @if($news->image_path)
                            <img src="{{ asset($news->image_path) }}" class="card-img-top card-arq-img" alt="{{ $news->title }}">
                        @else
                            <div class="card-arq-img d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #333 100%); position: relative; overflow: hidden;">
                                <div style="position: absolute; width: 150%; height: 150%; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 60%); top: -25%; left: -25%;"></div>
                                @if(isset($school) && $school->logo)
                                    <img src="{{ asset($school->logo) }}" alt="Logo" style="max-height: 100px; opacity: 0.2; filter: grayscale(100%) brightness(200%); position: relative; z-index: 1;">
                                @else
                                    <span class="material-icons text-white" style="font-size: 5rem; opacity: 0.1; position: relative; z-index: 1;">newspaper</span>
                                @endif
                            </div>
                        @endif
                        <div class="card-arq-body">
                            <small class="text-muted fw-bold">{{ $news->published_at->format('d M, Y') }}</small>
                            <h4 class="mt-2 mb-3" style="font-size: 1.2rem; line-height: 1.4;">{{ $news->title }}</h4>
                            <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none" style="color: var(--accent); font-weight: 600;">LEER MÁS &#8594;</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-5 text-center bg-light">
                <span class="material-icons text-muted fs-1">engineering</span>
                <p class="text-muted mt-2 mb-0">No hay novedades publicadas en este momento.</p>
            </div>
            @endif
        </div>

        <!-- AUTORIDADES -->
        <div id="autoridades" class="mb-5 pb-5 border-bottom pt-5">
            <h2 class="section-title text-center d-block mb-5">Autoridades</h2>
            
            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <div class="mb-5">
                        <h4 class="text-center oswald mb-4" style="color: var(--gray); letter-spacing: 2px;">// {{ $department }}</h4>
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

                        <div class="d-flex flex-column align-items-center">
                            @if($president)
                            <div class="node-arq president mb-4">
                                @php
                                    $presImageUrl = $president->collegiate && $president->collegiate->avatar_url ? $president->collegiate->avatar_url : $president->image_path;
                                    $presName = $president->collegiate ? $president->collegiate->first_name . ' ' . $president->collegiate->last_name : $president->name;
                                @endphp
                                <img src="{{ $presImageUrl }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($presName) }}&background=000&color=fff'">
                                <h6 class="oswald mb-1">{{ $presName }}</h6>
                                <small style="color: var(--accent); font-weight: 600;">{{ $president->role }}</small>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="d-flex flex-wrap justify-content-center gap-4">
                                @foreach($others as $m)
                                <div class="node-arq">
                                    @php
                                        $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                        $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                                    @endphp
                                    <img src="{{ $mImageUrl }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background=000&color=fff'" style="width: 40px; height: 40px; top: -20px; right: 10px;">
                                    <h6 class="oswald mb-1">{{ $mName }}</h6>
                                    <small class="text-muted fw-bold">{{ $m->role }}</small>
                                    @if($m->is_substitute) <br><small class="text-danger">Suplente</small> @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-4">
                    <p class="text-muted">La comisión directiva aún no ha sido publicada.</p>
                </div>
            @endif
        </div>

        <!-- CONTACTO -->
        <div id="contacto" class="row g-5 mb-5 pb-4 pt-5">
            <div class="col-lg-5">
                <h2 class="section-title mb-4">Contacto</h2>
                <ul class="list-unstyled">
                    <li class="d-flex mb-4 align-items-center">
                        <span class="material-icons me-3 fs-3" style="color: var(--accent);">location_on</span>
                        <div>
                            <strong class="d-block oswald">Dirección</strong>
                            <span class="text-muted">{{ $school->address ?? 'San Martin 123' }}</span>
                        </div>
                    </li>
                    <li class="d-flex mb-4 align-items-center">
                        <span class="material-icons me-3 fs-3" style="color: var(--accent);">phone</span>
                        <div>
                            <strong class="d-block oswald">Teléfono</strong>
                            <span class="text-muted">{{ $school->phone ?? '(011) 456-7890' }}</span>
                        </div>
                    </li>
                    <li class="d-flex mb-4 align-items-center">
                        <span class="material-icons me-3 fs-3" style="color: var(--accent);">email</span>
                        <div>
                            <strong class="d-block oswald">Mail</strong>
                            <span class="text-muted">{{ $school->email ?? 'info@arquitectos.com' }}</span>
                        </div>
                    </li>
                </ul>
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

                @if($school->map_embed_code)
                    <div style="filter: grayscale(100%) contrast(120%); border: 1px solid #ddd; height: 100%; min-height: 300px;">
                        {!! $school->map_embed_code !!}
                    </div>
                @elseif($mapQuery)
                    <div style="filter: grayscale(100%) contrast(120%); border: 1px solid #ddd; height: 100%; min-height: 300px;">
                        <iframe width="100%" height="100%" style="border:0; min-height: 300px;" loading="lazy" allowfullscreen 
                            src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                        </iframe>
                    </div>
                @else
                    <div class="bg-dark text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                        <span class="material-icons fs-1">map</span>
                    </div>
                @endif
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer-arq">
        <div class="container-fluid px-4 px-xl-5 text-center">
            <h4 class="oswald mb-3">{{ $school->name }}</h4>
            <p class="text-white-50 mb-4 small" style="letter-spacing: 1px;">DISEÑO • ÉTICA • PROFESIONALISMO</p>
            <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} Desarrollado por Gente Piola.</p>
        </div>
    </footer>


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
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio de Trabajo Social' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Fuentes modernas y profesionales -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ts-primary: #4f46e5; /* Índigo Moderno */
            --ts-secondary: #0ea5e9; /* Celeste Eléctrico */
            --ts-accent: #10b981; /* Verde Mentas / Empatía */
            --ts-light: #f8fafc;
            --ts-dark: #0f172a;
            --ts-grad: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ts-dark);
            background: var(--ts-grad);
        }

        h1, h2, h3, h4, h5, h6, .playfair {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* NAVBAR */
        .navbar-ts {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(79, 70, 229, 0.1);
        }
        .navbar-ts .navbar-brand {
            color: var(--ts-dark) !important;
            font-weight: 800;
        }
        .navbar-ts .navbar-brand span {
            background: linear-gradient(90deg, var(--ts-primary), var(--ts-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .btn-ts-nav {
            background: linear-gradient(90deg, var(--ts-primary), var(--ts-secondary));
            color: #fff !important;
            border-radius: 50px;
            padding: 10px 28px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-ts-nav:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.45);
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1529156069898-49953eb1f5bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-ts {
            padding: 200px 0 120px;
            background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(240, 253, 244, 0.95) 100%), url('{{ $bgImage }}');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .hero-ts h1 {
            font-size: 4.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }
        .hero-ts p {
            font-size: 1.3rem;
            color: #475569;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        /* SECTIONS */
        .section-title {
            color: #1e1b4b;
            margin-bottom: 3.5rem;
            text-align: center;
            font-size: 2.8rem;
            font-weight: 800;
        }
        .section-title span {
            background: linear-gradient(90deg, var(--ts-primary), var(--ts-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 5px;
            background: linear-gradient(90deg, var(--ts-primary), var(--ts-accent));
            margin: 15px auto 0;
            border-radius: 10px;
        }

        /* CARDS GLASSMORPHIC */
        .card-ts {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            padding: 2.5rem;
            height: 100%;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .card-ts:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.08) !important;
            border-color: rgba(79, 70, 229, 0.2);
            background: rgba(255, 255, 255, 0.9);
        }
        .card-ts img {
            border-radius: 18px;
            width: 100%;
            height: 220px;
            object-fit: cover;
            margin-bottom: 1.8rem;
        }

        /* ORG CHART */
        .org-ts-node {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            width: 260px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-top: 6px solid var(--ts-primary);
            transition: all 0.3s ease;
        }
        .org-ts-node:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.1);
        }
        .org-ts-node img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.2rem;
            border: 4px solid #fff;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.15);
        }
        
        .footer-ts {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #cbd5e1;
            padding: 5rem 0 3rem;
            text-align: center;
        }
    
        #chatbot-trigger {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        #chatbot-trigger:hover {
            transform: scale(1.15) rotate(-5deg);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2) !important;
        }

        .stat-ts-magic {
            transition: all 0.3s ease;
            padding: 15px;
            border-radius: 15px;
        }
        .stat-ts-magic:hover {
            transform: translateY(-5px);
            background-color: var(--ts-light);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 2px solid var(--ts-primary);
        }
        .card-ts-magic {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .card-ts-magic:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(142, 68, 173, 0.15) !important;
            border: 1px solid var(--ts-secondary) !important;
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-ts fixed-top py-3">
        <div class="container-fluid px-4 px-xl-5 d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-2 playfair" href="/">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 80px;">
                @else
                    <span class="material-icons">diversity_1</span>
                @endif
                <span class="ms-2 fs-4">{{ $school->name ?? 'Colegio' }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#tsNav">
                <span class="material-icons" style="color: var(--ts-primary);">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="tsNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#quienes-somos">Nuestra Misión</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#autoridades">Autoridades</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn-ts-nav">Portal Colegiados</a>
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
        <section class="p-0 position-relative hero-slider-section" style="overflow: hidden; background-color: #000;">
            <div id="heroCarouselTS" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
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
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarouselTS" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarouselTS" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
                @endif
            </div>
    @else
        <!-- SIN SLIDER: Muestra el diseño moderno con gradientes y tarjetas glassmorphic -->
        <section class="hero-ts">
            <div class="container-fluid px-4 px-xl-5 position-relative">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="badge bg-white text-indigo border px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm d-inline-flex align-items-center gap-2" style="color: var(--ts-primary);">
                            <span class="material-icons fs-6" style="color: var(--ts-accent);">verified</span>
                            Organismo Oficial de Regulación
                        </div>
                        <h1 class="playfair">Empatía, <br>Derechos & <br>Comunidad.</h1>
                        <p>Órgano oficial que agrupa, regula y defiende a los profesionales del Trabajo Social en toda la provincia, promoviendo la ética y el compromiso social transformador.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="#quienes-somos" class="btn-ts-nav">Nuestra Misión</a>
                            <a href="#contacto" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-bold border-2 d-inline-flex align-items-center gap-2">
                                <span class="material-icons">mail</span> Contacto
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1 text-center">
                        <div class="position-relative d-inline-block">
                            <!-- Efecto de circulo resplandeciente de fondo -->
                            <div class="position-absolute bg-primary rounded-circle opacity-10 blur-3xl" style="width: 300px; height: 300px; top: 10%; left: 10%; filter: blur(40px);"></div>
                            <div class="card border-0 shadow-lg p-5 rounded-4" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4);">
                                @if(isset($school) && $school->logo)
                                    <img src="{{ asset($school->logo) }}" alt="Logo Gigante" class="img-fluid" style="max-height: 280px; filter: drop-shadow(0 15px 25px rgba(79,70,229,0.15));">
                                @endif
                                <h5 class="mt-4 fw-bold text-dark mb-1">{{ $school->name ?? 'Colegio' }}</h5>
                                <span class="text-muted small">La Rioja, Argentina</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="container-fluid px-4 px-xl-5 py-5">
        
        <!-- STATS & SERVICES -->
        <div class="row g-4 mb-5 pb-5 border-bottom pt-4">
            <div class="col-12 mb-4 text-center">
                <div class="row g-3 bg-white p-4 rounded-4 shadow-sm align-items-center">
                    <div class="col-6 col-md-3">
                        <div class="stat-ts-magic" data-bs-toggle="modal" data-bs-target="#modalTrabajadoresSociales" style="cursor: pointer; border: 2px solid transparent;">
                            <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">+{{ $school->collegiates()->count() ?? 0 }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Profesionales Matriculados</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-ts-magic" data-bs-toggle="modal" data-bs-target="#modalAnios" style="cursor: pointer; border: 2px solid transparent;">
                            <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">{{ \Carbon\Carbon::parse('1990-12-20')->age }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Años de Trayectoria</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-ts-magic" data-bs-toggle="modal" data-bs-target="#modalDepartamentos" style="cursor: pointer; border: 2px solid transparent;">
                            <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">18</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Departamentos de La Rioja</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-ts-magic" data-bs-toggle="modal" data-bs-target="#modalConvenios" style="cursor: pointer; border: 2px solid transparent;">
                            <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">{{ isset($agreements) ? $agreements->count() : 0 }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Convenios Vigentes</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm card-ts-magic" data-bs-toggle="modal" data-bs-target="#modalMatricula">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--ts-secondary);">badge</span>
                    <h4 class="playfair mb-2" style="font-size: 1.2rem;">Matrícula Habilitante</h4>
                    <p class="text-muted small mb-0">Verificá la habilitación legal de cualquier profesional.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm card-ts-magic" data-bs-toggle="modal" data-bs-target="#modalTramites">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--ts-secondary);">assignment</span>
                    <h4 class="playfair mb-2" style="font-size: 1.2rem;">Trámites</h4>
                    <p class="text-muted small mb-0">Información para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm card-ts-magic" data-bs-toggle="modal" data-bs-target="#modalAtencion">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--ts-secondary);">support_agent</span>
                    <h4 class="playfair mb-2" style="font-size: 1.2rem;">Atención</h4>
                    <p class="text-muted small mb-0">Consultas presenciales y asistencia administrativa.</p>
                </div>
            </div>
        </div>

        <!-- INSTITUCIONAL -->
        <div id="quienes-somos" class="row align-items-center mb-5 pb-5 pt-5">
            <div class="col-lg-6 mb-4">
                @php
                    $aboutImage = 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80';
                @endphp
                <div style="position: relative; border-radius: 30px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                    <img src="{{ $aboutImage }}" alt="Nosotros" class="img-fluid" style="width: 100%; height: 450px; object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h2 class="playfair" style="color: var(--ts-primary); font-size: 2.5rem; margin-bottom: 1.5rem;">Nuestra Misión</h2>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #666;">
                    El <strong>{{ $school->name }}</strong> tiene como propósito principal garantizar la jerarquización de la profesión,
                    el resguardo de las incumbencias y el acompañamiento constante a cada matriculado en su labor diaria.
                </p>
                <p style="font-size: 1.1rem; line-height: 1.8; color: #666;">
                    Fomentamos la solidaridad, el respeto por los derechos humanos y la construcción de una sociedad más justa y equitativa.
                </p>
            </div>
        </div>

        <!-- NOTICIAS -->
        <div id="novedades" class="mb-5 pb-5 pt-5">
            <h2 class="section-title">Últimas Novedades</h2>
            
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4 mt-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="card-ts d-flex flex-column">
                        @if($news->image_path)
                            <img src="{{ asset($news->image_path) }}" alt="{{ $news->title }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="border-radius: 15px; height: 200px; margin-bottom: 1.5rem; background: linear-gradient(135deg, var(--ts-primary, #8e44ad) 0%, rgba(142,68,173,0.8) 100%); position: relative; overflow: hidden;">
                                <div style="position: absolute; width: 150%; height: 150%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%); top: -25%; left: -25%;"></div>
                                @if(isset($school) && $school->logo)
                                    <img src="{{ asset($school->logo) }}" alt="Logo" style="max-height: 100px; opacity: 0.25; filter: grayscale(100%) brightness(200%); position: relative; z-index: 1;">
                                @else
                                    <span class="material-icons text-white" style="font-size: 5rem; opacity: 0.15; position: relative; z-index: 1;">volunteer_activism</span>
                                @endif
                            </div>
                        @endif
                        <span class="text-muted small mb-2"><span class="material-icons align-middle fs-6 me-1">calendar_today</span> {{ $news->published_at->format('d de M, Y') }}</span>
                        <h4 class="playfair mb-3" style="font-size: 1.3rem; line-height: 1.4;">{{ $news->title }}</h4>
                        <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                        <div class="mt-auto pt-3">
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none fw-bold" style="color: var(--ts-secondary);">Seguir Leyendo →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('news.index') }}" class="btn btn-outline-primary" style="border-radius: 50px; border-color: var(--ts-primary); color: var(--ts-primary); padding: 10px 30px; text-decoration:none;">Ver todas las noticias</a>
            </div>
            @else
            <div class="text-center p-5 rounded-4" style="background-color: #fff; border: 1px dashed var(--ts-secondary);">
                <p class="text-muted mb-0">La cartelera informativa se actualizará pronto.</p>
            </div>
            @endif
        </div>

        <!-- AUTORIDADES -->
        <div id="autoridades" class="mb-5 pb-5 pt-5">
            <h2 class="section-title">Nuestras Autoridades</h2>
            
            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <div class="mb-5 bg-white p-5 rounded-4 shadow-sm">
                        <h4 class="text-center playfair mb-5" style="color: var(--ts-secondary);">{{ $department }}</h4>
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
                            <div class="org-ts-node mb-4" style="border-top-color: var(--ts-secondary);">
                                @php
                                    $presImageUrl = $president->collegiate && $president->collegiate->avatar_url ? $president->collegiate->avatar_url : $president->image_path;
                                    $presName = $president->collegiate ? $president->collegiate->first_name . ' ' . $president->collegiate->last_name : $president->name;
                                @endphp
                                <img src="{{ $presImageUrl }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($presName) }}&background=8e44ad&color=fff'">
                                <h5 class="fw-bold mb-1">{{ $presName }}</h5>
                                <span class="badge rounded-pill" style="background-color: rgba(230,126,34,0.1); color: var(--ts-secondary);">{{ $president->role }}</span>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="d-flex flex-wrap justify-content-center gap-4">
                                @foreach($others as $m)
                                <div class="org-ts-node">
                                    @php
                                        $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                        $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                                    @endphp
                                    <img src="{{ $mImageUrl }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background=2c3e50&color=fff'">
                                    <h6 class="fw-bold mb-1">{{ $mName }}</h6>
                                    <small class="text-muted fw-bold d-block">{{ $m->role }}</small>
                                    @if($m->is_substitute) <small class="text-danger">Suplente</small> @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-4">
                    <p class="text-muted">Las autoridades se publicarán pronto.</p>
                </div>
            @endif
        </div>

        <!-- CONTACTO -->
        <div id="contacto" class="row g-4 mb-4 bg-white p-4 p-md-5 rounded-4 shadow-sm mt-4">
            <div class="col-lg-5">
                <h2 class="playfair mb-4" style="color: var(--ts-primary);">Estemos en Contacto</h2>
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center" style="width: 60px; height: 60px;">
                        <span class="material-icons" style="color: var(--ts-secondary); font-size: 28px;">location_on</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Dónde encontrarnos</small>
                        <strong class="fs-6">{{ $school->address ?? 'Dirección no disponible' }}</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center" style="width: 60px; height: 60px;">
                        <span class="material-icons" style="color: var(--ts-secondary); font-size: 28px;">phone</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Llamanos</small>
                        <strong class="fs-6">{{ $school->phone ?? 'Teléfono no disponible' }}</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center" style="width: 60px; height: 60px;">
                        <span class="material-icons" style="color: var(--ts-secondary); font-size: 28px;">email</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Escribinos</small>
                        <strong class="fs-6">{{ $school->email ?? 'correo@ejemplo.com' }}</strong>
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

                @if($school->map_embed_code)
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 100%; min-height: 350px;">
                        {!! $school->map_embed_code !!}
                    </div>
                @elseif($mapQuery)
                    <div class="rounded-4 overflow-hidden shadow-sm" style="height: 100%; min-height: 350px;">
                        <iframe width="100%" height="100%" style="border:0; min-height: 350px;" loading="lazy" allowfullscreen 
                            src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                        </iframe>
                    </div>
                @else
                    <div class="bg-light rounded-4 d-flex align-items-center justify-content-center" style="height: 350px;">
                        <span class="material-icons text-muted" style="font-size: 3rem;">map</span>
                    </div>
                @endif
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="footer-ts">
        <div class="container-fluid px-4 px-xl-5">
            <h3 class="playfair mb-3">{{ $school->name }}</h3>
            <p class="mb-0" style="opacity: 0.8;">&copy; {{ date('Y') }} Graficar Software de Mario Rojas. Todos los derechos reservados.</p>
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

        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag] || tag)
            );
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatbot-messages');
            
            // Append user message safely escaped
            messagesDiv.innerHTML += `
                <div class="d-flex mb-3 justify-content-end">
                    <div class="bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 85%;">${escapeHTML(message)}</div>
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
                
                // Append bot response safely escaped
                messagesDiv.innerHTML += `
                    <div class="d-flex mb-3">
                        <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 85%;">${escapeHTML(data.answer)}</div>
                    </div>
                `;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

    <!-- MODALES INTERACTIVOS TRABAJO SOCIAL -->
    <!-- Modal 1: Padrón de Trabajadores Sociales -->
    <div class="modal fade" id="modalTrabajadoresSociales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0" style="background-color: var(--ts-light);">
                    <h5 class="modal-title fw-bold playfair text-dark"><i class="bi bi-people me-2" style="color: var(--ts-primary);"></i> Padrón de Trabajadores Sociales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="input-group mb-4 shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 py-2" id="searchTrabajadoresSociales" placeholder="Buscar por nombre, matrícula o DNI...">
                    </div>
                    <div class="list-group list-group-flush rounded-3 border shadow-sm" id="listTrabajadoresSociales">
                        @if(isset($collegiates))
                            @foreach($collegiates as $colegiado)
                                <div class="list-group-item list-group-item-action p-3 trabajador-item">
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
                    <div class="display-1 mb-3" style="color: var(--ts-secondary);"><i class="bi bi-award"></i></div>
                    <h3 class="fw-bold playfair text-dark mb-3">{{ \Carbon\Carbon::parse('1990-12-20')->age }} Años de Trayectoria</h3>
                    <p class="text-muted">El Consejo Profesional de Trabajo Social de La Rioja ha liderado por décadas la jerarquización del ejercicio profesional y el firme compromiso con el bienestar y desarrollo de nuestra comunidad.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Departamentos -->
    <div class="modal fade" id="modalDepartamentos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0" style="background-color: var(--ts-light);">
                    <h5 class="modal-title fw-bold playfair text-dark"><i class="bi bi-geo-alt me-2" style="color: var(--ts-primary);"></i> Distribución por Departamentos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Profesionales matriculados con delegación activa por departamento en la provincia de La Rioja.</p>
                    <div class="accordion accordion-flush border rounded-4 overflow-hidden" id="accordionDeptos">
                        @php
                            $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martin', 'Independencia', 'Rosario Vera Penaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela'];
                        @endphp
                        @foreach($departamentos as $index => $depto)
                            @php
                                $deptCollegiates = isset($collegiates) ? $collegiates->where('city', $depto) : collect();
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#depto{{ $index }}" aria-expanded="false" aria-controls="depto{{ $index }}">
                                        {{ $depto }}
                                        <span class="badge ms-2 rounded-pill text-white" style="background-color: var(--ts-primary);">{{ $deptCollegiates->count() }}</span>
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
                                                <i class="bi bi-info-circle fs-4 d-block mb-2" style="color: var(--ts-primary);"></i> 
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
                <div class="modal-header border-bottom-0" style="background-color: var(--ts-light);">
                    <h5 class="modal-title fw-bold playfair text-dark"><i class="bi bi-briefcase me-2" style="color: var(--ts-primary);"></i> Convenios Comerciales</h5>
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
                        <div class="text-center p-5 rounded-4 bg-white border shadow-sm">
                            <span class="material-icons text-muted fs-1 mb-3">card_membership</span>
                            <p class="text-muted mb-0">No hay convenios comerciales activos actualmente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Servicios -->
    <div class="modal fade" id="modalMatricula" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold playfair"><i class="bi bi-shield-check me-2" style="color: var(--ts-primary);"></i> Matrícula Habilitante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Puedes validar la habilitación de cualquier profesional en nuestra jurisdicción. Para certificar oficialmente la vigencia de la matrícula, puedes solicitar el certificado de habilitación a través de la plataforma.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTramites" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold playfair"><i class="bi bi-file-earmark-text me-2" style="color: var(--ts-primary);"></i> Trámites y Requisitos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Título habilitante de Lic. en Trabajo Social o equivalente (Copia legalizada)</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Documento Nacional de Identidad</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Constancia de CUIL</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> 2 fotos carnet claras de frente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAtencion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold playfair"><i class="bi bi-headset me-2" style="color: var(--ts-primary);"></i> Atención al Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>El Consejo ofrece atención en su sede central en los siguientes horarios:</p>
                    <ul>
                        <li><strong>Lunes a Viernes:</strong> 9:00 hs a 13:00 hs.</li>
                        <li><strong>Canal telefónico y online:</strong> De 9:00 hs a 17:00 hs.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BUSCADOR EN VIVO TRABAJO SOCIAL -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchTrabajadoresSociales');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const items = document.querySelectorAll('.trabajador-item');
                    
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
</body>
</html>

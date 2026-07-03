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
            --primary: #111111; /* Negro Técnico */
            --accent: #ff6b00; /* Naranja Energético */
            --light: #f4f5f6; /* Gris Plano claro */
            --dark: #090909;
            --gray: #6b7280;
            --grid-pattern: linear-gradient(rgba(255, 107, 0, 0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 107, 0, 0.04) 1px, transparent 1px);
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--primary);
            background-color: var(--light);
            background-image: var(--grid-pattern);
            background-size: 30px 30px;
        }

        h1, h2, h3, h4, h5, h6, .oswald {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* NAVBAR */
        .navbar-arq {
            background-color: rgba(9, 9, 9, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--accent);
            padding: 0.8rem 0;
        }
        .navbar-arq .navbar-brand {
            color: #fff !important;
            letter-spacing: 3px;
            font-weight: 800;
        }
        .navbar-arq .navbar-brand span {
            color: var(--accent);
        }
        .btn-arq-nav {
            border: 2px solid var(--accent);
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 0;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: transparent;
        }
        .btn-arq-nav:hover {
            background-color: var(--accent);
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-arq {
            height: 90vh;
            background: linear-gradient(135deg, rgba(9, 9, 9, 0.9) 0%, rgba(26, 26, 26, 0.95) 100%), url('{{ $bgImage }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            position: relative;
            clip-path: polygon(0 0, 100% 0, 100% 92%, 0 100%);
            border-bottom: 4px solid var(--accent);
        }
        .hero-arq h1 {
            font-size: 5.5rem;
            color: #fff;
            letter-spacing: 5px;
            line-height: 0.95;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }
        .hero-arq h1 span {
            color: var(--accent);
            font-weight: 200;
        }
        .hero-arq p {
            font-size: 1.25rem;
            color: #d1d5db;
            font-weight: 400;
            letter-spacing: 1px;
            max-width: 650px;
            line-height: 1.6;
        }

        /* SECTIONS */
        .section-title {
            font-size: 3.2rem;
            color: var(--dark);
            position: relative;
            display: inline-block;
            margin-bottom: 3.5rem;
            font-weight: 800;
        }
        .section-title span {
            font-weight: 200;
            color: var(--accent);
        }
        .section-title::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 80%;
            background-color: var(--accent);
        }

        /* CARDS & NEWS */
        .card-arq {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            position: relative;
        }
        .card-arq:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 107, 0, 0.08);
            border-color: var(--accent);
        }
        .card-arq-img {
            height: 260px;
            object-fit: cover;
            filter: grayscale(100%);
            transition: all 0.4s ease;
        }
        .card-arq:hover .card-arq-img {
            filter: grayscale(0%);
        }
        .card-arq-body {
            padding: 2.5rem 2rem;
        }
        
        /* ORG CHART */
        .node-arq {
            background: #fff;
            border-left: 5px solid var(--dark);
            padding: 1.8rem;
            width: 270px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            position: relative;
            transition: all 0.3s ease;
        }
        .node-arq:hover {
            transform: scale(1.03);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }
        .node-arq.president {
            border-left-color: var(--accent);
        }
        .node-arq img {
            width: 64px;
            height: 64px;
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

        .stat-arq-magic {
            border: 1px solid rgba(0,0,0,0.1);
            border-top: 4px solid var(--accent);
            padding: 25px 15px;
            position: relative;
            background: white;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
        }
        .stat-arq-magic::before {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            width: 12px;
            height: 12px;
            border-top: 2px solid var(--accent);
            border-left: 2px solid var(--accent);
        }
        .stat-arq-magic::after {
            content: '';
            position: absolute;
            bottom: -6px;
            right: -6px;
            width: 12px;
            height: 12px;
            border-bottom: 2px solid var(--accent);
            border-right: 2px solid var(--accent);
        }
        .stat-arq-magic:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.1);
            border-color: var(--accent);
        }
        
        .card-arq-magic {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .card-arq-magic:hover {
            transform: translateY(-5px);
            border-color: var(--accent) !important;
            box-shadow: 0 15px 30px rgba(255, 107, 0, 0.08) !important;
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
        <!-- SIN SLIDER: Muestra el diseño premium con cuadrícula técnica de fondo -->
        <section class="hero-arq">
            <!-- Capa de rejilla tecnica adicional para el Hero -->
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; background-image: linear-gradient(rgba(255, 107, 0, 0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 107, 0, 0.08) 1px, transparent 1px); background-size: 20px 20px; opacity: 0.7; z-index: 1;"></div>
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(135deg, rgba(9, 9, 9, 0.95) 0%, rgba(26, 26, 26, 0.7) 100%); z-index: 2;"></div>
            <div class="container-fluid px-4 px-xl-5 position-relative h-100 d-flex align-items-center" style="z-index: 3; width: 100%;">
                <div class="row align-items-center w-100 g-5">
                    <div class="col-lg-7">
                        <div class="d-inline-block border border-2 border-primary px-3 py-1 text-white small fw-bold text-uppercase tracking-wider mb-4" style="border-color: var(--accent) !important; color: var(--accent) !important;">
                            Establecido en 1990 | La Rioja
                        </div>
                        <h1 class="oswald">DISEÑO,<br><span>VANGUARDIA</span><br>& ESTRUCTURA.</h1>
                        <p>Órgano oficial de regulación profesional. Defendiendo las incumbencias, rindiendo la matrícula y promoviendo la excelencia arquitectónica en nuestra jurisdicción.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="#quienes-somos" class="btn btn-arq-nav" style="border-color: var(--accent); color: #fff; background-color: var(--accent);">Nuestra Misión</a>
                            <a href="#contacto" class="btn btn-outline-light rounded-0 px-4 py-3 text-uppercase tracking-wider fw-bold">Contacto Institucional</a>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-1 d-none d-lg-block">
                        <div class="position-relative border border-2 p-5" style="border-color: rgba(255,107,0,0.3) !important;">
                            <!-- Esquineros de plano decorativos para el logo en Hero -->
                            <div style="position: absolute; top: -6px; left: -6px; width: 12px; height: 12px; border-top: 2px solid var(--accent); border-left: 2px solid var(--accent);"></div>
                            <div style="position: absolute; bottom: -6px; right: -6px; width: 12px; height: 12px; border-bottom: 2px solid var(--accent); border-right: 2px solid var(--accent);"></div>
                            @if(isset($school) && $school->logo)
                                <img src="{{ asset($school->logo) }}" alt="CAPLaR Logo" class="img-fluid" style="max-height: 250px; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.5));">
                            @endif
                        </div>
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
                        <div class="stat-arq-magic" data-bs-toggle="modal" data-bs-target="#modalArquitectos" style="cursor: pointer;">
                            <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">+{{ $school->collegiates()->count() ?? 0 }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Profesionales Matriculados</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-arq-magic" data-bs-toggle="modal" data-bs-target="#modalAnios" style="cursor: pointer;">
                            <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">{{ \Carbon\Carbon::parse('1990-12-20')->age }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Años de Trayectoria</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-arq-magic" data-bs-toggle="modal" data-bs-target="#modalDepartamentos" style="cursor: pointer;">
                            <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">18</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Departamentos de La Rioja</p>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-arq-magic" data-bs-toggle="modal" data-bs-target="#modalConvenios" style="cursor: pointer;">
                            <h2 class="display-5 fw-bold mb-0 oswald" style="color: var(--accent);">{{ isset($agreements) ? $agreements->count() : 0 }}</h2>
                            <p class="text-muted small mt-2 fw-bold mb-0">Convenios Vigentes</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border card-arq-magic" data-bs-toggle="modal" data-bs-target="#modalMatricula" style="cursor: pointer;">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--accent);">badge</span>
                    <h4 class="oswald mb-2" style="font-size: 1.2rem;">Matrícula Habilitante</h4>
                    <p class="text-muted small mb-0">Verificá la habilitación legal de cualquier profesional.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border card-arq-magic" data-bs-toggle="modal" data-bs-target="#modalTramites" style="cursor: pointer;">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--accent);">assignment</span>
                    <h4 class="oswald mb-2" style="font-size: 1.2rem;">Trámites</h4>
                    <p class="text-muted small mb-0">Información para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-arq p-4 text-center border card-arq-magic" data-bs-toggle="modal" data-bs-target="#modalAtencion" style="cursor: pointer;">
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
                    $aboutImage = 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80';
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

    <!-- MODALES INTERACTIVOS DE ARQUITECTOS -->
    <!-- Modal 1: Padrón de Arquitectos -->
    <div class="modal fade" id="modalArquitectos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0" style="background-color: var(--light);">
                    <h5 class="modal-title fw-bold oswald text-dark"><i class="bi bi-people me-2" style="color: var(--accent);"></i> Padrón de Arquitectos Matriculados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="input-group mb-4 shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0 py-2" id="searchArquitectos" placeholder="Buscar por nombre, matrícula o DNI...">
                    </div>
                    <div class="list-group list-group-flush rounded-3 border shadow-sm" id="listArquitectos">
                        @if(isset($collegiates))
                            @foreach($collegiates as $colegiado)
                                <div class="list-group-item list-group-item-action p-3 arquitecto-item">
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
                    <div class="display-1 mb-3" style="color: var(--accent);"><i class="bi bi-award"></i></div>
                    <h3 class="fw-bold oswald text-dark mb-3">{{ \Carbon\Carbon::parse('1990-12-20')->age }} Años de Trayectoria</h3>
                    <p class="text-muted">El Colegio de Arquitectos de La Rioja (CAPLaR) ha trabajado desde su creación en la defensa de las incumbencias profesionales y el impulso del planeamiento urbano ético y sustentable.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Departamentos -->
    <div class="modal fade" id="modalDepartamentos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0" style="background-color: var(--light);">
                    <h5 class="modal-title fw-bold oswald text-dark"><i class="bi bi-geo-alt me-2" style="color: var(--accent);"></i> Delegaciones por Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Arquitectos matriculados distribuidos en las delegaciones de los departamentos de La Rioja.</p>
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
                                        <span class="badge ms-2 rounded-pill text-white" style="background-color: var(--accent);">{{ $deptCollegiates->count() }}</span>
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
                                                <i class="bi bi-info-circle fs-4 d-block mb-2" style="color: var(--accent);"></i> 
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
                <div class="modal-header border-bottom-0" style="background-color: var(--light);">
                    <h5 class="modal-title fw-bold oswald text-dark"><i class="bi bi-briefcase me-2" style="color: var(--accent);"></i> Convenios Comerciales CAPLaR</h5>
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
                    <h5 class="modal-title fw-bold oswald"><i class="bi bi-shield-check me-2" style="color: var(--accent);"></i> Matrícula Habilitante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Puedes validar la habilitación legal de cualquier profesional de la construcción. Si un profesional no se encuentra listado en el Padrón de Arquitectos, por favor contáctanos de inmediato para corroborar su estado.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTramites" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold oswald"><i class="bi bi-file-earmark-text me-2" style="color: var(--accent);"></i> Trámites y Requisitos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Título habilitante de Arquitecto (Original y copia legalizada)</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Documento Nacional de Identidad</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Certificado de reincidencia / antecedentes</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> 2 fotos carnet color de frente</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAtencion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold oswald"><i class="bi bi-headset me-2" style="color: var(--accent);"></i> Atención al Colegiado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Las oficinas administrativas del CAPLaR atienden en los siguientes horarios:</p>
                    <ul>
                        <li><strong>Lunes a Viernes:</strong> 8:00 hs a 13:00 hs y de 17:00 hs a 20:00 hs.</li>
                        <li><strong>Consultas técnicas:</strong> Martes y Jueves de 10:00 hs a 12:00 hs.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BUSCADOR EN VIVO DE ARQUITECTOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchArquitectos');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const items = document.querySelectorAll('.arquitecto-item');
                    
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Consejo Profesional de Trabajo Social de La Rioja' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts: Outfit (títulos) e Inter (cuerpo) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ts-primary: #1E3A8A; /* Azul Institucional Profundo */
            --ts-primary-rgb: 30, 58, 138;
            --ts-secondary: #DC2626; /* Rojo / Coral de Acento */
            --ts-secondary-rgb: 220, 38, 38;
            --ts-dark: #0F172A; /* Slate 900 */
            --ts-light: #F8FAFC; /* Slate 50 */
            --ts-slate-300: #CBD5E1;
            --ts-gradient-primary: linear-gradient(135deg, #1E3A8A 0%, #0F172A 100%);
            --ts-gradient-light: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
            --ts-gradient-accent: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            --ts-card-bg: rgba(255, 255, 255, 0.85);
            --ts-glass-border: rgba(30, 58, 138, 0.08);
            --ts-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --ts-shadow-md: 0 10px 15px -3px rgba(30, 58, 138, 0.05), 0 4px 6px -4px rgba(30, 58, 138, 0.05);
            --ts-shadow-lg: 0 20px 25px -5px rgba(30, 58, 138, 0.1), 0 8px 10px -6px rgba(30, 58, 138, 0.1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ts-dark);
            background: var(--ts-gradient-light);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--ts-light);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--ts-slate-300);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* NAVBAR FLOTANTE TIPO CÁPSULA */
        .navbar-ts {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--ts-glass-border);
            transition: all 0.3s ease;
        }
        
        .navbar-ts.scrolled {
            padding: 8px 0;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--ts-shadow-md);
        }

        .navbar-brand img {
            transition: all 0.3s ease;
        }

        .nav-link {
            color: #334155 !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 16px !important;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--ts-primary) !important;
            background: rgba(30, 58, 138, 0.05);
        }

        .btn-portal {
            background: var(--ts-gradient-primary);
            color: #fff !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 10px 24px;
            border-radius: 30px;
            box-shadow: 0 4px 14px rgba(30, 58, 138, 0.25);
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-portal:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
            background: var(--ts-primary);
            color: #fff !important;
        }

        /* HERO SECTION */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 
                ? (Str::startsWith($slider->items->first()->image_url, ['http://', 'https://']) ? $slider->items->first()->image_url : asset('storage/' . $slider->items->first()->image_url)) 
                : asset('images/trabajosocial_hero.png');
        @endphp

        .hero-ts {
            position: relative;
            padding: 180px 0 100px;
            background: linear-gradient(to right, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0.95) 45%, rgba(248, 250, 252, 0.65) 75%, rgba(248, 250, 252, 0.15) 100%), url('{{ $bgImage }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        @media (max-width: 991px) {
            .hero-ts {
                background: linear-gradient(to bottom, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0.95) 60%, rgba(248, 250, 252, 0.5) 85%, rgba(248, 250, 252, 0.15) 100%), url('{{ $bgImage }}') !important;
                background-attachment: scroll !important; /* Desactivar fixed en moviles para mejorar rendimiento */
            }
        }

        .hero-slider-overlay {
            background: linear-gradient(to right, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0.95) 45%, rgba(248, 250, 252, 0.65) 75%, rgba(248, 250, 252, 0.15) 100%);
        }

        @media (max-width: 991px) {
            .hero-slider-overlay {
                background: linear-gradient(to bottom, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0.95) 60%, rgba(248, 250, 252, 0.5) 85%, rgba(248, 250, 252, 0.15) 100%) !important;
            }
        }

        .hero-title {
            font-size: 4.2rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--ts-dark);
            margin-bottom: 1.5rem;
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--ts-primary) 0%, var(--ts-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #475569;
            line-height: 1.7;
            max-width: 600px;
            margin-bottom: 2.2rem;
        }

        /* COLLAGE HERO DERECHO */
        .collage-container {
            position: relative;
            height: 480px;
            width: 100%;
        }

        .collage-card-main {
            position: absolute;
            top: 20px;
            left: 40px;
            width: 80%;
            height: 380px;
            background: #fff;
            border-radius: 28px;
            box-shadow: var(--ts-shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .collage-card-main img {
            max-height: 200px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(30, 58, 138, 0.1));
            transition: all 0.3s ease;
        }

        .collage-card-main:hover {
            transform: translateY(-5px);
        }

        .collage-badge {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border: 1px solid var(--ts-glass-border);
            border-radius: 20px;
            padding: 14px 20px;
            box-shadow: var(--ts-shadow-md);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .collage-badge-1 {
            bottom: 30px;
            left: 0;
        }

        .collage-badge-2 {
            top: 0;
            right: 0;
        }

        .collage-badge:hover {
            transform: scale(1.05) translateY(-3px);
        }

        /* ESTADÍSTICAS FLOTANTES 3D */
        .stats-floating-bar {
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }

        .stat-card-3d {
            background: #fff;
            border-radius: 24px;
            padding: 24px;
            text-align: center;
            box-shadow: var(--ts-shadow-md);
            border: 1px solid var(--ts-glass-border);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card-3d::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--ts-primary), var(--ts-secondary));
            opacity: 0;
            transition: all 0.3s ease;
        }

        .stat-card-3d:hover {
            transform: translateY(-8px);
            box-shadow: var(--ts-shadow-lg);
            border-color: rgba(30, 58, 138, 0.15);
        }

        .stat-card-3d:hover::after {
            opacity: 1;
        }

        .stat-card-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(30, 58, 138, 0.06);
            color: var(--ts-primary);
            font-size: 1.5rem;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }

        .stat-card-3d:hover .stat-card-icon {
            background: var(--ts-gradient-primary);
            color: #fff;
            transform: scale(1.1);
        }

        /* TARJETAS DE SERVICIOS */
        .service-card-premium {
            background: var(--ts-card-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--ts-glass-border);
            border-radius: 24px;
            padding: 32px;
            height: 100%;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: var(--ts-shadow-sm);
            display: flex;
            flex-direction: column;
        }

        .service-card-premium:hover {
            transform: translateY(-6px);
            box-shadow: var(--ts-shadow-lg);
            border-color: rgba(30, 58, 138, 0.2);
            background: #fff;
        }

        .service-card-icon {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: rgba(220, 38, 38, 0.08);
            color: var(--ts-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .service-card-premium:hover .service-card-icon {
            background: var(--ts-gradient-accent);
            color: #fff;
            transform: scale(1.08) rotate(5deg);
        }

        /* SECCIÓN DE HITO HISTÓRICO */
        .section-history {
            background: var(--ts-gradient-primary);
            color: #fff;
            border-radius: 40px;
            padding: 80px 60px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--ts-shadow-lg);
            margin: 60px 0;
        }

        .section-history::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.15) 0%, transparent 70%);
            z-index: 1;
        }

        .history-content {
            position: relative;
            z-index: 2;
        }

        .history-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* NOVEDADES */
        .news-card-modern {
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--ts-glass-border);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: var(--ts-shadow-sm);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .news-card-modern:hover {
            transform: translateY(-6px);
            box-shadow: var(--ts-shadow-lg);
            border-color: rgba(30, 58, 138, 0.15);
        }

        .news-image-container {
            height: 230px;
            overflow: hidden;
            position: relative;
        }

        .news-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .news-card-modern:hover .news-image-container img {
            transform: scale(1.05);
        }

        .news-category-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(30, 58, 138, 0.95);
            color: #fff;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .news-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .news-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ts-dark);
            line-height: 1.4;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .news-card-modern:hover .news-title {
            color: var(--ts-primary);
        }

        /* AUTORIDADES */
        .org-member-card {
            background: #fff;
            border-radius: 24px;
            padding: 30px 24px;
            text-align: center;
            border: 1px solid var(--ts-glass-border);
            box-shadow: var(--ts-shadow-sm);
            width: 250px;
            transition: all 0.3s ease;
        }

        .org-member-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--ts-shadow-md);
            border-color: rgba(30, 58, 138, 0.15);
        }

        .org-member-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 16px;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.15);
            display: block;
        }

        /* FOOTER */
        .footer-premium {
            background: var(--ts-gradient-primary);
            color: #cbd5e1;
            padding: 80px 0 40px;
            position: relative;
        }

        .footer-logo {
            filter: brightness(0) invert(1);
            max-height: 80px;
        }

        /* MODALES */
        .modal-content-premium {
            border-radius: 28px;
            border: none;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header-premium {
            background: var(--ts-gradient-primary);
            color: #fff;
            padding: 24px 32px;
            border-bottom: none;
        }

        .modal-header-premium .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-body-premium {
            padding: 32px;
            background: var(--ts-light);
        }

        /* ACORDEÓN PREMIUM */
        .accordion-premium .accordion-item {
            border: 1px solid var(--ts-glass-border) !important;
            border-radius: 16px !important;
            margin-bottom: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: var(--ts-shadow-sm);
        }

        .accordion-premium .accordion-button {
            font-weight: 700;
            color: var(--ts-dark);
            padding: 20px 24px;
            background: #fff;
            box-shadow: none;
        }

        .accordion-premium .accordion-button:not(.collapsed) {
            background: rgba(30, 58, 138, 0.03);
            color: var(--ts-primary);
            border-bottom: 1px solid var(--ts-glass-border);
        }

        .accordion-premium .accordion-button::after {
            filter: sepia(100%) hue-rotate(190deg) saturate(900%);
        }

        .list-group-item-premium {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(30, 58, 138, 0.05);
            padding: 16px 24px;
        }

        .list-group-item-premium:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-ts fixed-top py-3">
        <div class="container-fluid px-4 px-xl-5 d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 64px;">
                @else
                    <span class="fs-4 fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>C.P.T.S.</span>
                @endif
                <div class="d-none d-md-flex flex-column lh-1 ms-1">
                    <span class="fs-5 fw-extrabold text-dark tracking-tight">TRABAJO SOCIAL</span>
                    <span class="text-muted small fw-bold">CONSEJO PROFESIONAL LA RIOJA</span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#tsNav">
                <i class="bi bi-list fs-1 text-primary"></i>
            </button>

            <div class="collapse navbar-collapse" id="tsNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item"><a class="nav-link" href="#quienes-somos">Nuestra Misión</a></li>
                    <li class="nav-item"><a class="nav-link" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#autoridades">Autoridades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn-portal"><i class="bi bi-shield-lock me-2"></i>Portal Colegiados</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    @php
        $sliderItems = isset($slider) && $slider->items->count() > 0 ? $slider->items : collect([]);
    @endphp

    @if($sliderItems->count() > 0)
        <style>
            .hero-slider-section { min-height: 90vh; margin-top: 96px; display: flex; align-items: center; }
            @media (max-width: 991px) { .hero-slider-section { min-height: 650px; } }
            @media (max-width: 576px) { .hero-slider-section { min-height: 550px; } }
        </style>
        <section class="p-0 position-relative hero-slider-section" style="overflow: hidden; background-color: #000; border-radius: 0 0 32px 32px;">
            <div id="heroCarouselTS" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                    @foreach($sliderItems as $index => $item)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }} position-relative" data-bs-interval="5000">
                            @php
                                $imgSrc = Str::startsWith($item->image_url, ['http://', 'https://']) 
                                    ? $item->image_url 
                                    : (Str::startsWith($item->image_url, 'images/') ? asset($item->image_url) : asset('storage/' . $item->image_url));
                            @endphp
                            
                            <!-- Imagen de fondo del slide -->
                            <div class="w-100 h-100 position-absolute top-0 start-0 hero-slider-bg" style="background-image: url('{{ $imgSrc }}'); background-size: cover; background-position: center; z-index: 1;"></div>
                            
                            <!-- Capa de degradado responsivo y contenedor de textos -->
                            <div class="hero-slider-overlay w-100 h-100 position-absolute top-0 start-0 d-flex align-items-center" style="z-index: 2;">
                                <div class="container-fluid px-4 px-xl-5">
                                    <div class="row">
                                        <div class="col-lg-8 col-xl-7">
                                            @if($index == 0)
                                                <div class="badge bg-white text-primary border border-2 border-primary-subtle px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                                    <i class="bi bi-people-fill text-danger"></i>
                                                    Comunidad y Acción Social
                                                </div>
                                            @elseif($index == 1)
                                                <div class="badge bg-white text-primary border border-2 border-primary-subtle px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                                    <i class="bi bi-book-half text-danger"></i>
                                                    Formación Profesional
                                                </div>
                                            @else
                                                <div class="badge bg-white text-primary border border-2 border-primary-subtle px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                                    <i class="bi bi-award-fill text-danger"></i>
                                                    Respaldo Institucional
                                                </div>
                                            @endif
                                            
                                            <h1 class="hero-title">{!! $item->title ?? 'Defensa de Derechos, <br><span>Empatía & Compromiso.</span>' !!}</h1>
                                            <p class="hero-subtitle">{{ $item->description ?? 'Agrupamos, jerarquizamos y respaldamos a los profesionales del Trabajo Social de La Rioja.' }}</p>
                                            
                                            <div class="d-flex flex-wrap gap-3">
                                                @if($item->link)
                                                    <a href="{{ $item->link }}" class="btn-portal"><i class="bi bi-arrow-right-circle me-2"></i>Más Información</a>
                                                @else
                                                    <a href="#quienes-somos" class="btn-portal"><i class="bi bi-info-circle me-2"></i>Nuestra Misión</a>
                                                @endif
                                                <a href="#contacto" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-bold border-2 d-inline-flex align-items-center gap-2">
                                                    <i class="bi bi-envelope"></i> Contacto
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($sliderItems->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarouselTS" data-bs-slide="prev" style="z-index: 10;">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(30, 58, 138, 0.4); border-radius: 50%; padding: 20px;"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarouselTS" data-bs-slide="next" style="z-index: 10;">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(30, 58, 138, 0.4); border-radius: 50%; padding: 20px;"></span>
                </button>
                @endif
            </div>
        </section>
    @else
        <!-- HERO ESTÁTICO PREMIUM -->
        <section class="hero-ts">
            <div class="container-fluid px-4 px-xl-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-8 col-xl-7">
                        <div class="badge bg-white text-primary border border-2 border-primary-subtle px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-patch-check-fill text-danger"></i>
                            Organismo Regulador de la Matrícula
                        </div>
                        <h1 class="hero-title">Defensa de Derechos, <br><span>Empatía & Compromiso.</span></h1>
                        <p class="hero-subtitle">Agrupamos, jerarquizamos y respaldamos a los profesionales del Trabajo Social de La Rioja, promoviendo el ejercicio ético, legal y solidario en toda nuestra provincia.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#quienes-somos" class="btn-portal"><i class="bi bi-info-circle me-2"></i>Nuestra Misión</a>
                            <a href="#contacto" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-bold border-2 d-inline-flex align-items-center gap-2">
                                <i class="bi bi-envelope"></i> Contacto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="container-fluid px-4 px-xl-5">
        
        <!-- ESTADÍSTICAS FLOTANTES (Fila 3D) -->
        <section class="stats-floating-bar container mb-5">
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-lg-3">
                    <div class="stat-card-3d" data-bs-toggle="modal" data-bs-target="#modalTrabajadoresSociales">
                        <div class="stat-card-icon"><i class="bi bi-people-fill"></i></div>
                        <h2 class="display-6 fw-bold mb-0 text-dark tracking-tight">+{{ $collegiates->count() ?? 0 }}</h2>
                        <p class="text-muted small mt-2 fw-bold mb-0">Profesionales Matriculados</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-3d" data-bs-toggle="modal" data-bs-target="#modalAnios">
                        <div class="stat-card-icon"><i class="bi bi-award-fill"></i></div>
                        <h2 class="display-6 fw-bold mb-0 text-dark tracking-tight">{{ \Carbon\Carbon::parse('2009-08-11')->age }}</h2>
                        <p class="text-muted small mt-2 fw-bold mb-0">Años de Trayectoria</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-3d" data-bs-toggle="modal" data-bs-target="#modalDepartamentos">
                        <div class="stat-card-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <h2 class="display-6 fw-bold mb-0 text-dark tracking-tight">18</h2>
                        <p class="text-muted small mt-2 fw-bold mb-0">Departamentos de La Rioja</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="stat-card-3d" data-bs-toggle="modal" data-bs-target="#modalConvenios">
                        <div class="stat-card-icon"><i class="bi bi-handshake-fill"></i></div>
                        <h2 class="display-6 fw-bold mb-0 text-dark tracking-tight">{{ isset($agreements) ? $agreements->count() : 0 }}</h2>
                        <p class="text-muted small mt-2 fw-bold mb-0">Convenios Vigentes</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN DE SERVICIOS -->
        <section class="py-5">
            <div class="text-center mb-5">
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold mb-2">GESTIÓN DIRECTA</span>
                <h2 class="display-5 fw-bold text-dark">Servicios y Respuestas Rápidas</h2>
                <p class="text-muted max-width-600 mx-auto">Ponemos a tu disposición accesos rápidos para validar el ejercicio y realizar trámites administrativos.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="service-card-premium" data-bs-toggle="modal" data-bs-target="#modalMatricula">
                        <div class="service-card-icon"><i class="bi bi-shield-check"></i></div>
                        <h4 class="fw-bold mb-3 text-dark">Matrícula Habilitante</h4>
                        <p class="text-muted small mb-0">Verificá en tiempo real la validez del ejercicio profesional de un matriculado en nuestra provincia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card-premium" data-bs-toggle="modal" data-bs-target="#modalTramites">
                        <div class="service-card-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <h4 class="fw-bold mb-3 text-dark">Trámites</h4>
                        <p class="text-muted small mb-0">Revisá la documentación, requisitos e instructivos para registrarte o actualizar tu matrícula.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card-premium" data-bs-toggle="modal" data-bs-target="#modalAtencion">
                        <div class="service-card-icon"><i class="bi bi-headset"></i></div>
                        <h4 class="fw-bold mb-3 text-dark">Atención al Profesional</h4>
                        <p class="text-muted small mb-0">Consultas administrativas, horarios de la sede física y canales de atención por Whatsapp o e-mail.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN DE HITO HISTÓRICO (NUEVO REGISTRO LEY 8.522) -->
        <section class="container shadow-sm border border-light" style="border-radius: 40px; overflow: hidden;">
            <div class="section-history">
                <div class="row align-items-center g-5 history-content">
                    <div class="col-lg-7">
                        <span class="history-badge"><i class="bi bi-bank me-2"></i>Hito Institucional</span>
                        <h2 class="display-5 fw-bold mb-4">Sanción de la Ley Provincial Nº 8.522</h2>
                        <p class="lead mb-4" style="opacity: 0.95;">
                            Creado formalmente en <strong>agosto de 2009</strong>, el Consejo Profesional de Trabajo Social obtuvo mediante esta legislación el rango de <strong>Persona Jurídica de Derecho Público</strong>.
                        </p>
                        <p class="mb-0" style="opacity: 0.85; font-size: 1.05rem;">
                            La publicación de la Ley en el Boletín Oficial, realizada el 11 de agosto de 2009, otorgó la delegación oficial para centralizar el gobierno de las matrículas, vigilar el cumplimiento ético de la profesión y fiscalizar la labor de los trabajadores sociales en todo el territorio de La Rioja.
                        </p>
                    </div>
                    <div class="col-lg-5 text-center">
                        <div class="bg-white text-dark p-5 rounded-4 shadow-lg border border-light mx-auto" style="max-width: 320px;">
                            <div class="text-danger display-3 mb-3"><i class="bi bi-calendar-check-fill"></i></div>
                            <h3 class="fw-extrabold text-dark mb-1">11 de Agosto</h3>
                            <h5 class="text-muted fw-bold mb-4">de 2009</h5>
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6">Fundación Oficial</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NUESTRA MISIÓN -->
        <section id="quienes-somos" class="py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    @php
                        $aboutImage = 'https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
                    @endphp
                    <div style="position: relative; border-radius: 32px; overflow: hidden; box-shadow: var(--ts-shadow-lg);">
                        <img src="{{ $aboutImage }}" alt="Nosotros" class="img-fluid" style="width: 100%; height: 420px; object-fit: cover;">
                        <div style="position: absolute; bottom: 30px; left: 30px; background: rgba(255,255,255,0.9); backdrop-filter: blur(8px); padding: 20px; border-radius: 20px; box-shadow: var(--ts-shadow-md);">
                            <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-heart-pulse-fill text-danger me-2"></i>Compromiso Social</h5>
                            <small class="text-muted">Construyendo equidad desde las bases</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">QUIÉNES SOMOS</span>
                    <h2 class="display-5 fw-bold text-dark mb-4">Nuestra Misión</h2>
                    <p class="fs-5 text-secondary mb-4">
                        El <strong>{{ $school->name }}</strong> busca de manera fundamental la jerarquización del ejercicio profesional, la defensa ética del rol, y el apoyo integral a cada colegiado en su intervención en la comunidad.
                    </p>
                    <div class="d-flex gap-4 align-items-start mb-4">
                        <div class="bg-primary text-white p-3 rounded-4 d-flex"><i class="bi bi-shield-check fs-4"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Fiscalización y Ética</h5>
                            <p class="text-muted mb-0">Habilitar a los profesionales garantizando intervenciones responsables e idóneas.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-4 align-items-start">
                        <div class="bg-danger text-white p-3 rounded-4 d-flex"><i class="bi bi-people fs-4"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Solidaridad Colectiva</h5>
                            <p class="text-muted mb-0">Promover espacios de formación continua y acompañamiento ante realidades sociales.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOVEDADES (NOTICIAS) -->
        <section id="novedades" class="py-5">
            <div class="text-center mb-5">
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold mb-2">NOTICIAS</span>
                <h2 class="display-5 fw-bold text-dark">Últimas Novedades</h2>
                <p class="text-muted max-width-600 mx-auto">Mantenete al tanto de los comunicados oficiales, asambleas y actividades organizadas por el Consejo.</p>
            </div>
            
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4 mt-2">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="news-card-modern">
                        <div class="news-image-container">
                            @if($news->featured_image_url)
                                <img src="{{ asset($news->featured_image_url) }}" alt="{{ $news->title }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100" style="background: var(--ts-gradient-primary);">
                                    @if(isset($school) && $school->logo)
                                        <img src="{{ asset($school->logo) }}" alt="Logo" style="max-height: 80px; opacity: 0.2;">
                                    @else
                                        <i class="bi bi-journal-text text-white" style="font-size: 4rem; opacity: 0.15;"></i>
                                    @endif
                                </div>
                            @endif
                            <span class="news-category-badge">Institucional</span>
                        </div>
                        <div class="news-body">
                            <span class="text-muted small mb-2"><i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($news->published_at)->format('d de M, Y') }}</span>
                            <h4 class="news-title">{{ $news->title }}</h4>
                            <p class="text-secondary small mb-4 flex-grow-1">{{ Str::limit(strip_tags($news->content), 120) }}</p>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none fw-bold text-danger d-inline-flex align-items-center gap-1 mt-auto">
                                Seguir Leyendo <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-5">
                <a href="{{ route('news.index') }}" class="btn btn-outline-primary rounded-pill px-5 py-3 fw-bold border-2"><i class="bi bi-grid-fill me-2"></i>Ver todas las novedades</a>
            </div>
            @else
            <div class="text-center p-5 rounded-4 bg-white border border-dashed text-muted">
                <i class="bi bi-mailbox fs-1 text-muted mb-3 d-block"></i>
                <p class="mb-0">Próximamente se publicará la cartelera informativa.</p>
            </div>
            @endif
        </section>

        <!-- AUTORIDADES -->
        <section id="autoridades" class="py-5">
            <div class="text-center mb-5">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold mb-2">AUTORIDADES</span>
                <h2 class="display-5 fw-bold text-dark">Comisión Directiva</h2>
                <p class="text-muted max-width-600 mx-auto">Profesionales elegidos para liderar el gobierno institucional y guiar la matriculación en toda la jurisdicción.</p>
            </div>
            
            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <div class="mb-5 bg-white p-5 rounded-4 shadow-sm border border-light" style="border-radius: 24px;">
                        <h4 class="text-center fw-bold text-primary mb-5 position-relative d-inline-block w-100 font-display">
                            {{ $department }}
                            <span class="d-block bg-danger mx-auto mt-2 rounded" style="width: 50px; height: 3px;"></span>
                        </h4>
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
                            <div class="org-member-card mb-4" style="border-top: 6px solid var(--ts-secondary);">
                                @php
                                    $presImageUrl = $president->collegiate && $president->collegiate->avatar_url ? $president->collegiate->avatar_url : $president->image_path;
                                    $presName = $president->collegiate ? $president->collegiate->first_name . ' ' . $president->collegiate->last_name : $president->name;
                                @endphp
                                <img src="{{ $presImageUrl }}" class="org-member-photo" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($presName) }}&background=1E3A8A&color=fff'">
                                <h5 class="fw-bold mb-1 text-dark">{{ $presName }}</h5>
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 mt-1 small">{{ $president->role }}</span>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-3">
                                @foreach($others as $m)
                                <div class="org-member-card">
                                    @php
                                        $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                        $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                                    @endphp
                                    <img src="{{ $mImageUrl }}" class="org-member-photo" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background=0F172A&color=fff'">
                                    <h6 class="fw-bold mb-1 text-dark">{{ $mName }}</h6>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-1 mt-1 small">{{ $m->role }}</span>
                                    @if($m->is_substitute) <small class="text-danger fw-bold d-block mt-2">Suplente</small> @endif
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
        </section>

        <!-- CONTACTO & MAPA -->
        <section id="contacto" class="row g-4 mb-5 bg-white p-4 p-md-5 rounded-4 shadow-sm border border-light" style="border-radius: 24px;">
            <div class="col-lg-5">
                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold mb-2">CANALES</span>
                <h2 class="display-6 fw-bold text-dark mb-4">Contacto Directo</h2>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center d-flex align-items-center justify-content-center text-primary" style="width: 56px; height: 56px;">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold">Sede Física</small>
                        <strong class="text-dark">{{ $school->address ?? 'Dirección no configurada' }}</strong>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center d-flex align-items-center justify-content-center text-primary" style="width: 56px; height: 56px;">
                        <i class="bi bi-telephone-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold">Atención Telefónica</small>
                        <strong class="text-dark"><a href="tel:{{ $school->phone }}" class="text-decoration-none text-dark">{{ $school->phone ?? 'No disponible' }}</a></strong>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-light p-3 rounded-circle me-3 text-center d-flex align-items-center justify-content-center text-primary" style="width: 56px; height: 56px;">
                        <i class="bi bi-envelope-fill fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold">Correo Institucional</small>
                        <strong class="text-dark"><a href="mailto:{{ $school->email }}" class="text-decoration-none text-dark">{{ $school->email ?? 'contacto@ejemplo.com' }}</a></strong>
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
                    <div class="rounded-4 overflow-hidden shadow-sm border border-light" style="height: 100%; min-height: 350px; border-radius: 20px;">
                        {!! $school->map_embed_code !!}
                    </div>
                @elseif($mapQuery)
                    <div class="rounded-4 overflow-hidden shadow-sm border border-light" style="height: 100%; min-height: 350px; border-radius: 20px;">
                        <iframe width="100%" height="100%" style="border:0; min-height: 350px;" loading="lazy" allowfullscreen 
                            src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                        </iframe>
                    </div>
                @else
                    <div class="bg-light rounded-4 d-flex flex-column align-items-center justify-content-center border border-light" style="height: 350px; border-radius: 20px;">
                        <i class="bi bi-map text-muted fs-1 mb-2"></i>
                        <span class="text-muted">Mapa de ubicación no disponible</span>
                    </div>
                @endif
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer class="footer-premium">
        <div class="container-fluid px-5">
            <div class="row align-items-center g-4 border-bottom border-secondary-subtle pb-5 mb-4">
                <div class="col-md-6 text-center text-md-start">
                    @if(isset($school) && $school->logo)
                        <img src="{{ asset($school->logo) }}" alt="Logo" class="footer-logo mb-3">
                    @endif
                    <h4 class="text-white fw-bold">{{ $school->name }}</h4>
                    <p class="mb-0 text-white-50">Garantizando la ética y la excelencia en el ejercicio del Trabajo Social.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex gap-3 justify-content-center justify-content-md-end mb-3">
                        @if($school->facebook_url)
                            <a href="{{ $school->facebook_url }}" target="_blank" class="text-white fs-4"><i class="bi bi-facebook"></i></a>
                        @endif
                        @if($school->instagram_url)
                            <a href="{{ $school->instagram_url }}" target="_blank" class="text-white fs-4"><i class="bi bi-instagram"></i></a>
                        @endif
                        @if($school->twitter_url)
                            <a href="{{ $school->twitter_url }}" target="_blank" class="text-white fs-4"><i class="bi bi-twitter"></i></a>
                        @endif
                    </div>
                    <span class="text-white-50 d-block">La Rioja, Argentina</span>
                </div>
            </div>
            <div class="text-center">
                <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} Graficar Software de Mario Rojas. Todos los derechos reservados.</p>
            </div>
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
                        Hola 👋 Soy el asistente virtual del {{ $school->name ?? 'Consejo' }}. ¿En qué te puedo ayudar hoy?
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <form id="chatbot-form" class="d-flex gap-2" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatbot-input" class="form-control rounded-pill bg-light border-0 px-3" placeholder="Escribe tu consulta..." required>
                    <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; padding:0;">
                        <i class="bi bi-send-fill text-white fs-6"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <button id="chatbot-trigger" class="btn btn-light border border-2 border-primary rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center p-0" style="bottom: 25px; right: 25px; z-index: 1040; width: 95px; height: 95px; background-color: white !important; overflow: hidden;" onclick="toggleChatbot()">
        <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" style="width: 100%; height: 100%; object-fit: cover;">
    </button>

    <!-- SCRIPT CHATBOT -->
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

    <!-- MODALES INTERACTIVOS PREMIUM -->
    
    <!-- Modal 1: Padrón de Trabajadores Sociales -->
    <div class="modal fade" id="modalTrabajadoresSociales" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display d-flex align-items-center gap-2"><i class="bi bi-people-fill text-danger"></i> Padrón General de Matriculados</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    <div class="input-group mb-4 shadow-sm rounded-pill overflow-hidden border bg-white p-1">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-0 shadow-none ps-1 py-2" id="searchTrabajadoresSociales" placeholder="Buscar por nombre, matrícula o DNI...">
                    </div>
                    <div class="list-group list-group-flush rounded-4 border shadow-sm bg-white overflow-hidden" id="listTrabajadoresSociales" style="max-height: 400px; overflow-y: auto;">
                        @if(isset($collegiates) && $collegiates->count() > 0)
                            @foreach($collegiates as $colegiado)
                                <div class="list-group-item list-group-item-premium trabajador-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark t-name">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                            <div class="small text-muted">
                                                <span class="me-3 t-mat"><i class="bi bi-card-text me-1"></i> MP: {{ $colegiado->registration_number }}</span>
                                                <span class="t-dni"><i class="bi bi-person-vcard me-1"></i> DNI: {{ $colegiado->dni }}</span>
                                            </div>
                                        </div>
                                        @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                            <span class="badge bg-success rounded-pill px-3 py-2">Activo</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $colegiado->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                                No se encontraron colegiados registrados en el padrón.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Años Trayectoria -->
    <div class="modal fade" id="modalAnios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display"><i class="bi bi-award-fill text-danger me-2"></i>Nuestra Trayectoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium p-5 text-center bg-white">
                    <div class="display-1 text-danger mb-4"><i class="bi bi-calendar2-heart-fill"></i></div>
                    <h3 class="fw-bold text-dark mb-3">{{ \Carbon\Carbon::parse('2009-08-11')->age }} Años de Trayectoria</h3>
                    <p class="text-muted leading-relaxed mb-0 text-start">
                        El Consejo Profesional de Trabajo Social de La Rioja fue creado formalmente en <strong>agosto de 2009</strong>, mediante la sanción y posterior publicación de la <strong>Ley Provincial Nº 8.522</strong> (publicada en el Boletín Oficial el <strong>11 de agosto de 2009</strong>). 
                    </p>
                    <p class="text-muted leading-relaxed mt-3 text-start">
                        Esta legislación le otorgó el carácter de Persona Jurídica de Derecho Público para fiscalizar el correcto ejercicio de la profesión, centralizar las matrículas y velar por el código de ética en todo el territorio provincial. Desde entonces, trabajamos incansablemente por jerarquizar el rol del trabajador social.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Departamentos (Acordeón Real) -->
    <div class="modal fade" id="modalDepartamentos" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display d-flex align-items-center gap-2"><i class="bi bi-geo-alt-fill text-danger"></i> Distribución por Departamentos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    <p class="text-muted mb-4 small fw-bold">Listado en vivo de profesionales matriculados registrados según su delegación o departamento en la provincia de La Rioja.</p>
                    <div class="accordion accordion-premium" id="accordionDeptos">
                        @php
                            $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martin', 'Independencia', 'Rosario Vera Penaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela'];
                        @endphp
                        @foreach($departamentos as $index => $depto)
                            @php
                                $deptCollegiates = isset($collegiates) ? $collegiates->where('city', $depto) : collect();
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#depto{{ $index }}" aria-expanded="false" aria-controls="depto{{ $index }}">
                                        {{ $depto }}
                                        <span class="badge ms-3 rounded-pill text-white" style="background: var(--ts-primary); font-size: 0.8rem;">{{ $deptCollegiates->count() }}</span>
                                    </button>
                                </h2>
                                <div id="depto{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionDeptos">
                                    <div class="accordion-body bg-light p-0">
                                        @if($deptCollegiates->count() > 0)
                                            <div class="list-group list-group-flush bg-white">
                                                @foreach($deptCollegiates as $colegiado)
                                                    <div class="list-group-item list-group-item-premium">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                 <h6 class="mb-1 fw-bold text-dark">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                                                 <div class="small text-muted">
                                                                     <span class="me-3"><i class="bi bi-card-text me-1"></i> MP: {{ $colegiado->registration_number }}</span>
                                                                     <span><i class="bi bi-person-vcard me-1"></i> DNI: {{ $colegiado->dni }}</span>
                                                                 </div>
                                                            </div>
                                                            @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                                                <span class="badge bg-success rounded-pill px-3 py-1">Activo</span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill px-3 py-1">{{ $colegiado->status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4 small">
                                                <i class="bi bi-info-circle fs-4 d-block mb-2 text-primary"></i> 
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
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display"><i class="bi bi-handshake-fill text-danger me-2"></i> Convenios Comerciales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    @if(isset($agreements) && $agreements->count() > 0)
                        <div class="row g-3">
                            @foreach($agreements as $agreement)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 bg-white border border-light">
                                        <div class="card-body p-4 d-flex flex-column text-center">
                                            <div class="mb-3 mx-auto">
                                                @if($agreement->logo_url)
                                                    <img src="{{ asset($agreement->logo_url) }}" alt="{{ $agreement->name }}" class="img-fluid rounded" style="max-height: 80px; object-fit: contain;">
                                                @else
                                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto text-primary" style="width: 80px; height: 80px;">
                                                        <i class="bi bi-shop fs-1"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <h5 class="fw-bold mb-1 text-dark">{{ $agreement->name }}</h5>
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
                        <div class="text-center p-5 rounded-4 bg-white border border-dashed text-muted">
                            <i class="bi bi-gift fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="fw-bold">Aún no hay convenios comerciales activos</h5>
                            <p class="small mb-0">Próximamente se publicarán los beneficios vigentes para matriculados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modales de Servicios Rápidos -->
    
    <!-- Modal Matrícula Habilitante (AJAX) -->
    <div class="modal fade" id="modalMatricula" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display"><i class="bi bi-shield-check text-danger me-2"></i> Matrícula Habilitante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    <p class="text-muted small mb-4">Validá la habilitación legal de cualquier profesional en Trabajo Social de nuestra jurisdicción ingresando su DNI, Matrícula o Nombre completo.</p>
                    
                    <form id="formValidarMatricula" onsubmit="event.preventDefault(); validarMatricula();">
                        <div class="input-group mb-3 shadow-sm rounded-pill overflow-hidden border bg-white p-1">
                            <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="inputMatriculaSearch" class="form-control border-0 shadow-none ps-1" placeholder="Buscar por DNI, Matrícula o Nombre..." required>
                            <button class="btn btn-portal px-4" type="submit" id="btnValidar">Validar</button>
                        </div>
                    </form>
                    
                    <div id="resultadoMatricula" class="mt-4 d-none">
                        <!-- Ajax render -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Trámites -->
    <div class="modal fade" id="modalTramites" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display"><i class="bi bi-file-earmark-text text-danger me-2"></i> Requisitos de Matriculación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    <p class="text-muted small mb-4">A continuación, te detallamos la documentación obligatoria que debés presentar en soporte digital u original para iniciar tu trámite de matriculación profesional:</p>
                    <ul class="list-group list-group-flush rounded-4 overflow-hidden border shadow-sm">
                        <li class="list-group-item bg-white p-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Título Original habilitante de Lic. en Trabajo Social o equivalente (Legalizado y registrado).</li>
                        <li class="list-group-item bg-white p-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Fotocopia color del Documento Nacional de Identidad (Ambas caras).</li>
                        <li class="list-group-item bg-white p-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Constancia de CUIL emitida por ANSES.</li>
                        <li class="list-group-item bg-white p-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Certificado de Reincidencia o Antecedentes Penales vigente.</li>
                        <li class="list-group-item bg-white p-3"><i class="bi bi-check-circle-fill text-success me-2"></i> Dos (2) fotos 4x4 carnet, de frente y fondo claro.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Atención -->
    <div class="modal fade" id="modalAtencion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-content-premium">
                <div class="modal-header modal-header-premium">
                    <h5 class="modal-title fw-bold font-display"><i class="bi bi-headset text-danger me-2"></i> Atención al Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium">
                    <div class="p-4 rounded-4 bg-white border shadow-sm">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clock me-2 text-primary"></i> Horarios de Atención Presencial</h6>
                        <p class="text-secondary small leading-relaxed">
                            Nuestra sede atiende presencialmente de **Lunes a Viernes de 9:00 hs a 13:00 hs**. 
                        </p>
                        <hr class="my-3 text-muted">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-chat-dots me-2 text-primary"></i> Consultas Online / Whatsapp</h6>
                        <p class="text-secondary small leading-relaxed mb-0">
                            Para urgencias o trámites de habilitación rápida, podés contactarnos vía mail o telefónicamente en horario extendido de **9:00 hs a 17:00 hs**.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT VALIDADOR DE MATRÍCULA AJAX -->
    <script>
        function validarMatricula() {
            const query = document.getElementById('inputMatriculaSearch').value.trim();
            const btn = document.getElementById('btnValidar');
            const resContainer = document.getElementById('resultadoMatricula');
            
            if(!query) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
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
                    const msg = col.is_active ? 'El profesional se encuentra habilitado para ejercer la actividad.' : 'El profesional NO se encuentra habilitado en este momento.';

                    resContainer.innerHTML = `
                        <div class="card border-0 shadow-sm rounded-4 bg-white" style="border-left: 5px solid ${col.is_active ? '#198754' : '#dc3545'} !important;">
                            <div class="card-body p-4 text-center">
                                <i class="bi ${iconClass} mb-2" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold text-dark mb-1">${col.name}</h5>
                                <p class="text-muted mb-3 small">DNI: ${col.document} | Matrícula: ${col.registration}</p>
                                <span class="badge ${badgeClass} px-4 py-2 fs-6 mb-3 rounded-pill">${col.status}</span>
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

    <!-- SCRIPT DE BUSCADOR DE PADRÓN GENERAL -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar scrolled class
            const navbar = document.querySelector('.navbar-ts');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Live Search padrón
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

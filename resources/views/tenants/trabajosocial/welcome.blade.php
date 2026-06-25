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
            --ts-primary: #1e3a8a; /* Azul Profundo / Institucional */
            --ts-secondary: #059669; /* Esmeralda / Compromiso */
            --ts-light: #f8fafc; /* Gris Slate muy suave */
            --ts-dark: #0f172a;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ts-dark);
            background-color: var(--ts-light);
        }

        h1, h2, h3, h4, h5, h6, .playfair {
            font-family: 'Montserrat', sans-serif;
        }

        /* NAVBAR */
        .navbar-ts {
            background-color: rgba(253, 250, 246, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(142, 68, 173, 0.2);
        }
        .navbar-ts .navbar-brand {
            color: var(--ts-primary) !important;
            font-weight: 700;
        }
        .btn-ts-nav {
            background-color: var(--ts-secondary);
            color: #fff;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(230, 126, 34, 0.3);
            text-decoration: none;
            transition: 0.3s;
        }
        .btn-ts-nav:hover {
            background-color: #d35400;
            color: #fff;
            transform: translateY(-2px);
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1529156069898-49953eb1f5bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-ts {
            padding: 180px 0 100px;
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.92) 0%, rgba(248, 250, 252, 0.98) 100%), url('{{ $bgImage }}');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .hero-ts h1 {
            font-size: 4rem;
            color: var(--ts-primary);
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }
        .hero-ts p {
            font-size: 1.25rem;
            color: #555;
            margin-bottom: 2rem;
        }
        
        /* SECTIONS */
        .section-title {
            color: var(--ts-primary);
            margin-bottom: 3rem;
            text-align: center;
            font-size: 2.5rem;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--ts-secondary);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        /* CARDS */
        .card-ts {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.04);
            padding: 2rem;
            height: 100%;
            transition: 0.3s ease;
        }
        .card-ts:hover {
            box-shadow: 0 20px 45px rgba(142, 68, 173, 0.1);
        }
        .card-ts img {
            border-radius: 15px;
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 1.5rem;
        }

        /* ORG CHART */
        .org-ts-node {
            background: #fff;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 250px;
            border-top: 5px solid var(--ts-primary);
        }
        .org-ts-node img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.2);
        }
        
        .footer-ts {
            background-color: var(--ts-primary);
            color: #fff;
            padding: 4rem 0;
            text-align: center;
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
        <section class="p-0 position-relative" style="height: 100vh; overflow: hidden; background-color: #000;">
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
        </section>
    @else
        <!-- SIN SLIDER: Muestra el diseño tradicional azul con texto -->
        <section class="hero-ts">
            <div class="container-fluid px-4 px-xl-5 position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="playfair">Empatía, <br>Derechos & <br>Comunidad.</h1>
                        <p>Órgano oficial que agrupa, regula y defiende a los profesionales del Trabajo Social, velando por la ética y el compromiso social.</p>
                    </div>
                    <div class="col-lg-6 text-center">
                        @if(isset($school) && $school->logo)
                            <img src="{{ asset($school->logo) }}" alt="Logo Gigante" class="img-fluid opacity-75" style="max-height: 350px; filter: drop-shadow(0 20px 30px rgba(142,68,173,0.2));">
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="container-fluid px-4 px-xl-5 py-5">
        
        <!-- STATS & SERVICES -->
        <div class="row g-4 mb-5 pb-5 border-bottom pt-4">
            <div class="col-12 mb-4 text-center">
                <div class="row g-3 bg-white p-4 rounded-4 shadow-sm">
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">+{{ $school->collegiates()->count() ?? 0 }}</h2>
                        <p class="text-muted small mt-2 fw-bold">Profesionales Matriculados</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">20+</h2>
                        <p class="text-muted small mt-2 fw-bold">Años de Trayectoria</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">18</h2>
                        <p class="text-muted small mt-2 fw-bold">Localidades</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 playfair" style="color: var(--ts-primary);">12</h2>
                        <p class="text-muted small mt-2 fw-bold">Convenios</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--ts-secondary);">badge</span>
                    <h4 class="playfair mb-2" style="font-size: 1.2rem;">Matrícula Habilitante</h4>
                    <p class="text-muted small mb-0">Verificá la habilitación legal de cualquier profesional.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--ts-secondary);">assignment</span>
                    <h4 class="playfair mb-2" style="font-size: 1.2rem;">Trámites</h4>
                    <p class="text-muted small mb-0">Información para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ts p-4 text-center shadow-sm">
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
                    $aboutImage = isset($slider) && $slider->items->count() > 1 ? $slider->items[1]->image_url : 'https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80';
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

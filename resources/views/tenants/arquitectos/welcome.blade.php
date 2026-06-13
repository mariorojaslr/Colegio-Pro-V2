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
            --accent: #f39c12; /* Naranja/Dorado constructivo */
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
            border-bottom: 2px solid var(--accent);
            padding: 1rem 0;
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
            background-image: linear-gradient(rgba(15, 15, 15, 0.8), rgba(15, 15, 15, 0.8)), url('{{ $bgImage }}');
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
        }
        .hero-arq p {
            font-size: 1.2rem;
            color: #ccc;
            font-weight: 300;
            letter-spacing: 1px;
            max-width: 600px;
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
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-arq fixed-top">
        <div class="container d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 100px;">
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
    <section class="hero-arq">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>DISEÑO,<br><span style="color: var(--accent);">VANGUARDIA</span><br>& ÉTICA.</h1>
                    <p>Órgano oficial de regulación profesional. Defendiendo las incumbencias y promoviendo la excelencia arquitectónica en nuestra jurisdicción.</p>
                </div>
            </div>
        </div>
    </section>

    <main class="container py-5" style="margin-top: -50px; position: relative; z-index: 10;">
        
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
                            <div class="bg-dark d-flex align-items-center justify-content-center card-arq-img">
                                <span class="material-icons text-white" style="font-size: 3rem;">newspaper</span>
                            </div>
                        @endif
                        <div class="card-arq-body">
                            <small class="text-muted fw-bold">{{ $news->published_at->format('d M, Y') }}</small>
                            <h4 class="mt-2 mb-3" style="font-size: 1.2rem;">{{ $news->title }}</h4>
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
                                <img src="{{ $president->image_path }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($president->name) }}&background=000&color=fff'">
                                <h6 class="oswald mb-1">{{ $president->name }}</h6>
                                <small style="color: var(--accent); font-weight: 600;">{{ $president->role }}</small>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="d-flex flex-wrap justify-content-center gap-4">
                                @foreach($others as $m)
                                <div class="node-arq">
                                    <h6 class="oswald mb-1">{{ $m->name }}</h6>
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
                @if($school->map_embed_code)
                    <div style="filter: grayscale(100%) contrast(120%); border: 1px solid #ddd;">
                        {!! $school->map_embed_code !!}
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
        <div class="container text-center">
            <h4 class="oswald mb-3">{{ $school->name }}</h4>
            <p class="text-white-50 mb-4 small" style="letter-spacing: 1px;">DISEÑO • ÉTICA • PROFESIONALISMO</p>
            <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} Desarrollado por Gente Piola.</p>
        </div>
    </footer>

</body>
</html>

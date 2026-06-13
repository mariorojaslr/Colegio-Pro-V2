<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'SaaS Demo' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Fuente Tech/Clean -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-main: #10b981; /* Esmeralda */
            --brand-dark: #0f172a; /* Slate 900 */
            --brand-light: #f8fafc; /* Slate 50 */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #334155;
            background-color: var(--brand-light);
        }

        h1, h2, h3, .grotesk {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* NAVBAR */
        .navbar-tech {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-tech {
            padding: 150px 0 100px;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.1), transparent), 
                        url('{{ $bgImage }}');
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
        }

        .btn-tech {
            background-color: var(--brand-main);
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        .btn-tech:hover {
            background-color: #059669;
            color: #fff;
            transform: translateY(-2px);
        }

        /* CARDS */
        .tech-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 30px;
            transition: 0.3s;
            height: 100%;
        }
        .tech-card:hover {
            border-color: var(--brand-main);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .node-tech {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
            width: 220px;
        }
        .node-tech img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-tech fixed-top">
        <div class="container d-flex align-items-center justify-content-between py-2">
            <a class="navbar-brand grotesk fw-bold d-flex align-items-center gap-2" href="/" style="color: var(--brand-dark);">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 80px;">
                @else
                    <span class="material-icons" style="color: var(--brand-main);">cloud_done</span>
                @endif
                {{ $school->name ?? 'Demo SaaS' }}
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#demoNav">
                <span class="material-icons" style="color: var(--brand-dark);">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="demoNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#quienes-somos">Características</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#autoridades">Autoridades</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn-tech">
                        Acceder <span class="material-icons" style="font-size: 1.2rem;">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-tech">
        <div class="container text-center">
            <span class="badge bg-light text-success border border-success mb-3 px-3 py-2 rounded-pill">Plataforma V2 Activa</span>
            <h1 class="display-4 fw-bold mb-4" style="color: var(--brand-dark);">Tu organización,<br>en la nube.</h1>
            <p class="fs-5 mb-5 mx-auto" style="max-width: 600px; color: #64748b;">
                Gestión inteligente para colegios profesionales. Matrículas, cobros, noticias y portal de colegiados en un solo lugar.
            </p>
        </div>
    </section>

    <main class="container py-5">
        
        <!-- CARACTERÍSTICAS -->
        <div id="quienes-somos" class="row g-4 mb-5 pb-5 border-bottom pt-5">
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">speed</span>
                    <h4 class="grotesk fw-bold">Gestión Ágil</h4>
                    <p class="text-muted small">Automatizá la revisión de legajos y el pago de cuotas mensuales de forma simple.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">verified_user</span>
                    <h4 class="grotesk fw-bold">Seguridad Total</h4>
                    <p class="text-muted small">Validación de certificados por código QR y perfiles con auditoría completa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">devices</span>
                    <h4 class="grotesk fw-bold">Multi-dispositivo</h4>
                    <p class="text-muted small">Tus matriculados pueden acceder desde cualquier lugar con un diseño responsivo y PWA.</p>
                </div>
            </div>
        </div>

        <!-- NOTICIAS -->
        <div id="novedades" class="mb-5 pb-5 border-bottom pt-5">
            <h2 class="grotesk fw-bold mb-4" style="color: var(--brand-dark);">Últimos Updates</h2>
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="tech-card p-0 overflow-hidden">
                        @if($news->image_path)
                            <img src="{{ asset($news->image_path) }}" class="w-100" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="w-100 bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <span class="material-icons text-muted" style="font-size: 3rem;">article</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h5 class="fw-bold">{{ $news->title }}</h5>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none fw-bold mt-3 d-inline-block" style="color: var(--brand-main);">Ver detalles &rarr;</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-light border text-center p-4">
                <p class="mb-0 text-muted">No hay actualizaciones en el sistema de noticias.</p>
            </div>
            @endif
        </div>

        <!-- AUTORIDADES -->
        <div id="autoridades" class="mb-5 pt-5">
            <h2 class="grotesk fw-bold mb-4 text-center" style="color: var(--brand-dark);">Equipo Demo</h2>
            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <h5 class="text-center text-muted mb-4 mt-5">{{ $department }}</h5>
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        @foreach($members as $m)
                        <div class="node-tech">
                            <img src="{{ $m->image_path }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=10b981&color=fff'">
                            <h6 class="fw-bold mb-1">{{ $m->name }}</h6>
                            <small class="text-muted">{{ $m->role }}</small>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="alert alert-light border text-center p-4">
                    <p class="mb-0 text-muted">Aún no hay autoridades configuradas para esta instancia.</p>
                </div>
            @endif
        </div>
    </main>

    <footer class="py-5 border-top" style="background-color: #fff;">
        <div class="container text-center">
            <p class="text-muted mb-0">Demostración Multi-tenant SaaS &copy; {{ date('Y') }}</p>
        </div>
    </footer>

</body>
</html>

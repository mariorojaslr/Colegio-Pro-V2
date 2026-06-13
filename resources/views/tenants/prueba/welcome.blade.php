<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio de Prueba' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #10B981; /* Emerald Green */
            --dark-bg: #0f172a; /* Slate 900 */
            --dark-card: #1e293b; /* Slate 800 */
            --text-light: #f8fafc; /* Slate 50 */
            --text-muted: #94a3b8; /* Slate 400 */
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar Custom */
        .navbar-custom {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Hero Custom */
        .hero-section {
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 50%),
                        radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.1), transparent 50%);
        }

        .btn-emerald {
            background-color: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }

        .btn-emerald:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
            color: #fff;
        }

        .glass-card {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .huge-title {
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(to right, #10B981, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }

        .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            padding: 3px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 50px;">
                @else
                    <span class="material-icons text-success" style="font-size: 2.5rem;">memory</span>
                @endif
                <span class="fw-bold fs-4">{{ $school->name ?? 'Empresa de Prueba' }}</span>
            </a>
            
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn-emerald text-decoration-none d-flex align-items-center gap-2">
                    <span class="material-icons">terminal</span> Portal de Acceso
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-2 rounded-pill mb-3 border border-success border-opacity-25">
                        Versión 2.0.0
                    </span>
                    <h1 class="huge-title mb-4">La nueva era de la regulación digital.</h1>
                    <p class="lead text-muted mb-5">
                        Somos el órgano oficial que nuclea a los profesionales en sistemas. Diseñado exclusivamente para demostrar el poder del diseño único por empresa (Tenant-Specific Overrides).
                    </p>
                    <div class="d-flex gap-3">
                        <button class="btn-emerald">Conoce más</button>
                        <button class="btn btn-outline-light rounded-pill px-4">Ver Autoridades</button>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                    <!-- Imagen decorativa tecnológica -->
                    <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Tech" class="img-fluid rounded-4 shadow-lg" style="border: 2px solid rgba(255,255,255,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICIOS -->
    <section class="py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="fs-1 fw-bold">Nuestros Servicios Exclusivos</h2>
                <p class="text-muted">Diseño estructurado según las necesidades de esta empresa.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="glass-card text-center h-100">
                        <span class="material-icons text-success mb-3" style="font-size: 3rem;">dns</span>
                        <h4 class="fw-bold">Infraestructura</h4>
                        <p class="text-muted mb-0">Esta tarjeta es un ejemplo de contenido que solo existe en esta empresa.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card text-center h-100">
                        <span class="material-icons text-success mb-3" style="font-size: 3rem;">code</span>
                        <h4 class="fw-bold">API Pública</h4>
                        <p class="text-muted mb-0">Contenido exclusivo programado a mano para el subdominio de prueba.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="glass-card text-center h-100">
                        <span class="material-icons text-success mb-3" style="font-size: 3rem;">security</span>
                        <h4 class="fw-bold">Ciberseguridad</h4>
                        <p class="text-muted mb-0">Total aislamiento de datos y código fuente entre empresas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AUTORIDADES -->
    <section class="py-5" style="background-color: var(--dark-card);">
        <div class="container py-5">
            <h2 class="fs-1 fw-bold text-center mb-5">Nuestros Pioneros</h2>
            <div class="row justify-content-center g-4">
                @if(isset($boardMembers) && count($boardMembers) > 0)
                    @foreach($boardMembers as $dept => $members)
                        @foreach($members as $m)
                        <div class="col-md-3 text-center">
                            <div class="mb-3">
                                <img src="{{ $m->image_path }}" class="avatar-img shadow-lg" alt="{{ $m->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=10B981&color=fff'">
                            </div>
                            <h5 class="fw-bold mb-1">{{ $m->name }}</h5>
                            <p class="text-success small mb-0 fw-bold">{{ $m->role }}</p>
                        </div>
                        @endforeach
                    @endforeach
                @else
                    <p class="text-center text-muted">Aún no hay autoridades cargadas.</p>
                @endif
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ $school->name ?? 'Empresa' }}. Implementación de Vistas por Inquilino demostrada con éxito.</p>
        </div>
    </footer>

</body>
</html>

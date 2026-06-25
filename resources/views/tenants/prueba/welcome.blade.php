<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio Profesional' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: {{ $school->primary_color ?? '#4f46e5' }};
            --secondary: {{ $school->secondary_color ?? '#06b6d4' }};
            --dark: #0f172a;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            background-color: var(--light);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* NAVBAR */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar-brand img {
            height: 45px;
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            margin: 0 10px;
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        
        .btn-custom-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white !important;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        .btn-custom-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        /* HERO SECTION - NO SLIDER */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: radial-gradient(circle at top right, rgba(255,255,255,0.9), rgba(248,250,252,0.95)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80') center/cover fixed;
            position: relative;
            padding-top: 80px;
        }
        .hero-shape {
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
            opacity: 0.1;
            z-index: 0;
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .hero-badge {
            display: inline-block;
            padding: 8px 16px;
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            color: var(--dark);
            margin-bottom: 25px;
        }
        .hero-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-text {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 40px;
            max-width: 600px;
            line-height: 1.6;
        }

        /* FEATURES */
        .features-section {
            padding: 100px 0;
            background-color: white;
            position: relative;
        }
        .feature-card {
            padding: 40px;
            border-radius: 24px;
            background: var(--light);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            background: white;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border-color: rgba(79, 70, 229, 0.1);
        }
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 25px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(6, 182, 212, 0.1));
            color: var(--primary);
        }

        /* INFO BANNER */
        .info-banner {
            background: linear-gradient(135deg, var(--dark), #1e293b);
            border-radius: 30px;
            padding: 60px;
            margin: 100px 0;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        .info-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: pulse 15s infinite linear;
        }

        /* STATS */
        .stat-item {
            text-align: center;
            padding: 30px;
        }
        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        .stat-label {
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        /* FOOTER */
        footer {
            background-color: var(--light);
            padding: 80px 0 40px;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 2.8rem; }
            .info-banner { padding: 40px 20px; border-radius: 20px; }
            .stat-number { font-size: 2.5rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand" href="#">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo Institucional">
                @else
                    <h3 class="m-0 font-heading fw-bold" style="color: var(--primary);">ColegioPro</h3>
                @endif
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list fs-1 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-custom-login">
                        <i class="bi bi-person-circle me-2"></i> Portal Colegiados
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="inicio" class="hero-section">
        <div class="hero-shape"></div>
        <div class="container-fluid px-4 px-lg-5 hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-5">
                    <span class="hero-badge"><i class="bi bi-shield-check me-1"></i> Excelencia Profesional</span>
                    <h1 class="hero-title">Impulsamos tu <span>Desarrollo Institucional</span></h1>
                    <p class="hero-text">Somos la entidad líder que respalda, agrupa y potencia a los profesionales. Accede a tu portal exclusivo y gestiona todos tus trámites de manera 100% digital, rápida y segura.</p>
                    
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('login') }}" class="btn btn-custom-login btn-lg px-5 py-3">Ingresar al Sistema <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="#servicios" class="btn btn-outline-secondary btn-lg px-4 py-3 rounded-pill fw-bold bg-white">Conoce Más</a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6 offset-xl-1 d-none d-lg-block">
                    <!-- Abstract Modern Composition instead of slider -->
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Profesionales" class="img-fluid rounded-4 shadow-lg" style="border: 8px solid white;">
                        <div class="position-absolute bottom-0 start-0 translate-middle-x mb-5 bg-white p-4 rounded-4 shadow-lg text-center" style="z-index: 2; border-bottom: 4px solid var(--secondary);">
                            <h3 class="fw-bold mb-0 text-dark">100%</h3>
                            <span class="small text-muted fw-bold text-uppercase">Gestión Digital</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="py-5 bg-white border-bottom">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row justify-content-center">
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number">2.5K+</div>
                    <div class="stat-label">Profesionales Activos</div>
                </div>
                <div class="col-md-3 col-6 stat-item border-start">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Cursos Anuales</div>
                </div>
                <div class="col-md-3 col-6 stat-item border-start">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Trámites Online</div>
                </div>
                <div class="col-md-3 col-6 stat-item border-start">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Soporte Técnico</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="servicios" class="features-section">
        <div class="container-fluid px-4 px-lg-5">
            <div class="text-center mb-5 pb-3">
                <span class="text-uppercase fw-bold" style="color: var(--secondary); letter-spacing: 2px;">Nuestros Servicios</span>
                <h2 class="display-5 fw-bold text-dark mt-2">Todo lo que necesitas en un solo lugar</h2>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-laptop"></i></div>
                        <h4 class="fw-bold mb-3">Oficina Virtual</h4>
                        <p class="text-muted mb-0">Gestiona tus cuotas, descarga certificados y actualiza tu legajo sin moverte de tu casa u oficina, disponible las 24 horas.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-mortarboard"></i></div>
                        <h4 class="fw-bold mb-3">Capacitación Continua</h4>
                        <p class="text-muted mb-0">Accede a una amplia oferta de cursos, diplomaturas y seminarios dictados por expertos, con inscripción y certificación automática.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-file-earmark-check"></i></div>
                        <h4 class="fw-bold mb-3">Certificación Ética</h4>
                        <p class="text-muted mb-0">Obtén tus certificados de habilitación profesional y libre deuda con un solo clic, firmados digitalmente para validación instantánea.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFO BANNER -->
    <section id="beneficios" class="container-fluid px-4 px-lg-5">
        <div class="info-banner">
            <div class="row align-items-center position-relative" style="z-index: 1;">
                <div class="col-lg-8">
                    <h2 class="display-4 fw-bold mb-4">La revolución en la gestión institucional ha llegado.</h2>
                    <p class="fs-5 opacity-75 mb-0">Únete a la plataforma tecnológica más avanzada diseñada exclusivamente para colegios y consejos profesionales.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-dark shadow">Ingresar al Portal</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contacto">
        <div class="container-fluid px-4 px-lg-5">
            <div class="row gy-4">
                <div class="col-lg-4">
                    @if(isset($school) && $school->logo)
                        <img src="{{ asset($school->logo) }}" alt="Logo" height="50" class="mb-4">
                    @endif
                    <p class="text-muted pe-lg-5">Modernizamos y elevamos el estándar de la gestión profesional, brindando herramientas de última generación para instituciones de vanguardia.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4 text-dark">Enlaces Útiles</h5>
                    <ul class="list-unstyled mb-0 lh-lg text-muted">
                        <li><a href="#" class="text-decoration-none text-muted hover-primary">Marco Institucional</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-primary">Código de Ética</a></li>
                        <li><a href="#" class="text-decoration-none text-muted hover-primary">Reglamento Interno</a></li>
                        <li><a href="{{ route('login') }}" class="text-decoration-none text-muted hover-primary">Portal Autogestión</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-4 text-dark">Contacto</h5>
                    <ul class="list-unstyled mb-0 lh-lg text-muted">
                        <li><i class="bi bi-geo-alt me-2 text-primary"></i> Av. Tecnológica 1234, Piso 5</li>
                        <li><i class="bi bi-envelope me-2 text-primary"></i> info@colegioprofesional.demo</li>
                        <li><i class="bi bi-telephone me-2 text-primary"></i> 0800-333-PROFE (7763)</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-secondary opacity-10">
            <div class="text-center text-muted small fw-medium">
                &copy; {{ date('Y') }} {{ $school->name ?? 'Colegio Profesional' }}. Desarrollado con tecnología de vanguardia.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Efecto para la navbar al scrollear
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.08)';
                navbar.style.padding = '10px 0';
            } else {
                navbar.style.boxShadow = 'none';
                navbar.style.padding = '15px 0';
            }
        });
    </script>
</body>
</html>

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
    </style>
</head>
<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg bg-theme-card border-bottom border-theme sticky-top shadow-sm py-3">
        <div class="container">
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
            
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn bg-theme-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2">
                    <span class="material-icons" style="font-size: 1.2rem;">login</span> Ingresar
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    @php
        $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : asset('image.png');
    @endphp
    <section class="hero-bg" style="background-image: url('{{ $bgImage }}');">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center py-5">
            <p class="text-white fw-bold mb-3 text-uppercase tracking-wider small opacity-75">Órgano oficial de regulación profesional</p>
            <h1 class="display-4 fw-bold text-white mb-4 shadow-sm">{{ $school->name ?? 'Bienvenido' }}</h1>
            <p class="lead text-white mx-auto" style="max-width: 800px; opacity: 0.9;">
                Organismo encargado de habilitar, regular y fiscalizar el ejercicio ético y legal de la profesión en toda la jurisdicción.
            </p>
        </div>
    </section>

    <!-- STATS -->
    <section class="bg-theme-card py-5 border-bottom border-theme">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-theme-primary mb-0">+350</h2>
                    <p class="text-theme-secondary small mt-2 fw-semibold">Profesionales Matriculados</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-theme-primary mb-0">20+</h2>
                    <p class="text-theme-secondary small mt-2 fw-semibold">Años de Trayectoria</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-theme-primary mb-0">18</h2>
                    <p class="text-theme-secondary small mt-2 fw-semibold">Localidades de Cobertura</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-theme-primary mb-0">12</h2>
                    <p class="text-theme-secondary small mt-2 fw-semibold">Convenios Vigentes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="container py-5">
        
        <!-- INSTITUCIONAL -->
        <div class="row align-items-center mb-5 pb-5 border-bottom border-theme">
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
                    // Usamos una segunda imagen del slider si existe, sino un placeholder según el tema
                    $aboutImage = isset($slider) && $slider->items->count() > 1 
                                ? $slider->items[1]->image_url 
                                : ($primary == '#10B981' 
                                    ? 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' // Imagen corporativa
                                    : 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'); // Imagen médicos
                @endphp
                <img src="{{ $aboutImage }}" alt="Institucional" class="img-fluid rounded-4 shadow-lg border border-theme" style="max-height: 350px; width: 100%; object-fit: cover;">
                <p class="text-muted small mt-2">*(Puedes cambiar esta imagen desde CMS -> Sliders añadiendo una segunda imagen)*</p>
            </div>
        </div>

        <!-- SERVICIOS CARDS -->
        <div class="row g-4 mb-5 pb-5 border-bottom border-theme">
            <div class="col-md-4">
                <div class="card h-100 border-theme rounded-4 p-4 text-center shadow-hover-up bg-theme-card">
                    <div class="mb-3">
                        <span class="material-icons text-theme-primary" style="font-size: 3rem;">badge</span>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Matrícula Habilitante</h4>
                    <p class="text-theme-secondary small mb-0">Verificá la habilitación legal de cualquier profesional en nuestra jurisdicción.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-theme rounded-4 p-4 text-center shadow-hover-up bg-theme-card">
                    <div class="mb-3">
                        <span class="material-icons text-theme-primary" style="font-size: 3rem;">assignment</span>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Trámites y Requisitos</h4>
                    <p class="text-theme-secondary small mb-0">Toda la información necesaria para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-theme rounded-4 p-4 text-center shadow-hover-up bg-theme-card">
                    <div class="mb-3">
                        <span class="material-icons text-theme-primary" style="font-size: 3rem;">support_agent</span>
                    </div>
                    <h4 class="fw-bold text-theme-dark mb-2">Atención al Colegiado</h4>
                    <p class="text-theme-secondary small mb-0">Consultas, trámites presenciales, certificaciones y asistencia administrativa.</p>
                </div>
            </div>
        </div>

        <!-- COMISION DIRECTIVA (ORG CHART) -->
        <div class="mb-5 pb-4">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold text-theme-dark mb-2">Comisión Directiva</h2>
                <p class="text-theme-secondary">Autoridades que guían nuestra institución</p>
            </div>

            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @php
                    $president = null;
                    $others = [];
                    foreach($boardMembers as $dept => $members) {
                        foreach($members as $m) {
                            if(stripos($m->role, 'president') !== false || stripos($m->role, 'director') !== false) {
                                $president = $m;
                            } else {
                                $others[] = $m;
                            }
                        }
                    }
                    if(!$president && count($others) > 0) {
                        $president = array_shift($others);
                    }
                @endphp

                <div class="org-chart-wrapper d-flex flex-column align-items-center">
                    @if($president)
                    <div class="org-chart-node shadow-hover-up" style="width: 280px;">
                        <img src="{{ $president->image_path }}" alt="{{ $president->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($president->name) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                        <h5 class="fw-bold text-theme-dark mb-1">{{ $president->name }}</h5>
                        <p class="text-theme-primary fw-bold small mb-0">{{ $president->role }}</p>
                        <p class="text-muted small mb-0">{{ $president->department }}</p>
                    </div>
                    @endif

                    @if(count($others) > 0)
                    <div class="org-chart-connector"></div>
                    @if(count($others) > 1)
                    <div class="org-line-horizontal" style="max-width: {{ (count($others)-1) * 290 }}px;"></div>
                    @endif

                    <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                        @foreach($others as $m)
                        <div class="org-chart-node shadow-hover-up" style="width: 250px;">
                            <img src="{{ $m->image_path }}" alt="{{ $m->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                            <h5 class="fw-bold text-theme-dark mb-1">{{ $m->name }}</h5>
                            <p class="text-theme-primary fw-bold small mb-0">{{ $m->role }}</p>
                            <p class="text-muted small mb-0">{{ $m->department }}</p>
                            @if($m->is_substitute)
                                <span class="badge bg-secondary mt-2">Suplente</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center p-5 bg-theme-card rounded-4 border border-theme shadow-sm">
                    <span class="material-icons text-muted fs-1 mb-3">groups</span>
                    <p class="text-muted mb-0">La comisión directiva aún no ha sido publicada.</p>
                </div>
            @endif
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
            <p class="text-theme-dark small mb-0">&copy; {{ date('Y') }} Todos los derechos reservados. Desarrollado por <span class="fw-bold">Gente Piola</span>.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

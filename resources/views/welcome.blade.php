<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio-Pro' }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
    <style>
        :root {
            --azul-primario: {{ $school->primary_color ?? '#2980B9' }};
            --azul-sec: {{ $school->secondary_color ?? '#2E86C1' }};
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg bg-azul-card border-bottom border-azul-borde sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="bg-white rounded-4 shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; padding: 5px;">
                    @if(isset($school) && $school->logo)
                        <img src="{{ asset($school->logo) }}" alt="Logo" class="img-fluid">
                    @else
                        <span class="material-icons text-azul-primario">school</span>
                    @endif
                </div>
                <div>
                    <h1 class="h5 mb-0 fw-bold text-azul-marino">{{ $school->name ?? 'Institución' }}</h1>
                    <small class="text-azul-sec d-block" style="font-size: 0.75rem;">Colegio Profesional</small>
                </div>
            </a>
            
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn bg-azul-primario rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2">
                    <span class="material-icons" style="font-size: 1rem;">login</span> Ingresar
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
    <section class="bg-azul-card py-5 border-bottom border-azul-borde">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-azul-primario mb-0">+350</h2>
                    <p class="text-azul-sec small mt-2 fw-semibold">Profesionales Matriculados</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-azul-primario mb-0">20+</h2>
                    <p class="text-azul-sec small mt-2 fw-semibold">Años de Trayectoria</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-azul-primario mb-0">18</h2>
                    <p class="text-azul-sec small mt-2 fw-semibold">Localidades de Cobertura</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold text-azul-primario mb-0">12</h2>
                    <p class="text-azul-sec small mt-2 fw-semibold">Convenios Vigentes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="container py-5">
        
        <!-- INSTITUCIONAL -->
        <div class="row align-items-center mb-5 pb-5 border-bottom border-azul-borde">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="material-icons text-azul-primario fs-1">info</span>
                    <h2 class="h2 fw-bold text-azul-marino mb-0">¿Quiénes somos?</h2>
                </div>
                <div class="bg-white rounded-4 p-4 shadow-sm border border-azul-borde">
                    <p class="text-azul-marino mb-0" style="line-height: 1.8;">
                        El <strong>{{ $school->name }}</strong> es la institución que agrupa y regula a todos los profesionales que ejercen en nuestra jurisdicción. 
                        Somos el organismo encargado de controlar la matrícula profesional, hacer cumplir el código de ética y defender los derechos de los colegiados, fomentando la excelencia y la actualización continua.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Institucional" class="img-fluid rounded-4 shadow-lg border border-azul-borde" style="max-height: 350px; object-fit: cover;">
            </div>
        </div>

        <!-- SERVICIOS CARDS -->
        <div class="row g-4 mb-5 pb-5 border-bottom border-azul-borde">
            <div class="col-md-4">
                <div class="card h-100 border-azul-borde rounded-4 p-4 text-center shadow-hover-up bg-azul-card">
                    <div class="mb-3">
                        <span class="material-icons text-azul-primario" style="font-size: 3rem;">badge</span>
                    </div>
                    <h4 class="fw-bold text-azul-marino mb-2">Matrícula Habilitante</h4>
                    <p class="text-azul-sec small mb-0">Verificá la habilitación legal de cualquier profesional en nuestra jurisdicción.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-azul-borde rounded-4 p-4 text-center shadow-hover-up bg-azul-card">
                    <div class="mb-3">
                        <span class="material-icons text-azul-primario" style="font-size: 3rem;">assignment</span>
                    </div>
                    <h4 class="fw-bold text-azul-marino mb-2">Trámites y Requisitos</h4>
                    <p class="text-azul-sec small mb-0">Toda la información necesaria para iniciar o renovar tu matriculación.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-azul-borde rounded-4 p-4 text-center shadow-hover-up bg-azul-card">
                    <div class="mb-3">
                        <span class="material-icons text-azul-primario" style="font-size: 3rem;">support_agent</span>
                    </div>
                    <h4 class="fw-bold text-azul-marino mb-2">Atención al Colegiado</h4>
                    <p class="text-azul-sec small mb-0">Consultas, trámites presenciales, certificaciones y asistencia administrativa.</p>
                </div>
            </div>
        </div>

        <!-- COMISION DIRECTIVA (ORG CHART) -->
        <div class="mb-5 pb-4">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold text-azul-marino mb-2">Comisión Directiva</h2>
                <p class="text-azul-sec">Autoridades que guían nuestra institución</p>
            </div>

            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @php
                    $president = null;
                    $others = [];
                    foreach($boardMembers as $dept => $members) {
                        foreach($members as $m) {
                            if(stripos($m->role, 'president') !== false) {
                                $president = $m;
                            } else {
                                $others[] = $m;
                            }
                        }
                    }
                    // Si no hay alguien con rol de presidente, tomamos el primero
                    if(!$president && count($others) > 0) {
                        $president = array_shift($others);
                    }
                @endphp

                <div class="org-chart-wrapper d-flex flex-column align-items-center">
                    @if($president)
                    <!-- Nivel 1: Presidente -->
                    <div class="org-chart-node shadow-hover-up" style="width: 280px;">
                        <img src="{{ $president->image_path }}" alt="{{ $president->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($president->name) }}&background=EAF2F8&color=2980B9'">
                        <h5 class="fw-bold text-azul-marino mb-1">{{ $president->name }}</h5>
                        <p class="text-azul-primario fw-bold small mb-0">{{ $president->role }}</p>
                        <p class="text-muted small mb-0">{{ $president->department }}</p>
                    </div>
                    @endif

                    @if(count($others) > 0)
                    <!-- Conector Vertical -->
                    <div class="org-chart-connector"></div>
                    <!-- Línea Horizontal (solo si hay más de 1) -->
                    @if(count($others) > 1)
                    <div class="org-line-horizontal" style="max-width: {{ (count($others)-1) * 290 }}px;"></div>
                    @endif

                    <!-- Nivel 2: Resto de la comisión -->
                    <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                        @foreach($others as $m)
                        <div class="org-chart-node shadow-hover-up" style="width: 250px;">
                            <img src="{{ $m->image_path }}" alt="{{ $m->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=EAF2F8&color=2980B9'">
                            <h5 class="fw-bold text-azul-marino mb-1">{{ $m->name }}</h5>
                            <p class="text-azul-primario fw-bold small mb-0">{{ $m->role }}</p>
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
                <div class="text-center p-5 bg-white rounded-4 border border-azul-borde shadow-sm">
                    <span class="material-icons text-muted fs-1 mb-3">groups</span>
                    <p class="text-muted mb-0">La comisión directiva aún no ha sido publicada.</p>
                </div>
            @endif
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-azul-marino py-5 mt-auto">
        <div class="container text-center text-white">
            <div class="mb-4">
                <h4 class="fw-bold">{{ $school->name }}</h4>
                <p class="text-white-50 small mb-0">Órgano oficial de regulación profesional</p>
            </div>
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="#" class="text-white-50 text-decoration-none hover-white"><span class="material-icons">facebook</span></a>
                <a href="#" class="text-white-50 text-decoration-none hover-white"><span class="material-icons">email</span></a>
                <a href="#" class="text-white-50 text-decoration-none hover-white"><span class="material-icons">location_on</span></a>
            </div>
            <p class="text-white-50 small mb-0">&copy; {{ date('Y') }} Todos los derechos reservados. Desarrollado por <span class="text-white">Gente Piola</span>.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

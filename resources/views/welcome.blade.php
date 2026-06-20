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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg bg-theme-card border-bottom border-theme sticky-top shadow-sm py-3">
        <div class="container-fluid px-4 px-xl-5">
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
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="material-icons text-theme-primary">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#quienes-somos">Quiénes Somos</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#autoridades">Autoridades</a></li>
                    <li class="nav-item"><a class="nav-link text-theme-dark fw-bold" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn bg-theme-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-2 text-white text-decoration-none">
                        <span class="material-icons" style="font-size: 1.2rem;">login</span> Ingresar
                    </a>
                </div>
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
        <div class="container-fluid px-4 px-xl-5">
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
    <main class="container-fluid px-4 px-xl-5 py-5">
        
        <!-- INSTITUCIONAL -->
        <div id="quienes-somos" class="row align-items-center mb-5 pb-5 border-bottom border-theme pt-5">
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

        <!-- NOTICIAS Y NOVEDADES -->
        <div id="novedades" class="mb-5 pb-5 border-bottom border-theme pt-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="h2 fw-bold text-theme-dark mb-2">Noticias y Novedades</h2>
                    <p class="text-theme-secondary mb-0">Últimas actualizaciones e información de interés para colegiados</p>
                </div>
                <a href="{{ route('news.index') }}" class="btn btn-outline-primary rounded-pill px-4">Ver todas <span class="material-icons align-middle ms-1" style="font-size: 1.1rem;">arrow_forward</span></a>
            </div>
            
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="card h-100 border-0 rounded-4 shadow-sm bg-theme-card overflow-hidden shadow-hover-up">
                        @if($news->image_path)
                            <img src="{{ asset($news->image_path) }}" class="card-img-top" alt="{{ $news->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="material-icons text-muted" style="font-size: 4rem;">article</span>
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <span class="badge bg-theme-primary mb-2">{{ $news->category ?? 'Institucional' }}</span>
                            <h5 class="fw-bold text-theme-dark mb-2 line-clamp-2">{{ $news->title }}</h5>
                            <p class="text-secondary small mb-3 line-clamp-3">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-muted fw-medium">{{ $news->published_at->format('d/m/Y') }}</small>
                                <a href="{{ route('news.show', $news->slug) }}" class="text-theme-primary fw-bold text-decoration-none small">Leer más &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center p-5 bg-theme-card rounded-4 border border-theme shadow-sm">
                <span class="material-icons text-muted fs-1 mb-3">newspaper</span>
                <p class="text-muted mb-0">Aún no se han publicado noticias recientes.</p>
            </div>
            @endif
        </div>

        <!-- COMISION DIRECTIVA (ORG CHART) -->
        <div id="autoridades" class="mb-5 pb-4 pt-5">
            <div class="text-center mb-5">
                <h2 class="h2 fw-bold text-theme-dark mb-2">Comisión Directiva</h2>
                <p class="text-theme-secondary">Autoridades que guían nuestra institución</p>
            </div>

            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <div class="mb-5">
                        <h4 class="text-center text-theme-primary fw-bold mb-4">{{ $department }}</h4>
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

                        <div class="org-chart-wrapper d-flex flex-column align-items-center">
                            @if($president)
                            <div class="org-chart-node shadow-hover-up" style="width: 280px;">
                                @php
                                    $presImageUrl = $president->collegiate && $president->collegiate->avatar_url ? $president->collegiate->avatar_url : $president->image_path;
                                    $presName = $president->collegiate ? $president->collegiate->first_name . ' ' . $president->collegiate->last_name : $president->name;
                                @endphp
                                <img src="{{ $presImageUrl }}" alt="{{ $presName }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($presName) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                                <h5 class="fw-bold text-theme-dark mb-1">{{ $presName }}</h5>
                                <p class="text-theme-primary fw-bold small mb-0">{{ $president->role }}</p>
                            </div>
                            @endif

                            @if(count($others) > 0)
                            <div class="org-chart-connector"></div>
                            @if(count($others) > 1)
                            <div class="org-line-horizontal" style="max-width: {{ min((count($others)-1) * 290, 800) }}px;"></div>
                            @endif

                            <div class="d-flex flex-wrap justify-content-center gap-4 mt-4">
                                @foreach($others as $m)
                                <div class="org-chart-node shadow-hover-up" style="width: 250px;">
                                    @php
                                        $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                                        $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                    @endphp
                                    <img src="{{ $mImageUrl }}" alt="{{ $mName }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background={{ str_replace('#','',$bgCard) }}&color={{ str_replace('#','',$primary) }}'">
                                    <h5 class="fw-bold text-theme-dark mb-1">{{ $mName }}</h5>
                                    <p class="text-theme-primary fw-bold small mb-0">{{ $m->role }}</p>
                                    @if($m->is_substitute)
                                        <span class="badge bg-secondary mt-2">Suplente</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center p-5 bg-theme-card rounded-4 border border-theme shadow-sm">
                    <span class="material-icons text-muted fs-1 mb-3">groups</span>
                    <p class="text-muted mb-0">La comisión directiva aún no ha sido publicada.</p>
                </div>
            @endif
        </div>

        <!-- CONTACTO Y UBICACIÓN -->
        <div id="contacto" class="mb-5 pt-5">
            <div class="mb-4 text-center">
                <h2 class="h2 fw-bold text-theme-dark mb-2">Dónde Estamos</h2>
                <p class="text-theme-secondary">Encuéntranos en nuestras oficinas o contáctanos por nuestros canales oficiales.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="bg-theme-card rounded-4 p-4 shadow-sm border border-theme h-100 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">location_on</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Dirección</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->address ?? 'Calle Falsa 123, Ciudad' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">phone</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Teléfono</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->phone ?? '(123) 456-7890' }}</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-white p-2 rounded-circle shadow-sm text-theme-primary"><span class="material-icons">email</span></div>
                            <div>
                                <h6 class="fw-bold text-theme-dark mb-1">Correo Electrónico</h6>
                                <p class="text-theme-secondary small mb-0">{{ $school->email ?? 'contacto@colegio.com' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-2 pt-4 border-top border-theme">
                            <h6 class="fw-bold text-theme-dark mb-3">Síguenos en Redes</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ $school->facebook_url ?? '#' }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-circle"><span class="material-icons" style="font-size: 1rem;">facebook</span></a>
                                <a href="{{ $school->instagram_url ?? '#' }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-circle"><span class="material-icons" style="font-size: 1rem;">photo_camera</span></a>
                                <a href="{{ $school->twitter_url ?? '#' }}" target="_blank" class="btn btn-outline-info btn-sm rounded-circle"><span class="material-icons" style="font-size: 1rem;">chat</span></a>
                            </div>
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
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 400px; border: 1px solid var(--theme-border);">
                            {!! $school->map_embed_code !!}
                        </div>
                    @elseif($mapQuery)
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 400px; border: 1px solid var(--theme-border);">
                            <iframe width="100%" height="100%" style="border:0; min-height: 400px;" loading="lazy" allowfullscreen 
                                src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                    @else
                        <div class="rounded-4 bg-theme-card d-flex align-items-center justify-content-center h-100 shadow-sm border border-theme" style="min-height: 400px;">
                            <div class="text-center">
                                <span class="material-icons text-theme-secondary mb-2" style="font-size: 3rem;">map</span>
                                <p class="text-theme-secondary mb-0">Mapa no configurado</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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

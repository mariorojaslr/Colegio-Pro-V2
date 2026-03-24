<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Colegio-Pro | Gestión Profesional')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @yield('styles')
    @stack('styles')
    <style>
        /* Dark Mode OLED Styles */
        body.dark-mode { background: #000 !important; color: #fff !important; }
        body.dark-mode .navbar, 
        body.dark-mode .card, 
        body.dark-mode .offcanvas,
        body.dark-mode .mobile-nav,
        body.dark-mode .table-premium, 
        body.dark-mode footer { 
            background: #0a0a0a !important; 
            border: 1px solid #1a1a1a !important; 
            color: #e5e5e5 !important;
        }
        body.dark-mode .bg-light, 
        body.dark-mode .bg-white,
        body.dark-mode .bg-light-subtle { background: #050505 !important; }
        body.dark-mode .text-dark { color: #fff !important; }
        body.dark-mode .text-secondary, 
        body.dark-mode .text-muted { color: #a3a3a3 !important; }
        body.dark-mode table th { background: #111 !important; border-bottom: 2px solid #222 !important; }
        body.dark-mode .btn-light { background: #1a1a1a !important; color: #fff !important; border: 0; }
        body.dark-mode .form-control, 
        body.dark-mode .form-select { background-color: #111 !important; border-color: #222 !important; color: #fff !important; }
        
        mark { background: #ffeb3b !important; color: #000 !important; padding: 0; border-radius: 2px; }

        @media (max-width: 991.98px) {
            .notification-dropdown {
                width: calc(100vw - 32px) !important;
                position: fixed !important;
                left: 16px !important;
                right: 16px !important;
                top: 70px !important;
                margin: 0 !important;
                transform: none !important;
            }
        }
        @media (min-width: 992px) {
            .notification-dropdown {
                width: 350px;
            }
        }
    </style>
</head>
<body class="light-mode">
    {{-- Barra de Alerta de Suplantación (Modo Visión Omnisciente) --}}
    @if(session('impersonator_id'))
    <div class="bg-warning text-dark py-2 px-4 d-flex justify-content-between align-items-center fw-bold shadow-sm sticky-top" style="z-index: 1060;">
        <div>
            <i class="bi bi-eye-fill me-2"></i> MODO VISIÓN OMNISCIENTE: Estás viendo el sistema como <strong>{{ auth()->user()->name }}</strong>
        </div>
        <a href="{{ route('admin.leave_impersonation') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-door-open me-1"></i> Salir y Volver a OWNER
        </a>
    </div>
    @endif

    <nav class="navbar navbar-expand-lg py-2 sticky-top bg-white border-bottom border-light shadow-sm" style="transition: all 0.4s ease; z-index: 1050;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="32" class="me-2 opacity-75">
                <span class="d-none d-sm-inline fw-black ls-n1" style="color: #0f172a; font-size: 1.1rem;">COLEGIO</span>
                <span class="d-none d-sm-inline fw-light" style="color: #64748b; font-size: 1.1rem; margin-left: 1px;">PRO</span>
            </a>
            <button class="navbar-toggler border-0 order-last" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon" style="width: 20px;"></span>
            </button>

            <!-- Área de Usuario y Notificaciones (Siempre Visible) -->
            <div class="d-flex gap-2 align-items-center ms-auto me-2 me-lg-0 order-2 order-lg-last">
                @auth
                    <!-- Dark Mode Toggle -->
                    <button class="btn btn-sm btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center me-1" id="themeToggle" style="width: 38px; height: 38px;">
                        <i class="bi bi-moon-stars-fill text-dark" id="themeIcon"></i>
                    </button>

                    <!-- Campana de Notificaciones -->
                    <div class="dropdown me-1">
                        <button class="btn btn-sm btn-light rounded-circle shadow-sm border-0 position-relative" data-bs-toggle="dropdown" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                            <i class="bi bi-bell-fill fs-5 text-primary"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 8px; margin-top: 5px; margin-left: -5px;">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-3 mt-2 animate__animated animate__fadeIn notification-dropdown">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold m-0">Notificaciones</h6>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <a href="#" class="x-small text-decoration-none text-primary fw-bold">Marcar leídas</a>
                                @endif
                            </div>
                            <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                                @forelse(auth()->user()->notifications->take(5) as $notification)
                                <li class="mb-3">
                                    <div class="d-flex align-items-start gap-3 p-2 rounded-3 {{ $notification->read_at ? '' : 'bg-light' }}">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 flex-shrink-0">
                                            <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="small fw-bold mb-0 text-dark" style="font-size: 13px;">{{ $notification->data['title'] }}</p>
                                            <p class="small text-muted mb-1" style="font-size: 11px; line-height: 1.2;">{{ $notification->data['message'] }}</p>
                                            <div class="x-small text-muted-opacity">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <div class="text-center py-4">
                                    <i class="bi bi-bell-slash opacity-25 display-6 mb-2 d-block"></i>
                                    <p class="small text-muted m-0">No tienes notificaciones</p>
                                </div>
                                @endforelse
                            </div>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li><a class="dropdown-item text-center small text-primary fw-bold py-2" href="#">Ver todo el historial</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-pill px-3 fw-bold x-small border-0 shadow-sm dropdown-toggle" data-bs-toggle="dropdown">
                            {{ explode(' ', auth()->user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2 animate__animated animate__fadeIn">
                            <li><a class="dropdown-item rounded-3 py-2 x-small fw-bold" href="{{ route('home') }}"><i class="bi bi-person me-2"></i> Perfil</a></li>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 py-2 x-small fw-bold text-danger"><i class="bi bi-box-arrow-right me-2"></i> {{ __('Salir') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark btn-sm rounded-pill px-4 fw-black x-small shadow-sm">Acceso Institucional</a>
                @endauth
            </div>

            <div class="collapse navbar-collapse order-3" id="navContent">
                <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0">
                    @auth
                        @php $currentRoute = request()->route()->getName(); @endphp
                        {{-- Solo mostramos el menú interno si NO estamos en la Landing Page pública --}}
                        @if(!request()->is('/'))
                            @if(auth()->user()->role === 'ADMIN_COLEGIO')
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ $currentRoute == 'home' ? 'text-primary' : 'text-muted' }}" href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'collegiates') ? 'text-primary' : 'text-muted' }}" href="{{ route('collegiates.index') }}">{{ __('Padrón') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'billing') ? 'text-primary' : 'text-muted' }}" href="{{ route('admin.billing.index') }}">{{ __('Finanzas') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'ethics') ? 'text-primary' : 'text-muted' }}" href="{{ route('admin.ethics.index') }}">{{ __('Ética') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'lessons') ? 'text-primary' : 'text-muted' }}" href="{{ route('student.lessons.index') }}">{{ __('Academia') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'compliance') ? 'text-primary' : 'text-muted' }}" href="{{ route('admin.compliance.index') }}">{{ __('Auditoría') }}</a></li>
                            @elseif(auth()->user()->isOwner())
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-primary" href="{{ route('admin.dashboard') }}">{{ __('Panel Global') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="{{ route('student.lessons.index') }}">{{ __('Ver Academia') }}</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ $currentRoute == 'home' ? 'text-primary' : 'text-muted' }}" href="{{ route('home') }}">{{ __('Mi Panel') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'lessons') ? 'text-primary' : 'text-muted' }}" href="{{ route('student.lessons.index') }}">{{ __('Academia') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'compliance') ? 'text-primary' : 'text-muted' }}" href="{{ route('compliance.index') }}">{{ __('Mi Legajo') }}</a></li>
                            @endif
                        @else
                            {{-- En la Landing Page, incluso logueado, mostramos info del producto --}}
                            <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#ventajas">Ventajas</a></li>
                            <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#pricing">Planes</a></li>
                            <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-primary" href="/escuela-virtual">Escuela Virtual</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#ventajas">Ventajas</a></li>
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#pricing">Planes</a></li>
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-primary" href="/escuela-virtual">Escuela Virtual</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- El footer ha sido desactivado por solicitud administrativa para optimizar el área de trabajo --}}

    <style>
        .hover-white:hover { color: white !important; padding-left: 5px; }
        .transition-all { transition: all 0.3s ease; }
    </style>

    <!-- Navegación Móvil Estilo App (Solo pantallas pequeñas) -->
    @auth
    @if(!auth()->user()->isOwner())
    <div class="mobile-nav d-lg-none fixed-bottom bg-white border-top shadow-lg d-flex justify-content-around py-3">
        <a href="{{ route('home') }}" class="text-center text-decoration-none {{ request()->routeIs('home') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-grid m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Inicio</div>
        </a>
        <a href="{{ route('collegiates.index') }}" class="text-center text-decoration-none {{ request()->routeIs('collegiates.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-people m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">{{ auth()->user()->school?->member_plural ?? 'Miembros' }}</div>
        </a>
        <a href="{{ route('ai.index') }}" class="text-center text-decoration-none {{ request()->routeIs('ai.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white shadow-sm" style="width: 45px; height: 45px; margin-top: -25px; border: 4px solid white">
                <i class="bi bi-robot" style="font-size: 1.2rem"></i>
            </div>
            <div style="font-size: 10px">Asistente</div>
        </a>
        @if(auth()->user()->role === 'ADMIN_COLEGIO')
        <a href="{{ route('admin.billing.index') }}" class="text-center text-decoration-none {{ request()->routeIs('admin.billing.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-currency-dollar m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Cobranzas</div>
        </a>
        <a href="{{ route('admin.ethics.index') }}" class="text-center text-decoration-none {{ request()->routeIs('admin.ethics.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-shield-check m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Ética</div>
        </a>
        @else
        <a href="{{ route('billing.index') }}" class="text-center text-decoration-none {{ request()->routeIs('billing.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-credit-card m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mi Plan</div>
        </a>
        <a href="{{ route('compliance.index') }}" class="text-center text-decoration-none {{ request()->routeIs('compliance.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-folder2-open m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mi Legajo</div>
        </a>
        @endif
    </div>
    @endif
    @endauth

    <style>
        .mobile-nav {
            z-index: 1050;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9) !important; /* Fondo semi-transparente para efecto glassmorphism */
        }
        /* Ajusta el padding del body para que el contenido no quede oculto detrás de la nav bar móvil */
        @media (max-width: 991.98px) { /* Bootstrap's 'lg' breakpoint */
            body { padding-bottom: 80px; } /* Altura aproximada de la barra de navegación móvil */
        }
    </style>

    <!-- JS dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;
        
        function updateThemeElements(isDark) {
            if (isDark) {
                body.classList.replace('light-mode', 'dark-mode');
                if(themeIcon) {
                    themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    themeIcon.classList.replace('text-dark', 'text-warning');
                }
            } else {
                body.classList.replace('dark-mode', 'light-mode');
                if(themeIcon) {
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                    themeIcon.classList.replace('text-warning', 'text-dark');
                }
            }
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const isDark = body.classList.contains('light-mode');
                updateThemeElements(isDark);
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        updateThemeElements(savedTheme === 'dark');
    </script>
    <!-- Asistente IA 'Carina' (Burbuja Flotante Premium) -->
    <div class="position-fixed bottom-0 end-0 mb-4 me-4 d-none d-md-block" style="z-index: 1061;">
        <button class="btn btn-primary rounded-circle shadow-lg p-0 border-4 border-white animate__animated animate__bounceIn" 
                style="width: 65px; height: 65px; background: linear-gradient(135deg, #0F172A, #2563EB);"
                data-bs-toggle="offcanvas" data-bs-target="#carinaManual">
            <i class="bi bi-robot fs-2 text-white"></i>
        </button>
    </div>

    <!-- Manual Interactivo de Carina (Offcanvas) -->
    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="carinaManual" style="width: 400px; border-radius: 30px 0 0 30px;">
        <div class="offcanvas-header bg-dark text-white p-4" style="border-radius: 30px 0 0 0;">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary rounded-circle p-2"><i class="bi bi-robot fs-4"></i></div>
                <div>
                    <h5 class="offcanvas-title fw-bold mb-0">Soy Carina</h5>
                    <small class="opacity-75">Instructora Permanente</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light-subtle">
            <div class="chat-bubble bg-white p-3 rounded-4 shadow-sm mb-4 border-start border-4 border-primary animate__animated animate__fadeIn">
                @if(request()->is('/'))
                    <p class="small mb-0 fw-bold text-dark">¡Hola! Bienvenido a la vitrina de Colegio-Pro.</p>
                    <p class="x-small mb-0 mt-2">Aquí puedes explorar las ventajas de nuestra infraestructura SaaS para instituciones profesionales.</p>
                @elseif(request()->routeIs('home'))
                    <p class="small mb-0 fw-bold text-dark">Estás en tu Centro de Mando.</p>
                    <p class="x-small mb-0 mt-2">He analizado los datos: observa las tarjetas de arriba para ver rápidamente la morosidad y las bajas del día.</p>
                @elseif(request()->is('finanzas*'))
                    <p class="small mb-0 fw-bold text-dark">Bienvenido al área de Cobranzas.</p>
                    <p class="x-small mb-0 mt-2">Aquí gestionamos deudas y convenios. Recuerda que un convenio activo suspende temporalmente el estado de morosidad clínica.</p>
                @elseif(request()->is('gestion-etica*'))
                    <p class="small mb-0 fw-bold text-dark">Módulo de Disciplina y Ética.</p>
                    <p class="x-small mb-0 mt-2">Las sanciones aquí registradas bloquean automáticamente la emisión de certificados de habilitación.</p>
                @else
                    <p class="small mb-0 fw-medium">Soy tu guía en esta sección. ¿En qué puedo orientarte hoy?</p>
                @endif
            </div>

            <h6 class="xx-small text-uppercase ls-2 text-secondary fw-bold mb-3">Guía Rápida</h6>
            <div class="list-group list-group-flush rounded-4 overflow-hidden shadow-sm border mb-4">
                @if(request()->routeIs('home'))
                    <div class="list-group-item p-3 border-0 bg-white">
                        <h6 class="fw-bold mb-1 small text-primary"><i class="bi bi-graph-up me-2"></i>Métricas Críticas</h6>
                        <p class="x-small text-muted mb-0">Haz clic en la tarjeta de "Mora Arancelaria" para ir directo al listado de cobranza.</p>
                    </div>
                @elseif(request()->is('finanzas*'))
                    <div class="list-group-item p-3 border-0 bg-white">
                        <h6 class="fw-bold mb-1 small text-primary"><i class="bi bi-file-earmark-text me-2"></i>Convenios 12x10</h6>
                        <p class="x-small text-muted mb-0">Esta función permite condonar 2 cuotas a cambio de un compromiso de pago anual.</p>
                    </div>
                @elseif(request()->is('gestion-etica*'))
                    <div class="list-group-item p-3 border-0 bg-white">
                        <h6 class="fw-bold mb-1 small text-primary"><i class="bi bi-shield-lock me-2"></i>Bloqueo de Certificados</h6>
                        <p class="x-small text-muted mb-0">Un sancionado no puede descargar su credencial digital ni certificados de libre deuda.</p>
                    </div>
                @else
                    <div class="list-group-item p-3 border-0 bg-white">
                        <h6 class="fw-bold mb-1 small text-primary"><i class="bi bi-info-circle me-2"></i>Navegación</h6>
                        <p class="x-small text-muted mb-0">Usa el menú superior para saltar entre los diferentes subsistemas integrados.</p>
                    </div>
                @endif
            </div>
            
            <div class="mt-4 text-center">
                <div class="p-3 bg-white rounded-4 shadow-sm border mb-2">
                    <img src="{{ asset('media/carina_wave.png') }}" alt="IA wave" class="img-fluid" style="max-height: 100px;">
                </div>
                <p class="x-small text-muted italic mt-3">"Mi objetivo es que la administración de tu institución sea invisible, elegante y perfecta."</p>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>

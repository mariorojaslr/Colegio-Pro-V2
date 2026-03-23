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
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @yield('styles')
</head>
<body class="light-mode">
    <nav class="navbar navbar-expand-lg py-3 sticky-top glass-card m-2">
        <div class="container uppercase">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
                <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="40" class="me-2">
                <span class="d-none d-sm-inline" style="color: var(--primary-color)">COLEGIO</span>
                <span class="d-none d-sm-inline" style="color: var(--accent-color)">-PRO</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
                    @auth
                        @if(auth()->user()->role === 'ADMIN_COLEGIO')
                            {{-- Navegación Estilo Institucional para el Administrador del Colegio --}}
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}"><i class="bi bi-grid-alt me-1"></i> {{ __('Dashboard') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('collegiates.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('collegiates.index') }}"><i class="bi bi-people me-1"></i> {{ __('Padrón') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('admin.compliance.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.compliance.index') }}"><i class="bi bi-patch-check me-1"></i> {{ __('Auditoría') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('amenities.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('amenities.index') }}"><i class="bi bi-buildings me-1"></i> {{ __('Club y Sedes') }}</a></li>
                        @elseif(auth()->user()->isOwner())
                            {{-- Acceso Rápido para el Owner --}}
                            <li class="nav-item"><a class="nav-link px-3 active fw-bold text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-1"></i> {{ __('Panel Global') }}</a></li>
                        @else
                            {{-- Navegación para el Colegiado --}}
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-1"></i> {{ __('Mi Panel') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('compliance.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('compliance.index') }}"><i class="bi bi-file-earmark-lock me-1"></i> {{ __('Mi Legajo') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('amenities.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('amenities.index') }}"><i class="bi bi-buildings me-1"></i> {{ __('Deportes') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 {{ request()->routeIs('tickets.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('tickets.index') }}"><i class="bi bi-chat-dots me-1"></i> {{ __('Soporte') }}</a></li>
                        @endif
                    @else
                        <li class="nav-item"><a class="nav-link px-3" href="#ventajas">Ventajas</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="#servicios">Servicios</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="#demo">Demo</a></li>
                    @endauth
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @if(!request()->is('/'))
                    {{-- Botón para alternar entre modo claro y oscuro --}}
                    <button id="themeToggle" class="btn btn-sm btn-outline-dark border-0 rounded-circle p-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                        </svg>
                    </button>
                    @endif

                    {{-- Gestión dinámica del botón de acceso/dashboard basado en estado de autenticación --}}
                    @auth
                        @if(session('impersonator_id'))
                            <a href="{{ route('admin.leave_impersonation') }}" class="btn btn-warning px-3 shadow-sm fw-bold me-2" style="font-size: 0.85rem">
                                <i class="bi bi-person-up me-1"></i> {{ __('Volver a Owner') }}
                            </a>
                        @endif

                        @if(auth()->user()->isOwner())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary px-3 shadow-sm fw-bold me-2" style="font-size: 0.85rem">{{ __('Panel OWNER') }}</a>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-primary px-3 shadow-sm fw-bold me-2" style="font-size: 0.85rem">{{ __('Mi Dashboard') }}</a>
                        @endif

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger px-3 shadow-sm fw-bold" style="font-size: 0.85rem"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> {{ __('Salir') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium px-4 shadow-sm" style="font-size: 0.9rem">Acceso Institucional</a>
                    @endauth
                </div>
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
            <div style="font-size: 10px">Colegiados</div>
        </a>
        <a href="{{ route('ai.index') }}" class="text-center text-decoration-none {{ request()->routeIs('ai.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white shadow-sm" style="width: 45px; height: 45px; margin-top: -25px; border: 4px solid white">
                <i class="bi bi-robot" style="font-size: 1.2rem"></i>
            </div>
            <div style="font-size: 10px">Asistente</div>
        </a>
        <a href="{{ route('billing.index') }}" class="text-center text-decoration-none {{ request()->routeIs('billing.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-credit-card m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mi Plan</div>
        </a>
        <a href="{{ route('tickets.index') }}" class="text-center text-decoration-none {{ request()->routeIs('tickets.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-chat-dots m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Soporte</div>
        </a>
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
    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        
        // Event listener para el botón de alternar tema
        themeToggle.addEventListener('click', () => {
            if (body.classList.contains('light-mode')) {
                body.classList.replace('light-mode', 'dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.replace('dark-mode', 'light-mode');
                localStorage.setItem('theme', 'light');
            }
        });

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.replace('light-mode', 'dark-mode');
        }
    </script>
    @yield('scripts')
</body>
</html>

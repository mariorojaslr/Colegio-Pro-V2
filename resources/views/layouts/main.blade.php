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
    @stack('styles')
</head>
<body class="light-mode">
    <nav class="navbar navbar-expand-lg py-2 sticky-top bg-white border-bottom border-light shadow-sm" style="transition: all 0.4s ease;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="32" class="me-2 opacity-75">
                <span class="d-none d-sm-inline fw-black ls-n1" style="color: #0f172a; font-size: 1.1rem;">COLEGIO</span>
                <span class="d-none d-sm-inline fw-light" style="color: #64748b; font-size: 1.1rem; margin-left: 1px;">PRO</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon" style="width: 20px;"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    @auth
                        @php $currentRoute = request()->route()->getName(); @endphp
                        @if(auth()->user()->role === 'ADMIN_COLEGIO')
                            <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ $currentRoute == 'home' ? 'text-primary' : 'text-muted' }}" href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                            <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'collegiates') ? 'text-primary' : 'text-muted' }}" href="{{ route('collegiates.index') }}">{{ __('Padrón') }}</a></li>
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
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#ventajas">Ventajas</a></li>
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="#servicios">Servicios</a></li>
                        <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-primary" href="/escuela-virtual">Escuela Virtual</a></li>
                    @endauth
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @auth
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
        <a href="{{ route('billing.index') }}" class="text-center text-decoration-none {{ request()->routeIs('billing.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-credit-card m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mi Plan</div>
        </a>
        <a href="{{ route('compliance.index') }}" class="text-center text-decoration-none {{ request()->routeIs('compliance.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-folder2-open m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mi Legajo</div>
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
        
        // Event listener para el botón de alternar tema (si existe)
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                if (body.classList.contains('light-mode')) {
                    body.classList.replace('light-mode', 'dark-mode');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.replace('dark-mode', 'light-mode');
                    localStorage.setItem('theme', 'light');
                }
            });
        }

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.replace('light-mode', 'dark-mode');
        }
    </script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio-Pro | Dashboard Administrativo</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        body { background-color: #F8FAFC; font-family: 'Inter', sans-serif; transition: background-color 0.3s, color 0.3s; }
        .sidebar { width: 130px; min-height: 100vh; background: var(--primary-color); color: white; transition: all 0.3s; z-index: 1040; flex-shrink: 0; }
        .sidebar .nav-link { display: flex; flex-direction: column; align-items: center; justify-content: center; color: rgba(255,255,255,0.7); font-weight: 600; font-size: 0.65rem; text-align: center; padding: 10px 5px; border-radius: 12px; margin: 4px 10px; border: 1px solid transparent; transition: all 0.2s; letter-spacing: 0.5px; }
        .sidebar .nav-link i { font-size: 1.4rem; margin-bottom: 4px; margin-right: 0 !important; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.05); }
        .sidebar-brand { padding: 25px 10px; text-align: center; }
        .main-content { flex: 1; min-width: 0; background: #f8fafc; width: calc(100% - 130px); }
        @media (max-width: 991.98px) { .main-content { width: 100%; } }
        .stat-card { border-radius: 20px; border: 0; transition: transform 0.2s, background-color 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-card:hover { transform: translateY(-5px); }
        .table-premium { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 0 !important; }

        /* Dark Mode OLED Styles */
        body[data-bs-theme='dark'] { background: #000 !important; color: #fff !important; }
        body[data-bs-theme='dark'] .main-content { background: #000 !important; }
        body[data-bs-theme='dark'] .navbar, 
        body[data-bs-theme='dark'] .card, 
        body[data-bs-theme='dark'] .table-premium, 
        body[data-bs-theme='dark'] footer { 
            background: #0a0a0a !important; 
            border: 1px solid rgba(255, 255, 255, 0.35) !important; 
            color: #e5e5e5 !important;
        }
        body[data-bs-theme='dark'] .bg-light, 
        body[data-bs-theme='dark'] .bg-white { background: #050505 !important; }
        body[data-bs-theme='dark'] .text-dark { color: #fff !important; }
        body[data-bs-theme='dark'] .text-muted { color: #a3a3a3 !important; }
        body[data-bs-theme='dark'] table th { background: #111 !important; border-bottom: 2px solid #222 !important; }
        body[data-bs-theme='dark'] .sidebar { border-right: 1px solid #1a1a1a; }
        body[data-bs-theme='dark'] .btn-light { background: #1a1a1a !important; color: #fff !important; border: 0; }
        
        .theme-toggle-btn { width: 40px; height: 40px; }
        .badge-plan { font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 5px 10px; border-radius: 30px; }
        .plan-initial { background: #E2E8F0; color: #475569; }
        .plan-professional { background: #DBEAFE; color: #1E40AF; }
        .plan-enterprise { background: #FEF3C7; color: #B45309; }
    </style>
    @yield('styles')
</head>
<body class="d-flex" style="flex-direction: column; min-height: 100vh;">
    @include('components.context-switcher')
    
    <div class="d-flex flex-grow-1" style="width: 100%;">

    <!-- Sidebar -->
    <div class="sidebar d-none d-lg-block sticky-top h-100">
        <div class="sidebar-brand">
            @php
                $isOwnerView = auth()->user()->isOwner() && !session()->has('impersonator_id');
                $currentSchool = $isOwnerView ? null : auth()->user()->school;
            @endphp

            @if($isOwnerView)
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-white text-primary fw-bold mb-2 shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <h5 class="fw-bold m-0 mt-2" style="font-family: 'Outfit', sans-serif; letter-spacing: 1px;">COLEGIO-PRO</h5>
                <div class="x-small text-white-50 mt-1">Panel de Control Global</div>
            @else
                @if($currentSchool && $currentSchool->logo)
                    <img src="{{ asset($currentSchool->logo) }}" alt="{{ $currentSchool->name }}" height="80" class="mb-2" style="border-radius: 10px; max-width: 100%; object-fit: contain;">
                @else
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center bg-white text-primary fw-bold mb-2 shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ substr($currentSchool->name ?? 'C', 0, 1) }}
                    </div>
                @endif
                <h6 class="fw-bold m-0 mt-2 text-wrap" style="font-family: 'Outfit', sans-serif; letter-spacing: 1px; font-size: 0.9rem;">{{ strtoupper($currentSchool->name ?? 'Portal') }}</h6>
                <div class="x-small text-white-50 mt-1">Administración Institucional</div>
            @endif
        </div>
        
        <nav class="nav flex-column mb-auto">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('home') ? 'active' : '' }}" href="{{ $isOwnerView ? route('admin.dashboard') : route('home') }}"><i class="bi bi-grid"></i> Vista General</a>
            
            @if($isOwnerView)
                <a class="nav-link {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}" href="{{ route('admin.schools.index') }}"><i class="bi bi-building"></i> Empresas</a>
                <a class="nav-link {{ request()->routeIs('admin.academy.*') ? 'active' : '' }}" href="{{ route('admin.academy.index') }}"><i class="bi bi-mortarboard"></i> Academia</a>
                <a class="nav-link {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}" href="{{ route('admin.exams.index') }}"><i class="bi bi-card-checklist"></i> Exámenes</a>
                <a class="nav-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}"><i class="bi bi-credit-card"></i> Suscripciones</a>
                <a class="nav-link {{ request()->routeIs('admin.billing.global') ? 'active' : '' }}" href="{{ route('admin.billing.global') }}"><i class="bi bi-wallet2"></i> Finanzas</a>
            @else
                @php $ctx = session('active_role_context', 'admin_general'); @endphp
                
                @if($ctx === 'admin_general' || $ctx === 'manage_cms')
                    <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}"><i class="bi bi-megaphone"></i> Flyers</a>
                @endif
                
                @if($ctx === 'admin_general' || $ctx === 'manage_users' || $ctx === 'manage_finances' || $ctx === 'manage_ethics')
                    <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}"><i class="bi bi-chat-dots"></i> Soporte</a>
                    <a class="nav-link {{ request()->routeIs('admin.activity_logs.*') ? 'active' : '' }}" href="{{ route('admin.activity_logs.index') }}"><i class="bi bi-activity"></i> Auditoría</a>
                @endif

                @if($ctx === 'admin_general' || $ctx === 'manage_users')
                    <a class="nav-link {{ request()->routeIs('collegiates.index') ? 'active' : '' }}" href="{{ route('collegiates.index') }}"><i class="bi bi-people"></i> Usuarios</a>
                @endif
                
                @if($ctx === 'admin_general')
                    <a class="nav-link {{ request()->routeIs('admin.permissions.index') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}"><i class="bi bi-shield-lock"></i> Autoridades</a>
                @endif

                @if($ctx === 'admin_general' || $ctx === 'manage_finances')
                    <a class="nav-link {{ request()->routeIs('admin.billing.index') ? 'active' : '' }}" href="{{ route('admin.billing.index') }}"><i class="bi bi-wallet2"></i> Contabilidad</a>
                    <a class="nav-link {{ request()->routeIs('billing.index') ? 'active' : '' }}" href="{{ route('billing.index') }}"><i class="bi bi-credit-card"></i> Mi Plan</a>
                @endif

                @if($ctx === 'admin_general' || $ctx === 'manage_ethics')
                    <a class="nav-link {{ request()->routeIs('admin.ethics.*') ? 'active' : '' }}" href="{{ route('admin.ethics.index') ?? '#' }}"><i class="bi bi-bank"></i> Ética</a>
                @endif
                
                @if($ctx === 'admin_general' || $ctx === 'manage_academy')
                    <a class="nav-link {{ request()->routeIs('admin.exams.*') ? 'active' : '' }}" href="{{ route('admin.exams.index') ?? '#' }}"><i class="bi bi-mortarboard"></i> Academia</a>
                @endif

                <a class="nav-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}"><i class="bi bi-chat-dots"></i> Mis Tickets</a>
            @endif
        </nav>
        
        <div class="mt-auto pb-3">
            <a href="#" class="nav-link text-decoration-none text-center" style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: rgba(255,255,255,0.7); font-weight: 600; font-size: 0.65rem; padding: 10px 5px; border-radius: 12px; margin: 4px 10px; transition: all 0.2s;">
                <i class="bi bi-headset" style="font-size: 1.4rem; margin-bottom: 4px; color: rgba(255,255,255,0.7);"></i>
                <span>Soporte<br>Técnico</span>
            </a>
        </div>
    </div>

    <!-- Contenedor de Contenido Principal -->
    <div class="main-content d-flex flex-column vh-100 overflow-auto">
        {{-- Barra de Alerta de Suplantación (Modo Soporte Administrativo) --}}
        @if(session('impersonator_id'))
        <div class="bg-warning text-dark py-2 px-4 d-flex justify-content-between align-items-center fw-bold shadow-sm">
            <div>
                <i class="bi bi-headset me-2"></i> SESIÓN DE SOPORTE: Estás viendo el sistema como <strong>{{ auth()->user()->name }}</strong>
            </div>
            <a href="{{ route('admin.leave_impersonation') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-door-open me-1"></i> Salir y Volver a OWNER
            </a>
        </div>
        @endif
        
        <!-- Topbar: Barra superior de navegación y perfil -->
        <nav class="navbar navbar-expand bg-white py-3 px-4 sticky-top shadow-sm">
            <div class="container-fluid justify-content-between p-0">
                {{-- Lado izquierdo: Título y saludo --}}
                <div class="d-flex flex-column me-3" style="min-width: 0;">
                    <h4 class="m-0 fw-bold text-truncate" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">
                        @yield('header', 'Dashboard Central')
                    </h4>
                    @php
                        $user = auth()->user();
                        $hour = date('H');
                        $greeting = $hour < 12 ? 'Buenos días' : ($hour < 19 ? 'Buenas tardes' : 'Buenas noches');
                    @endphp
                    <span class="x-small text-muted fw-medium d-none d-md-block text-truncate">
                        {{ $greeting }}, <span class="text-primary fw-bold">{{ $user->name }}</span>. Bienvenido de vuelta al centro de mando.
                    </span>
                </div>
                
                {{-- Lado derecho: Controles de la barra superior --}}
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    {{-- Dark Mode Toggle --}}
                    <button class="btn btn-light rounded-circle theme-toggle-btn p-0 border-0 shadow-sm d-flex align-items-center justify-content-center" id="themeToggle" title="Cambiar Tema">
                        <i class="bi bi-moon-stars-fill text-dark fs-5" id="themeIcon"></i>
                    </button>

                    <div class="vr mx-1"></div>
                    
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 border-0 position-relative shadow-sm" data-bs-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 8px">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-3 rounded-4 mt-2" style="width: 300px">
                            <h6 class="fw-bold mb-3">Notificaciones</h6>
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                            <li class="mb-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                        <i class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }} text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="small fw-bold mb-0 text-dark">{{ $notification->data['title'] }}</p>
                                        <p class="small text-muted mb-0" style="font-size: 11px">{{ $notification->data['message'] }}</p>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <p class="small text-center text-muted m-0">No hay notificaciones pendientes</p>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center small text-primary fw-bold" href="#">Ver todas</a></li>
                        </ul>
                    </div>
                    
                    <div class="vr mx-2"></div>
                    
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2 shadow-sm" style="width: 35px; height: 35px; font-size: 14px">O</div>
                            <span class="d-none d-sm-inline fw-semibold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-4 text-small" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item rounded-3" href="#">Perfil</a></li>
                            <li><a class="dropdown-item rounded-3" href="#">Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item rounded-3 text-danger" type="submit">Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>{{-- Cierre del wrapper d-flex align-items-center (lado derecho) --}}
            </div>{{-- Cierre del container-fluid --}}
        </nav>

        <div class="p-4 p-lg-5">
            {{-- Alertas de Suscripción y Almacenamiento --}}
            @if(auth()->check() && !auth()->user()->isOwner() && auth()->user()->school)
                @if(!auth()->user()->school->activeSubscription)
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                        <i class="bi bi-x-octagon-fill me-2 text-danger"></i>
                        <strong>Sin Suscripción Activa:</strong> Tu empresa no tiene un plan asignado. Por favor, contacta al administrador de la plataforma para habilitar tu cuenta.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif(!auth()->user()->school->canUploadFile(0))
                    <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
                        <strong>Límite de Almacenamiento Alcanzado:</strong> Tu empresa ha superado la capacidad incluida en tu plan actual. Puedes seguir operando, pero te recomendamos contactar al administrador para revisar o actualizar tu suscripción.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endif

            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="mt-auto py-3 px-4 bg-white text-center border-top">
            <p class="m-0 text-muted small">&copy; {{ date('Y') }} Graficar Software de Mario Rojas. Todos los derechos reservados.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            
            // Función para actualizar el icono y tema
            function setTheme(theme) {
                if (theme === 'dark') {
                    body.setAttribute('data-bs-theme', 'dark');
                    themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    themeIcon.classList.replace('text-dark', 'text-warning');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.setAttribute('data-bs-theme', 'light');
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                    themeIcon.classList.replace('text-warning', 'text-dark');
                    localStorage.setItem('theme', 'light');
                }
            }

            // Detectar preferencia guardada
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);

            // Toggle Click
            themeToggle.addEventListener('click', () => {
                const currentTheme = body.getAttribute('data-bs-theme');
                setTheme(currentTheme === 'dark' ? 'light' : 'dark');
            });
        });
    </script>
    </div> <!-- Cerrar flex-grow-1 -->
</body>
</html>

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
        body { background-color: #F8FAFC; font-family: 'Inter', sans-serif; }
        .sidebar { width: 280px; min-height: 100vh; background: var(--primary-color); color: white; transition: all 0.3s; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); font-weight: 500; padding: 12px 20px; border-radius: 12px; margin: 4px 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-brand { padding: 30px 20px; text-align: center; }
        .main-content { flex: 1; min-width: 0; }
        .stat-card { border-radius: 20px; border: 0; transition: transform 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-card:hover { transform: translateY(-5px); }
        .table-premium { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .badge-plan { font-size: 11px; font-weight: 700; text-transform: uppercase; padding: 5px 10px; border-radius: 30px; }
        .plan-initial { background: #E2E8F0; color: #475569; }
        .plan-professional { background: #DBEAFE; color: #1E40AF; }
        .plan-enterprise { background: #FEF3C7; color: #B45309; }
    </style>
    @yield('styles')
</head>
<body class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar d-none d-lg-block sticky-top h-100">
        <div class="sidebar-brand">
            <img src="{{ asset('media/logo.png') }}" alt="CP Logo" height="50" class="mb-2">
            <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif; letter-spacing: 1px;">COLEGIO-PRO</h5>
            <small class="opacity-50">OWNER PANEL</small>
        </div>
        
        <nav class="nav flex-column mb-auto">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i> Vista General</a>
            <a class="nav-link {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}" href="{{ route('admin.schools.index') }}"><i class="bi bi-building me-2"></i> Empresas</a>
            <a class="nav-link" href="#"><i class="bi bi-people me-2"></i> Usuarios</a>
            <a class="nav-link {{ request()->routeIs('admin.plans.index') ? 'active' : '' }}" href="{{ route('admin.plans.index') }}"><i class="bi bi-credit-card me-2"></i> Suscripciones</a>
            <a class="nav-link {{ request()->routeIs('admin.billing.index') ? 'active' : '' }}" href="{{ route('admin.billing.index') }}"><i class="bi bi-wallet2 me-2"></i> Finanzas</a>
            <a class="nav-link {{ request()->routeIs('admin.tickets.index') ? 'active' : '' }}" href="{{ route('admin.tickets.index') }}"><i class="bi bi-chat-dots me-2"></i> Soporte</a>
            <a class="nav-link {{ request()->routeIs('admin.activity_logs.index') ? 'active' : '' }}" href="{{ route('admin.activity_logs.index') }}"><i class="bi bi-activity me-2"></i> Auditoría</a>
        </nav>
        
        <div class="p-4 mt-auto">
            <div class="glass-card p-3 rounded-4 bg-white bg-opacity-10 text-center">
                <p class="small m-0 opacity-75">Soporte Técnico</p>
                <a href="#" class="text-white fw-bold text-decoration-none small">Contactar Equipo</a>
            </div>
        </div>
    </div>

    <!-- Contenedor de Contenido Principal -->
    <div class="main-content d-flex flex-column vh-100 overflow-auto">
        {{-- Barra de Alerta de Suplantación (Modo Visión Omnisciente) --}}
        @if(session('impersonator_id'))
        <div class="bg-warning text-dark py-2 px-4 d-flex justify-content-between align-items-center fw-bold shadow-sm">
            <div>
                <i class="bi bi-eye-fill me-2"></i> MODO VISIÓN OMNISCIENTE: Estás viendo el sistema como <strong>{{ auth()->user()->name }}</strong>
            </div>
            <a href="{{ route('admin.leave_impersonation') }}" class="btn btn-dark btn-sm rounded-pill px-3 fw-bold">
                <i class="bi bi-door-open me-1"></i> Salir y Volver a OWNER
            </a>
        </div>
        @endif
        
        <!-- Topbar: Barra superior de navegación y perfil -->
        <nav class="navbar navbar-expand bg-white py-3 px-4 sticky-top shadow-sm">
            <div class="container-fluid justify-content-between p-0">
                <h4 class="m-0 fw-bold" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">@yield('header', 'Dashboard Central')</h4>
                
                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 border-0 position-relative" data-bs-toggle="dropdown">
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
                </div>
            </div>
        </nav>

        <div class="p-4 p-lg-5">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="mt-auto py-3 px-4 bg-white text-center border-top">
            <p class="m-0 text-muted small">&copy; {{ date('y') }} Colegio-Pro. Visión Omnisciente activada.</p>
        </footer>
    </div>

</body>
</html>

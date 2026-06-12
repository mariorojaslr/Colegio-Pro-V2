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
        /* Dark Mode OLED Styles (Total Blackout Phase) */
        body.dark-mode { background: #000 !important; color: #fff !important; }
        body.dark-mode .navbar, 
        body.dark-mode .card, 
        body.dark-mode .card-prestige,
        body.dark-mode .offcanvas,
        body.dark-mode .modal-content,
        body.dark-mode .mobile-nav,
        body.dark-mode .dropdown-menu,
        body.dark-mode footer { 
            background: #000 !important; 
            border: 1px solid rgba(255, 255, 255, 0.25) !important; 
        }

        /* Forzado de icono blanco en modo oscuro (Nueva versión Font-Icon) */
        body.dark-mode #mobileMenuIcon {
            color: #ffffff !important;
            opacity: 1 !important;
            font-size: 1.8rem !important;
        }
        body.dark-mode .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Líneas y tablas ultra visibles en OLED - Refuerzo Rolls-Royce */
        body.dark-mode hr { border-top: 3px solid #ffffff !important; opacity: 0.9 !important; }
        body.dark-mode .table, 
        body.dark-mode table,
        body.dark-mode .table-responsive,
        body.dark-mode .table td, 
        body.dark-mode .table th,
        body.dark-mode table td,
        body.dark-mode table th { 
            border-bottom: 2.5px solid rgba(255, 255, 255, 0.6) !important; 
            border-top: 0 !important;
        }
        
        body.dark-mode .dropdown-item { color: #fff !important; background: transparent !important; }
        body.dark-mode .dropdown-item:hover,
        body.dark-mode .dropdown-item.active { 
            background: rgba(255, 255, 255, 0.15) !important; 
            color: #fff !important; 
        }
        
        body.dark-mode .btn-light { 
            background: rgba(255, 255, 255, 0.1) !important; 
            color: #fff !important; 
            border: 1px solid rgba(255, 255, 255, 0.15) !important; 
        }
        body.dark-mode .card-header, 
        body.dark-mode .bg-white, 
        body.dark-mode .bg-light,
        body.dark-mode .bg-light-subtle { background: #000 !important; border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important; }
        
        body.dark-mode .text-finance-clean { color: #fff !important; opacity: 1 !important; }
        body:not(.dark-mode) .text-finance-clean { color: #000 !important; opacity: 1 !important; }
        
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3, 
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
        body.dark-mode .text-dark, body.dark-mode p, body.dark-mode span,
        body.dark-mode label, body.dark-mode .nav-link, body.dark-mode .dropdown-item { 
            color: #fff !important; 
        }
        
        body.dark-mode .text-secondary, body.dark-mode .text-muted { 
            color: #b0b0b0 !important; 
        }
        
        body.dark-mode table, 
        body.dark-mode table tr, 
        body.dark-mode table td,
        body.dark-mode table th,
        body.dark-mode .table-hover tbody tr:hover { 
            background: #000 !important; 
            color: #fff !important; 
            border-color: #1a1a1a !important; 
        }
        
        body.dark-mode .btn-light,
        body.dark-mode .btn-outline-dark,
        body.dark-mode .btn-outline-primary { 
            background: #111 !important; 
            color: #fff !important; 
            border: 1px solid #333 !important; 
        }
        
        body.dark-mode .form-control, 
        body.dark-mode .form-select,
        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea { 
            background-color: #0c0c0c !important; 
            border-color: #333 !important; 
            color: #fff !important; 
        }
        
        body.dark-mode .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        body.dark-mode .pagination .page-link {
            background-color: #000 !important;
            border-color: #222 !important;
            color: #fff !important;
        }

        body.dark-mode .dropdown-menu {
            background-color: #0c0c0c !important;
            border: 1px solid #222 !important;
        }

        body.dark-mode .notification-item {
            background-color: #000 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 12px;
            margin: 6px;
            transition: all 0.3s ease;
        }
        
        body.dark-mode .notification-item:hover {
            border-color: rgba(255, 255, 255, 0.3) !important;
            background-color: #0a0a0a !important;
        }
        
        body.dark-mode .badge {
            background-color: #1a1a1a !important;
            color: #fff !important;
            border: 1px solid #333 !important;
        }
        
        body.dark-mode .bg-danger-soft, body.dark-mode .bg-success-soft, body.dark-mode .bg-primary-soft {
            background-color: #222 !important;
            color: #fff !important;
        }
        
        body.dark-mode .text-success, 
        body.dark-mode .bi-check, 
        body.dark-mode .bi-check-circle, 
        body.dark-mode .bi-check2-all,
        body.dark-mode .bi-check-all,
        body.dark-mode .bi-check-lg { 
            color: #a3e635 !important; /* Verde Manzana Vibrante */
        }
        
        body.dark-mode .text-primary,
        body.dark-mode .bi-search { color: #3b82f6 !important; } /* Azul Eléctrico */
        
        body.dark-mode .btn-primary {
            background-color: #2563eb !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }
        
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
            <a class="navbar-brand d-flex align-items-center" href="{{ auth()->check() ? route('home') : url('/') }}">
                @php
                    $isOwnerView = auth()->check() && auth()->user()->isOwner() && !session()->has('impersonator_id');
                    $tenant = $currentTenant ?? (\App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first());
                @endphp

                @if($isOwnerView)
                    <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="40" class="me-2 opacity-75">
                    <span class="d-none d-sm-inline fw-black ls-n1" style="color: #0f172a; font-size: 1.1rem;">COLEGIO</span>
                    <span class="d-none d-sm-inline fw-light" style="color: #64748b; font-size: 1.1rem; margin-left: 1px;">PRO</span>
                @else
                    @if($tenant && $tenant->logo)
                        <img src="{{ asset($tenant->logo) }}" alt="{{ $tenant->name }}" style="height: 110px; width: 110px; object-fit: cover;" class="me-3 rounded-circle border border-2 border-primary shadow-sm bg-white">
                    @else
                        <div class="me-3 rounded-circle shadow-sm" style="width: 110px; height: 110px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display:flex; justify-content:center; align-items:center;">
                            <i class="bi bi-shield-plus text-white fs-1"></i>
                        </div>
                    @endif
                    <span class="d-none d-sm-inline fw-black ls-n1 text-truncate" style="color: #0f172a; font-size: 1.5rem; max-width: 400px; letter-spacing: -0.5px;">{{ strtoupper($tenant->name ?? 'COLEGIO') }}</span>
                @endif
            </a>
            <button class="navbar-toggler border-0 order-last shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <i class="bi bi-list fs-1" id="mobileMenuIcon"></i>
            </button>

            <!-- Área de Usuario y Notificaciones (Siempre Visible) -->
            <div class="d-flex gap-2 align-items-center ms-auto me-2 me-lg-0 order-2 order-lg-last">
                @auth
                    <!-- Language Selector -->
                    <div class="dropdown me-1">
                        <button class="btn btn-sm btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" style="width: 38px; height: 38px;">
                            <span class="fw-bold x-small text-uppercase">{{ app()->getLocale() }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2 animate__animated animate__fadeIn">
                            <li><a class="dropdown-item rounded-3 py-2 x-small fw-bold {{ app()->getLocale() == 'es' ? 'active' : '' }}" href="{{ route('lang.switch', 'es') }}"><span class="me-2">🇪🇸</span> Español</a></li>
                            <li><a class="dropdown-item rounded-3 py-2 x-small fw-bold {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}"><span class="me-2">🇺🇸</span> English</a></li>
                            <li><a class="dropdown-item rounded-3 py-2 x-small fw-bold {{ app()->getLocale() == 'pt' ? 'active' : '' }}" href="{{ route('lang.switch', 'pt') }}"><span class="me-2">🇧🇷</span> Português</a></li>
                        </ul>
                    </div>

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
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ $currentRoute == 'home' ? 'text-primary' : 'text-muted' }}" href="{{ route('home') }}">{{ __('ui.dashboard') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'collegiates') ? 'text-primary' : 'text-muted' }}" href="{{ route('collegiates.index') }}">{{ __('ui.padron') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'billing') ? 'text-primary' : 'text-muted' }}" href="{{ route('admin.billing.index') }}">{{ __('ui.finances') }}</a></li>
                                
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'cms') ? 'text-primary' : 'text-muted' }}" href="#" data-bs-toggle="dropdown">
                                        Gestor Web (CMS)
                                    </a>
                                    <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2">
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('admin.cms.pages.index') }}"><i class="bi bi-file-earmark-richtext me-2 text-primary"></i>Páginas Dinámicas</a></li>
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('admin.cms.menus.index') }}"><i class="bi bi-list-nested me-2 text-primary"></i>Menús y Navegación</a></li>
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('admin.cms.sliders.index') }}"><i class="bi bi-images me-2 text-primary"></i>Sliders y Banners</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'ethics') ? 'text-primary' : 'text-muted' }}" href="{{ route('admin.ethics.index') }}">{{ __('ui.ethics') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'lessons') ? 'text-primary' : 'text-muted' }}" href="{{ route('student.lessons.index') }}">{{ __('ui.academy') }}</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'news') ? 'text-primary' : 'text-muted' }}" href="#" id="navbarDropdownNews" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Prensa
                                    </a>
                                    <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="navbarDropdownNews">
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('admin.news.index') }}"><i class="bi bi-newspaper me-2 text-primary"></i>Gestor de Noticias</a></li>
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#underConstructionModal"><i class="bi bi-envelope-paper me-2 text-primary"></i>Newsletters</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'compliance') || str_contains($currentRoute, 'requisitos') ? 'text-primary' : 'text-muted' }}" href="#" id="navbarDropdownDocs" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('ui.audit') }}
                                    </a>
                                    <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="navbarDropdownDocs">
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('admin.compliance.index') }}">Auditoría de Legajos</a></li>
                                        <li><a class="dropdown-item py-2 x-small fw-bold" href="{{ route('compliance_requirements.index') }}">Config. Requisitos</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'tickets') ? 'text-primary' : 'text-muted' }}" href="{{ route('tickets.index') }}"><i class="bi bi-headset me-1 text-primary"></i> SOPORTE</a></li>
                            @elseif(auth()->user()->isOwner())
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-primary" href="{{ route('admin.dashboard') }}">{{ __('ui.dashboard') }} (Owner)</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="{{ route('student.lessons.index') }}">{{ __('ui.academy') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase text-muted" href="{{ route('admin.tickets.index') }}"><i class="bi bi-headset me-1 text-primary"></i> SOPORTE GLOBAL</a></li>
                            @else
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ $currentRoute == 'home' ? 'text-primary' : 'text-muted' }}" href="{{ route('home') }}">{{ __('ui.dashboard') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'lessons') ? 'text-primary' : 'text-muted' }}" href="{{ route('student.lessons.index') }}">{{ __('ui.academy') }}</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'payment') ? 'text-primary' : 'text-muted' }}" href="{{ route('payment.index') }}">Mis Pagos</a></li>
                                <li class="nav-item"><a class="nav-link px-3 x-small fw-bold ls-1 text-uppercase {{ str_contains($currentRoute, 'compliance') ? 'text-primary' : 'text-muted' }}" href="{{ route('compliance.index') }}">{{ __('ui.compliance') }}</a></li>
                            @endif
                        @else
                        @if(isset($mainMenu) && $mainMenu)
                            @foreach($mainMenu->items as $item)
                                @if($item->children->count() > 0)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#" data-bs-toggle="dropdown">
                                            {{ $item->title }}
                                        </a>
                                        <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2 bg-dark">
                                            @foreach($item->children as $child)
                                                @if($child->is_active)
                                                    <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="{{ $child->page_id ? route('public.page', $child->page->slug) : $child->url }}" target="{{ $child->target }}">{{ $child->title }}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="{{ $item->page_id ? route('public.page', $item->page->slug) : $item->url }}" target="{{ $item->target }}">{{ $item->title }}</a></li>
                                @endif
                            @endforeach
                        @else
                            <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#">Configura tu menú en el CMS</a></li>
                        @endif
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#" data-bs-toggle="dropdown">
                                Institucional
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2 bg-dark">
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Historia</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Qué es el Colegio?</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold text-uppercase" href="#">Normativas</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Asamblea</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Consejo Directivo</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Tribunal de Disciplina</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold text-uppercase" href="#">Boletín Oficial</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Comisiones de Trabajo</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-gold" href="#">Relaciones Interinstitucionales</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#" data-bs-toggle="dropdown">
                                Ejercicio Profesional
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-2 bg-dark">
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Matriculación</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Honorarios Mínimos Éticos</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Habilitación de Consultorio</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Legislación</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Código de Ética</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Ámbitos de Actuación Profesional</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Incumbencias</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Certificado de Ética</a></li>
                                <li><a class="dropdown-item py-2 x-small fw-bold text-white hover-white" href="#">Consentimiento informado</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#">Auxiliares de Justicia</a></li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="{{ url('escuela-virtual') }}">Capacitaciones</a></li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#">Beneficios</a></li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="{{ route('news.index') }}">Noticias</a></li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#">Contacto</a></li>
                        <li class="nav-item"><a class="nav-link px-2 x-small fw-bold ls-1 text-uppercase text-dark" href="#">Denuncias</a></li>
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
        .hover-gold { color: #fde68a !important; transition: all 0.2s ease; }
        .hover-gold:hover { color: #f59e0b !important; padding-left: 6px; background-color: rgba(255,255,255,0.05) !important; border-radius: 6px; }
        .dropdown-menu.bg-dark { background-color: #1e2227 !important; border: 1px solid rgba(255,255,255,0.1) !important; }
        .transition-all { transition: all 0.3s ease; }
    </style>

    <!-- Modal En Construcción -->
    <div class="modal fade" id="underConstructionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg bg-dark text-white">
                <div class="modal-header border-bottom border-secondary py-3 px-4">
                    <h5 class="modal-title fw-bold text-warning"><i class="bi bi-cone-striped me-2"></i>Módulo en Construcción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-tools display-1 text-secondary mb-3 opacity-50"></i>
                    <h4 class="fw-bold mb-2">¡Próximamente!</h4>
                    <p class="text-white-50 mb-0">Esta funcionalidad está siendo desarrollada por nuestro equipo de ingeniería y estará disponible en las próximas actualizaciones.</p>
                </div>
                <div class="modal-footer border-0 p-4 pt-0 justify-content-center">
                    <button type="button" class="btn btn-warning rounded-pill px-5 fw-bold" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

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
        <a href="{{ route('payment.index') }}" class="text-center text-decoration-none {{ request()->routeIs('payment.*') ? 'text-primary fw-bold' : 'text-muted' }}">
            <i class="bi bi-credit-card m-0" style="font-size: 1.4rem"></i>
            <div style="font-size: 10px">Mis Pagos</div>
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
            const navbar = document.querySelector('.navbar');
            const menuIcon = document.getElementById('mobileMenuIcon');
            if (isDark) {
                body.classList.replace('light-mode', 'dark-mode');
                if(navbar) {
                    navbar.classList.remove('navbar-light', 'bg-white');
                    navbar.classList.add('navbar-dark');
                    navbar.style.backgroundColor = '#000';
                }
                if(menuIcon) {
                    menuIcon.classList.remove('text-dark');
                    menuIcon.classList.add('text-white');
                }
                if(themeIcon) {
                    themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    themeIcon.classList.replace('text-dark', 'text-warning');
                }
            } else {
                body.classList.replace('dark-mode', 'light-mode');
                if(navbar) {
                    navbar.classList.add('navbar-light', 'bg-white');
                    navbar.classList.remove('navbar-dark');
                    navbar.style.backgroundColor = '';
                }
                if(menuIcon) {
                    menuIcon.classList.remove('text-white');
                    menuIcon.classList.add('text-dark');
                }
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

        // Intercept empty links for "Under Construction" modal
        document.addEventListener('DOMContentLoaded', function() {
            const emptyLinks = document.querySelectorAll('a[href="#"]');
            const constructionModal = new bootstrap.Modal(document.getElementById('underConstructionModal'));
            
            emptyLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Only intercept if it doesn't have a data-bs-toggle attribute (like dropdowns or collapses)
                    if (!this.hasAttribute('data-bs-toggle') && !this.classList.contains('dropdown-toggle')) {
                        e.preventDefault();
                        constructionModal.show();
                    }
                });
            });
        });
    </script>
    <!-- Asistente IA de Voz 'Carina' (Burbuja Flotante Premium) -->
    @auth
    <div class="position-fixed bottom-0 end-0 mb-4 me-4" style="z-index: 1061;">
        <button id="carinaVoiceBtn" class="btn btn-primary rounded-circle shadow-lg p-0 border-4 border-white animate__animated animate__bounceIn" 
                style="width: 65px; height: 65px; background: linear-gradient(135deg, #0F172A, #2563EB); transition: all 0.3s ease;">
            <i id="carinaVoiceIcon" class="bi bi-mic-fill fs-2 text-white"></i>
        </button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const voiceBtn = document.getElementById('carinaVoiceBtn');
            const voiceIcon = document.getElementById('carinaVoiceIcon');
            
            if (!voiceBtn) return;
            
            // Verificamos soporte
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            
            if (!SpeechRecognition) {
                console.warn('Speech Recognition API no soportada en este navegador.');
                voiceBtn.style.display = 'none';
                return;
            }

            const recognition = new SpeechRecognition();
            recognition.lang = 'es-ES';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            let isRecording = false;

            voiceBtn.addEventListener('click', () => {
                if (isRecording) {
                    recognition.stop();
                    return;
                }
                
                recognition.start();
                isRecording = true;
                
                // Efecto visual de grabación
                voiceBtn.classList.add('animate__pulse', 'animate__infinite');
                voiceBtn.style.background = 'linear-gradient(135deg, #ef4444, #b91c1c)';
                voiceIcon.classList.replace('bi-mic-fill', 'bi-mic-mute-fill');
            });

            recognition.onresult = (event) => {
                const speechResult = event.results[0][0].transcript;
                console.log('Detectado:', speechResult);
                
                // Efecto de procesamiento
                voiceBtn.classList.remove('animate__pulse', 'animate__infinite');
                voiceBtn.classList.add('animate__swing');
                voiceBtn.style.background = 'linear-gradient(135deg, #eab308, #ca8a04)';
                voiceIcon.classList.replace('bi-mic-mute-fill', 'bi-hourglass-split');

                // Enviar a nuestro backend
                fetch('{{ route('ai.voice') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ text: speechResult })
                })
                .then(response => response.json())
                .then(data => {
                    // Restaurar botón
                    voiceBtn.classList.remove('animate__swing');
                    voiceBtn.style.background = 'linear-gradient(135deg, #0F172A, #2563EB)';
                    voiceIcon.classList.replace('bi-hourglass-split', 'bi-mic-fill');
                    
                    if(data.status === 'success') {
                        // Reproducir respuesta hablada
                        const utterance = new SpeechSynthesisUtterance(data.spoken_response);
                        utterance.lang = 'es-ES';
                        utterance.rate = 1.0;
                        utterance.pitch = 1.0;
                        
                        // Redirigir si Gemini mandó una URL válida
                        if(data.action_url) {
                            utterance.onend = () => {
                                window.location.href = data.action_url;
                            };
                        }
                        
                        window.speechSynthesis.speak(utterance);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    voiceBtn.style.background = 'linear-gradient(135deg, #0F172A, #2563EB)';
                    voiceIcon.classList.replace('bi-hourglass-split', 'bi-mic-fill');
                });
                
                isRecording = false;
            };

            recognition.onspeechend = () => {
                recognition.stop();
                isRecording = false;
            };

            recognition.onerror = (event) => {
                console.error('Error de reconocimiento de voz:', event.error);
                isRecording = false;
                voiceBtn.classList.remove('animate__pulse', 'animate__infinite');
                voiceBtn.style.background = 'linear-gradient(135deg, #0F172A, #2563EB)';
                voiceIcon.classList.replace('bi-mic-mute-fill', 'bi-mic-fill');
            };
        });
    </script>
    @endauth

    @yield('scripts')
</body>
</html>

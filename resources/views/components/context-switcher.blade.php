@php
    $user = auth()->user();
    if (!$user) return;
    
    $hasAdminAccess = $user->hasAnyAdminPermission();
    if (!$hasAdminAccess) return; // Si es un usuario normal sin permisos, no mostramos la barra

    $activeContext = session('active_role_context', 'user');

    $availableContexts = [
        'user' => ['label' => 'Usuario', 'icon' => 'bi-person-badge', 'color' => 'success'],
    ];

    if ($user->role === 'OWNER' || $user->role === 'ADMIN_COLEGIO' || $user->role === 'ADMIN_INTERNO' || in_array('admin_general', $user->permissions ?? [])) {
        $availableContexts['admin_general'] = ['label' => 'Administrador General', 'icon' => 'bi-diagram-3-fill', 'color' => 'primary'];
    }

    $permissionsMap = [
        'manage_users' => ['label' => 'Padrón de Usuarios', 'icon' => 'bi-people', 'color' => 'info'],
        'manage_finances' => ['label' => 'Finanzas y Cuotas', 'icon' => 'bi-wallet2', 'color' => 'warning'],
        'manage_ethics' => ['label' => 'Tribunal de Ética', 'icon' => 'bi-bank', 'color' => 'danger'],
        'manage_cms' => ['label' => 'Gestión Web / CMS', 'icon' => 'bi-globe', 'color' => 'primary'],
        'manage_academy' => ['label' => 'Academia y Cursos', 'icon' => 'bi-mortarboard', 'color' => 'primary'],
    ];

    // Evitar duplicar botones si ya es admin general (porque el admin general lo tiene todo)
    // Pero el usuario pidió que si tiene varios roles, aparezcan los botones.
    // Vamos a agregar los botones de los permisos que tenga.
    foreach ($permissionsMap as $key => $data) {
        if ($user->hasPermission($key) && !isset($availableContexts['admin_general'])) {
            $availableContexts[$key] = $data;
        }
    }
@endphp

<div class="bg-dark text-white py-2 px-3 d-flex justify-content-center align-items-center gap-2 shadow-sm" style="z-index: 1050; position: relative;">
    <span class="small fw-bold text-white-50 me-2 d-none d-md-inline"><i class="bi bi-briefcase me-1"></i> MODO DE TRABAJO:</span>
    
    @foreach($availableContexts as $key => $data)
        @php
            $isActive = $activeContext === $key;
            $btnClass = $isActive ? "btn-{$data['color']}" : "btn-outline-light border-0 text-white-50";
        @endphp
        <a href="{{ route('switch_context', $key) }}" class="btn btn-sm rounded-pill fw-bold px-3 {{ $btnClass }} shadow-sm" style="transition: all 0.2s;">
            <i class="bi {{ $data['icon'] }} me-1"></i> {{ $data['label'] }}
        </a>
    @endforeach
</div>

@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-shield-lock text-primary me-2"></i> Autoridades y Permisos</h3>
            <p class="text-muted mb-0">Gestiona los accesos y roles de la mesa directiva de la institución.</p>
        </div>
        <button class="btn btn-primary fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
            <i class="bi bi-plus-lg me-1"></i> Asignar Permisos
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-4 border-0 text-secondary fw-bold">Usuario / Autoridad</th>
                        <th class="py-3 border-0 text-secondary fw-bold">Nivel de Acceso</th>
                        <th class="py-3 border-0 text-secondary fw-bold">Módulos Asignados</th>
                        <th class="py-3 border-0 text-secondary fw-bold text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($admins as $admin)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $admin->name }}</h6>
                                    <p class="text-muted small mb-0">{{ $admin->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($admin->role === 'ADMIN_COLEGIO')
                                <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="bi bi-diagram-3-fill me-1"></i> Administrador General</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-person-lines-fill me-1"></i> Sub-Administrador</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($admin->role === 'ADMIN_COLEGIO')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 me-1 mb-1">Acceso Total</span>
                            @else
                                @php
                                    $perms = $admin->permissions ?? [];
                                    $labels = [
                                        'manage_users' => 'Padrón de Usuarios',
                                        'manage_finances' => 'Finanzas y Cuotas',
                                        'manage_ethics' => 'Tribunal de Ética',
                                        'manage_cms' => 'Gestión Web / CMS',
                                        'manage_academy' => 'Academia y Cursos'
                                    ];
                                @endphp
                                @foreach($perms as $p)
                                    @if(isset($labels[$p]))
                                        <span class="badge bg-dark px-2 py-1 me-1 mb-1 fw-normal">{{ $labels[$p] }}</span>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td class="py-3 px-4 text-end">
                            <button class="btn btn-sm btn-light border shadow-sm text-primary rounded-circle" style="width: 35px; height: 35px;" onclick="editPermissions({{ $admin->id }}, '{{ $admin->role }}', {{ json_encode($admin->permissions ?? []) }})">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            
                            @if($admin->id !== auth()->id())
                            <form action="{{ route('admin.permissions.destroy', $admin) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border shadow-sm text-danger rounded-circle ms-1" style="width: 35px; height: 35px;" onclick="return confirm('¿Estás seguro de querer revocar todos los permisos a este usuario?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-x fs-1 text-black-50 mb-3 d-block"></i>
                            <h5 class="fw-bold text-dark">No hay autoridades asignadas</h5>
                            <p>No se encontraron sub-administradores en tu institución.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Asignar Permisos -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('admin.permissions.store') }}" method="POST" id="permissionsForm">
                @csrf
                <input type="hidden" name="user_id" id="modal_user_id" value="">
                
                <div class="modal-header border-0 bg-light rounded-top-4 pb-0">
                    <h5 class="modal-title fw-bold" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-shield-check text-primary me-2"></i> Asignar Permisos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="mb-4" id="userSelectGroup">
                        <label class="form-label fw-bold small text-muted">Seleccionar Autoridad / Usuario</label>
                        <select name="user_id" class="form-select form-select-lg rounded-3 shadow-sm border-0 bg-light" id="userSelect" required>
                            <option value="">Buscar por nombre...</option>
                            @foreach($collegiates as $col)
                                <option value="{{ $col->user_id ? 'usr_'.$col->user_id : 'col_'.$col->id }}">
                                    {{ $col->last_name }}, {{ $col->first_name }} 
                                    @if(!$col->user_id) (Sin cuenta activada) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Nivel de Acceso</label>
                        
                        <div class="card border-primary bg-primary bg-opacity-10 shadow-sm rounded-4 mb-3 cursor-pointer role-card" onclick="selectRole('admin_general')" id="card_admin_general" style="cursor: pointer; transition: 0.2s;">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role_type" id="role_admin" value="admin_general" required checked>
                                    <label class="form-check-label fw-bold d-block" for="role_admin">
                                        Administrador General
                                        <span class="d-block small text-muted fw-normal mt-1">Control absoluto sobre todos los módulos. Equivalente al creador.</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 bg-light shadow-sm rounded-4 cursor-pointer role-card" onclick="selectRole('custom')" id="card_custom" style="cursor: pointer; transition: 0.2s;">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role_type" id="role_custom" value="custom" required>
                                    <label class="form-check-label fw-bold d-block" for="role_custom">
                                        Sub-Administrador (Módulos Específicos)
                                        <span class="d-block small text-muted fw-normal mt-1">Selecciona qué secciones exactas puede ver y administrar.</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="permissionsList" class="bg-white border rounded-4 p-3 d-none">
                        <h6 class="fw-bold mb-3 small text-primary">MÓDULOS PERMITIDOS</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="manage_users" id="perm_users">
                            <label class="form-check-label" for="perm_users"><i class="bi bi-people text-info me-2"></i> Padrón de Usuarios</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="manage_finances" id="perm_finances">
                            <label class="form-check-label" for="perm_finances"><i class="bi bi-wallet2 text-warning me-2"></i> Finanzas y Cuotas</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="manage_ethics" id="perm_ethics">
                            <label class="form-check-label" for="perm_ethics"><i class="bi bi-bank text-danger me-2"></i> Tribunal de Ética</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="manage_cms" id="perm_cms">
                            <label class="form-check-label" for="perm_cms"><i class="bi bi-globe text-primary me-2"></i> Gestión Web / CMS</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input perm-checkbox" type="checkbox" name="permissions[]" value="manage_academy" id="perm_academy">
                            <label class="form-check-label" for="perm_academy"><i class="bi bi-mortarboard text-success me-2"></i> Academia y Cursos</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light fw-bold text-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">Guardar Permisos</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function selectRole(role) {
        document.getElementById('role_' + (role === 'admin_general' ? 'admin' : 'custom')).checked = true;
        
        if (role === 'admin_general') {
            document.getElementById('card_admin_general').classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
            document.getElementById('card_admin_general').classList.remove('border-0', 'bg-light');
            
            document.getElementById('card_custom').classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
            document.getElementById('card_custom').classList.add('border-0', 'bg-light');
            
            document.getElementById('permissionsList').classList.add('d-none');
        } else {
            document.getElementById('card_custom').classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
            document.getElementById('card_custom').classList.remove('border-0', 'bg-light');
            
            document.getElementById('card_admin_general').classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
            document.getElementById('card_admin_general').classList.add('border-0', 'bg-light');
            
            document.getElementById('permissionsList').classList.remove('d-none');
        }
    }

    function editPermissions(userId, role, permissions) {
        // Reset form
        document.getElementById('permissionsForm').reset();
        document.querySelectorAll('.perm-checkbox').forEach(cb => cb.checked = false);
        
        // Disable user select and set hidden ID
        document.getElementById('userSelectGroup').classList.add('d-none');
        document.getElementById('userSelect').removeAttribute('required');
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_user_id').name = "user_id";

        if (role === 'ADMIN_COLEGIO') {
            selectRole('admin_general');
        } else {
            selectRole('custom');
            if (permissions && Array.isArray(permissions)) {
                permissions.forEach(p => {
                    const cb = document.querySelector(`input[value="${p}"]`);
                    if (cb) cb.checked = true;
                });
            }
        }

        var myModal = new bootstrap.Modal(document.getElementById('addPermissionModal'));
        myModal.show();
    }
</script>
@endpush

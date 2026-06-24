@extends('layouts.main')

@section('title', 'Mi Perfil | Colegio-Pro')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold mb-0 text-dark"><i class="bi bi-person-circle text-primary me-2"></i> Mi Perfil</h2>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row g-4">
                <!-- Columna Izquierda: Avatar y Estado -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="card-body text-center p-4">
                            <div class="position-relative d-inline-block mb-4">
                                @if($collegiate && $collegiate->avatar_url)
                                    <img src="{{ $collegiate->avatar_url }}" alt="Avatar" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--tenant-primary, #0d6efd);">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow" style="width: 150px; height: 150px; border: 4px solid var(--tenant-primary, #0d6efd);">
                                        <i class="bi bi-person text-secondary" style="font-size: 5rem;"></i>
                                    </div>
                                @endif
                                
                                <button type="button" class="btn btn-primary rounded-circle position-absolute bottom-0 end-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#avatarModal" style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-camera"></i>
                                </button>
                            </div>
                            
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ $user->email }}</p>
                            
                            @if($collegiate)
                            <div class="p-3 bg-light rounded-3 mt-3">
                                <p class="mb-1 small fw-bold text-muted text-uppercase">Matrícula</p>
                                <h5 class="fw-bold text-primary mb-0">#{{ $collegiate->registration_number ?? 'Pendiente' }}</h5>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Datos Personales -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="fw-bold mb-0">Datos Personales</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Nombre Completo</label>
                                        <input type="text" class="form-control bg-light" value="{{ $user->name }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Documento (DNI/RUT)</label>
                                        <input type="text" class="form-control bg-light" value="{{ $collegiate->dni ?? '' }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Teléfono de Contacto</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $collegiate->phone ?? '') }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Fecha de Nacimiento</label>
                                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $collegiate->birth_date ?? '') }}">
                                        @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-muted">Dirección Particular</label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $collegiate->address ?? '') }}">
                                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de Seguridad -->
                    <div class="card border-0 shadow-sm rounded-4 mt-4" id="security-section">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-shield-lock text-warning me-2"></i> Seguridad de la Cuenta</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('profile.password') }}" method="POST">
                                @csrf
                                
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-muted">Contraseña Actual</label>
                                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required minlength="8">
                                        @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-muted">Confirmar Nueva Contraseña</label>
                                        <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    <button type="submit" class="btn btn-warning rounded-pill px-5 fw-bold shadow-sm">
                                        Actualizar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Subir Avatar -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-camera text-primary me-2"></i> Subir Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-4">Sube una foto clara y frontal. El tamaño máximo permitido es de 5MB.</p>
                    
                    <input type="file" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                    @error('avatar') 
                        <div class="text-danger small mt-2 text-start">{{ $message }}</div> 
                    @enderror
                </div>
                <div class="modal-footer py-3 border-top bg-light-subtle">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Subir Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

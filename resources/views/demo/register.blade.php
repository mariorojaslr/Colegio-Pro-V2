@extends('layouts.main')

@section('title', 'Registro Demo | Colegio-Pro')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5" 
     style="background: radial-gradient(circle at top right, #1e293b, #020617); border-radius: 0">
    
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-xl-4">
            <!-- La Tarjeta Prestige -->
            <div class="card-prestige p-5 bg-white border-0 shadow-2xl position-relative overflow-hidden">
                <!-- Adorno sutil de marca -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="bi bi-shield-check fs-1 text-primary"></i>
                </div>

                <div class="text-center mb-5">
                    <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro Elite Logo" height="100" class="mb-4">
                    <h2 class="fw-bold mb-2" style="font-family: 'Outfit', sans-serif; color: var(--primary-color)">
                        Únase a la <span class="text-gradient-gold">Prueba</span>
                    </h2>
                    <p class="text-muted fw-bold small text-uppercase ls-2">Experiencia Profesional Exclusiva</p>
                </div>

                <form method="POST" action="{{ route('demo.register.post') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-primary mb-2 ls-1">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-4"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" class="form-control bg-light border-0 rounded-end-pill py-3 px-3 shadow-none fw-bold" placeholder="Ej: Dr. Juan Pérez" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-primary mb-2 ls-1">Correo Institucional</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-4"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-0 rounded-end-pill py-3 px-3 shadow-none fw-bold" placeholder="juan@colegiopro.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-primary mb-2 ls-1">Contraseña de Acceso</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-4"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-0 rounded-end-pill py-3 px-3 shadow-none fw-bold" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-primary mb-2 ls-1">Confirmar Identidad</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-pill ps-4"><i class="bi bi-shield-lock text-muted"></i></span>
                            <input type="password" name="password_confirmation" class="form-control bg-light border-0 rounded-end-pill py-3 px-3 shadow-none fw-bold" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-prestige w-100 py-3 rounded-pill fw-bold shadow-lg fs-5">
                        Comenzar mi Experiencia <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0 fw-medium">¿Ya es miembro institucional? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none border-bottom border-primary border-opacity-25">Inicie Sesión</a></p>
                    </div>
                </form>
            </div>
            
            <div class="text-center mt-5 text-white-50 small">
                <i class="bi bi-shield-fill-check me-2 text-accent-color"></i> Encriptación de Grado Institucional 256-bit
            </div>
        </div>
    </div>
</div>
@endsection

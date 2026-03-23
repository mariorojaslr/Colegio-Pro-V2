@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Registrar Nueva Institución</h1>
            <p class="text-muted">Inicie una nueva instancia del sistema para un colegio cliente.</p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver al Dashboard
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.schools.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            {{-- Datos de la Institución --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white mb-4">
                    <h5 class="fw-bold mb-4" style="color: var(--primary-color)">Información del Colegio</h5>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre Oficial del Colegio</label>
                            <input type="text" name="name" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: Colegio San Agustín" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Slug (Subdominio)</label>
                            <div class="input-group">
                                <input type="text" name="slug" class="form-control rounded-start-pill px-4 py-3 border-light shadow-none" placeholder="san-agustin" required>
                                <span class="input-group-text bg-light border-light text-muted px-3">.colegio-pro.cl</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Plan de Suscripción</label>
                            <select name="subscription_plan_id" class="form-select rounded-pill px-4 py-3 border-light shadow-none" required>
                                <option value="">Seleccione un plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5" style="color: var(--primary-color)">Identidad Visual (Branding)</h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color Principal</label>
                            <input type="color" name="primary_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="#0F172A" style="height: 54px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color Secundario</label>
                            <input type="color" name="secondary_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="#3B82F6" style="height: 54px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color de Acento</label>
                            <input type="color" name="accent_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="#F59E0B" style="height: 54px;">
                        </div>
                    </div>
                </div>

                {{-- Cuenta del Administrador --}}
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                    <h5 class="fw-bold mb-4" style="color: var(--primary-color)">Administrador Principal (Dueño del Colegio)</h5>
                    <p class="text-muted small">Se creará un usuario con rol de ADMINISTRADOR para este colegio.</p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre Completo</label>
                            <input type="text" name="admin_name" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Nombre completo del admin" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Correo Electrónico</label>
                            <input type="email" name="admin_email" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="admin@colegio.cl" required>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Contraseña Inicial</label>
                            <input type="password" name="admin_password" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="********" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar de Ayuda/Acciones --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 text-white" style="background: linear-gradient(135deg, #020617, #0f172a);">
                    <h5 class="fw-bold mb-4">Configuración Cloud</h5>
                    <ul class="list-unstyled d-grid gap-4 opacity-75 small">
                        <li class="d-flex align-items-start">
                            <i class="bi bi-cloud-check-fill me-3 fs-5 text-primary"></i>
                            <div>El sistema creará automáticamente un entorno aislado para los datos de esta institución.</div>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-hdd-fill me-3 fs-5 text-primary"></i>
                            <div>Se asignarán los límites de almacenamiento y tráfico según el plan seleccionado.</div>
                        </li>
                    </ul>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                        Finalizar Registro <i class="bi bi-check2-circle ms-2"></i>
                    </button>
                    <p class="text-muted small mt-3 px-3">Al registrar, se enviarán las credenciales al correo del administrador.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

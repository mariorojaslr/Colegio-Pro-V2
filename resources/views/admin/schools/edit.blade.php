@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Editar Institución: <span class="text-primary">{{ $school->name }}</span></h1>
            <p class="text-muted">Ajuste la configuración de marca y estado del colegio.</p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.schools.update', $school) }}">
        @csrf
        <div class="row g-4">
            {{-- Datos de la Institución --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white mb-4">
                    <h5 class="fw-bold mb-4" style="color: var(--primary-color)">Información General</h5>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre del Colegio</label>
                            <input type="text" name="name" value="{{ $school->name }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Slug (Subdominio)</label>
                            <div class="input-group">
                                <input type="text" name="slug" value="{{ $school->slug }}" class="form-control rounded-start-pill px-4 py-3 border-light shadow-none" required>
                                <span class="input-group-text bg-light border-light text-muted px-3">.colegio-pro.cl</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $school->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-muted small uppercase ls-1 ms-2" for="is_active">Estado Activo (Permitir acceso)</label>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5" style="color: var(--primary-color)">Identidad Visual (Branding)</h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color Principal</label>
                            <input type="color" name="primary_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="{{ $school->primary_color }}" style="height: 54px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color Secundario</label>
                            <input type="color" name="secondary_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="{{ $school->secondary_color }}" style="height: 54px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Color de Acento</label>
                            <input type="color" name="accent_color" class="form-control form-control-color w-100 rounded-4 border-light shadow-none" value="{{ $school->accent_color }}" style="height: 54px;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar de Acciones --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 bg-white">
                    <h5 class="fw-bold mb-4">Resumen de Uso</h5>
                    <ul class="list-unstyled d-grid gap-3 small">
                        <li class="d-flex justify-content-between">
                            <span class="text-muted">Plan Actual:</span>
                            <span class="badge-plan plan-{{ $school->plan_category }}">{{ strtoupper($school->plan_category) }}</span>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span class="text-muted">Usuarios:</span>
                            <span class="fw-bold text-dark">{{ $school->users()->count() }}</span>
                        </li>
                    </ul>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                        Guardar Cambios <i class="bi bi-save ms-2"></i>
                    </button>
                    <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-dark rounded-pill px-5 py-3 fw-bold w-100 mt-3 border-light opacity-75">
                         Entrar como Admin <i class="bi bi-eye ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

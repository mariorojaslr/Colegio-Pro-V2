@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Editar Plan: <span class="text-primary">{{ $plan->name }}</span></h1>
            <p class="text-muted">Ajuste las condiciones comerciales y límites operativos para este nivel de servicio.</p>
        </div>
        <div class="col-lg-auto">
            <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
                    @csrf
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre del Plan</label>
                            <input type="text" name="name" value="{{ $plan->name }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Precio ($)</label>
                            <input type="number" name="price" value="{{ (int)$plan->price }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="0">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Límite de Usuarios</label>
                            <input type="number" name="max_users" value="{{ $plan->max_users }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Disco (GB)</label>
                            <input type="number" name="max_storage" value="{{ $plan->max_storage }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="1">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Tráfico Mensual (GB)</label>
                            <input type="number" name="max_traffic" value="{{ $plan->max_traffic }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Límite de Archivos</label>
                            <input type="number" name="max_files" value="{{ $plan->max_files }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="1">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Límite de Imágenes</label>
                            <input type="number" name="max_images" value="{{ $plan->max_images }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required min="1">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-5">
                        <input class="form-check-input" type="checkbox" name="is_one_time" id="is_one_time" {{ $plan->is_one_time ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-muted small uppercase ls-1 ms-2" for="is_one_time">¿Es Pago Único? (Instalación/Setup)</label>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Características (Una por línea)</label>
                        <textarea name="features_list" class="form-control rounded-4 px-4 py-3 border-light shadow-none" rows="5" required>{{ implode("\n", $plan->features) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                        Actualizar Plan Comercial <i class="bi bi-check2-circle ms-2"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-5 h-100" style="background: linear-gradient(135deg, #020617, #0f172a); color: white;">
                <h5 class="fw-bold mb-4">Impacto del Cambio</h5>
                <ul class="list-unstyled d-grid gap-4 opacity-75 small">
                    <li class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill me-3 fs-5 text-primary text-opacity-75"></i>
                        <div>Los cambios de precio se aplicarán en el próximo ciclo de facturación de las instituciones activas.</div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-shield-lock-fill me-3 fs-5 text-primary text-opacity-75"></i>
                        <div>Los límites de usuarios y espacio se actualizan instantáneamente para todos los colegios bajo este plan.</div>
                    </li>
                    <li class="d-flex align-items-start">
                        <i class="bi bi-currency-dollar me-3 fs-5 text-primary text-opacity-75"></i>
                        <div>El precio mínimo permitido es de $25.000 mensuales según las reglas de negocio SaaS.</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

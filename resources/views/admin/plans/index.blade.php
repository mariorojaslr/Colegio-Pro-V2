@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-5 align-items-center">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Configuración Comercial de Planes</h1>
            <p class="text-muted">Defina los precios, límites y características de los niveles de servicio SaaS.</p>
        </div>
    </div>

    <div class="row g-4">
        @foreach($plans as $plan)
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-primary">{{ $plan->name }}</h5>
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-light rounded-pill px-3">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </div>
                
                <div class="mb-4">
                    <span class="display-6 fw-bold text-dark">${{ number_format($plan->price, 0, ',', '.') }}</span>
                    <span class="text-muted small">/ mes</span>
                </div>

                <ul class="list-unstyled d-grid gap-2 mb-4">
                    <li class="small fw-bold"><i class="bi bi-mortarboard me-2"></i> {{ number_format($plan->max_users, 0, ',', '.') }} Colegiados</li>
                    <li class="small fw-bold"><i class="bi bi-hdd me-2"></i> {{ $plan->max_storage }} GB Disco</li>
                    <li class="small fw-bold"><i class="bi bi-broadcast me-2 text-info"></i> {{ number_format($plan->max_traffic, 0, ',', '.') }} GB Tráfico</li>
                    <li class="small fw-bold"><i class="bi bi-file-earmark-plus me-2 text-success"></i> {{ number_format($plan->max_files, 0, ',', '.') }} Archivos</li>
                </ul>

                <div class="mt-auto">
                    <div class="text-muted small uppercase fw-bold ls-1 mb-2">Características</div>
                    <ul class="list-unstyled small d-grid gap-1">
                        @foreach($plan->features as $feature)
                        <li><i class="bi bi-check2 text-success me-1"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

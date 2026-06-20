@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-shield-check text-primary fs-3 me-3"></i>
            <h1 class="h3 fw-bold mb-0">Bóveda de Resguardo</h1>
        </div>
        <p class="text-muted">Gestión de activos y copias de seguridad de <strong>{{ auth()->user()->school->name ?? 'la Institución' }}</strong></p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Tarjeta Datos -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-database text-purple fs-4 me-2" style="color: #6f42c1;"></i>
                        <h5 class="fw-bold mb-0 text-uppercase">Datos</h5>
                    </div>
                    <p class="text-muted small mb-3">Volcado SQL completo con sentencias INSERT de:</p>
                    <ul class="text-muted small ps-3 mb-4 flex-grow-1">
                        <li>Colegiados, cuotas y deudas</li>
                        <li>Autoridades y organigrama</li>
                        <li>Configuraciones y ajustes</li>
                        <li>Registros de pagos y acuerdos</li>
                    </ul>
                    <a href="{{ route('admin.settings.vault.data') }}" class="btn btn-success rounded-3 fw-bold py-2 w-100 mt-auto">
                        DESCARGAR DATOS
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Fotos -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-image text-warning fs-4 me-2"></i>
                        <h5 class="fw-bold mb-0 text-uppercase">Fotos</h5>
                    </div>
                    <p class="text-muted small mb-3">Descarga todas las imágenes desde Bunny CDN:</p>
                    <ul class="text-muted small ps-3 mb-4 flex-grow-1">
                        <li>Avatares de colegiados</li>
                        <li>Logo institucional</li>
                        <li>Comprobantes locales (si existen)</li>
                    </ul>
                    <div class="alert alert-warning small py-2 mb-3 border-0 fw-bold" style="background-color: #fff3cd; color: #000;">
                        Puede tardar según la cantidad de fotos
                    </div>
                    <a href="{{ route('admin.settings.vault.photos') }}" class="btn btn-primary rounded-3 fw-bold py-2 w-100 mt-auto" style="background-color: #3b82f6; border: none;">
                        DESCARGAR FOTOS
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta Certificados -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-lock text-warning fs-4 me-2" style="color: #f59e0b;"></i>
                        <h5 class="fw-bold mb-0 text-uppercase">Certificados</h5>
                    </div>
                    <p class="text-muted small mb-3">Resguardo de archivos críticos de AFIP/ARCA:</p>
                    <ul class="text-muted small ps-3 mb-4 flex-grow-1">
                        <li>Certificado digital (.crt)</li>
                        <li>Clave privada (.key)</li>
                        <li>Instrucciones de restauración</li>
                    </ul>
                    <div class="alert alert-danger small py-2 mb-3 border-0" style="background-color: #fee2e2; color: #b91c1c;">
                        Guardá este archivo en lugar seguro
                    </div>
                    <a href="{{ route('admin.settings.vault.certificates') }}" class="btn btn-dark rounded-3 fw-bold py-2 w-100 mt-auto" style="background-color: #111827;">
                        DESCARGAR CERTIFICADOS
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resguardo Maestro Integral -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 text-white p-2" style="background-color: #1a202c;">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-box-seam text-warning fs-3 me-3"></i>
                    <h4 class="fw-bold mb-0">Resguardo Maestro Integral</h4>
                </div>
                <p class="mb-0 text-secondary" style="font-size: 0.95rem;">
                    Genera un único archivo consolidado con la estructura sugerida: carpetas independientes para <strong>DATOS, FOTOS y CERTIFICADOS</strong>.<br>
                    Ideal para copias de seguridad mensuales.
                </p>
            </div>
            <a href="{{ route('admin.settings.vault.master') }}" class="btn bg-white text-dark rounded-pill fw-bold px-4 py-2" style="min-width: 250px;">
                DESCARGA MAESTRA
            </a>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 d-flex">
            <i class="bi bi-lightbulb text-warning fs-3 me-3"></i>
            <div>
                <h6 class="fw-bold mb-1">¿Sabías qué?</h6>
                <p class="text-muted small mb-0">El sistema genera automáticamente un respaldo semanal de tus datos críticos, el cual puedes descargar o restaurar en cualquier momento desde esta bóveda privada.</p>
            </div>
        </div>
    </div>
</div>
@endsection

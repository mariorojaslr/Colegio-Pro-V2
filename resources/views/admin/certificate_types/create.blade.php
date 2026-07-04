@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Nuevo Trámite / Certificado</h1>
            <p class="text-muted">Configure los parámetros iniciales de su trámite. Luego podrá diseñar la plantilla visualmente.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificate_types.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('admin.certificate_types.store') }}" method="POST">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-gear-fill text-primary me-2"></i> Configuración del Trámite</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nombre del Trámite</label>
                            <input type="text" name="name" class="form-control rounded-3" required placeholder="Ej: Certificado de Ética Profesional">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Descripción del Trámite</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Breve descripción del alcance del documento..."></textarea>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Precio ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control rounded-3" value="0" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Validez (Días)</label>
                                <input type="number" name="validity_days" class="form-control rounded-3" placeholder="Vacío = Ilimitado">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Reglas y Habilitaciones</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance">
                            <label class="form-check-label small" for="reqClearance">Exigir Libre de Deuda</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics">
                            <label class="form-check-label small" for="reqEthics">Exigir Habilitación Ética</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="has_qr" value="1" id="hasQr" checked>
                            <label class="form-check-label small fw-bold text-dark" for="hasQr">Incluir QR de Validación</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_single_use" value="1" id="isSingleUse">
                            <label class="form-check-label small fw-bold text-danger" for="isSingleUse">Habilitar un único uso</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0 text-center">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 w-100 fw-bold py-2.5">Crear Trámite e Ir al Diseñador</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

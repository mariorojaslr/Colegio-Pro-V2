@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Configuración de Institución</h1>
            <p class="text-muted mb-0">Modifique los datos de contacto, ubicación y diseño de {{ $school->name }}.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.school_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="fw-bold mb-3"><i class="bi bi-building me-2 text-primary"></i> Datos Principales</h5>
                
                <div class="row mb-4">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        @if($school->logo)
                            <img src="{{ Str::startsWith($school->logo, 'http') ? $school->logo : asset($school->logo) }}" alt="Logo" class="img-fluid rounded-3 border mb-2" style="max-height: 120px;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2 mx-auto" style="height: 120px; width: 120px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                        <div class="mt-2">
                            <label class="form-label fw-bold small">Actualizar Logo</label>
                            <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">Formato PNG o JPG.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        @if($school->about_image)
                            <img src="{{ Str::startsWith($school->about_image, 'http') ? $school->about_image : asset($school->about_image) }}" alt="Nosotros" class="img-fluid rounded-3 border mb-2" style="max-height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-2 mx-auto" style="height: 120px; width: 120px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif
                        <div class="mt-2">
                            <label class="form-label fw-bold small">Imagen "Quiénes Somos"</label>
                            <input type="file" name="about_image" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">Formato PNG o JPG.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Teléfono / WhatsApp</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $school->phone) }}" placeholder="+54 9 3804 84-9706">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Correo Electrónico (Email)</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $school->email) }}" placeholder="lariojacolegiodeto@gmail.com">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Dirección Física</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $school->address) }}" placeholder="Urquiza N 742. 1 piso. Oficina A La Rioja Capital">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i> Ubicación y Mapa</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Código Plus (Google Maps)</label>
                        <input type="text" name="plus_code" class="form-control" value="{{ old('plus_code', $school->plus_code) }}" placeholder="Ej: H4JR+GV La Rioja">
                        <small class="text-muted">Si ingresas esto, se usará para ubicar el marcador exacto.</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold small">Latitud</label>
                        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $school->latitude) }}" placeholder="Ej: -29.4186368">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold small">Longitud</label>
                        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $school->longitude) }}" placeholder="Ej: -66.8578176">
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3"><i class="bi bi-palette me-2 text-primary"></i> Apariencia y Colores Institucionales</h5>
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">Color Primario</label>
                        <input type="color" name="primary_color" class="form-control form-control-color w-100 rounded-4 shadow-sm" value="{{ old('primary_color', $school->primary_color ?? '#0f172a') }}" style="height: 50px;">
                        <small class="text-muted d-block mt-1">Color principal para botones y menús.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">Color Secundario</label>
                        <input type="color" name="secondary_color" class="form-control form-control-color w-100 rounded-4 shadow-sm" value="{{ old('secondary_color', $school->secondary_color ?? '#e2e8f0') }}" style="height: 50px;">
                        <small class="text-muted d-block mt-1">Color de fondos o contrastes.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold small">Color Terciario</label>
                        <input type="color" name="tertiary_color" class="form-control form-control-color w-100 rounded-4 shadow-sm" value="{{ old('tertiary_color', $school->tertiary_color ?? '#64748b') }}" style="height: 50px;">
                        <small class="text-muted d-block mt-1">Color de acento o detalles secundarios.</small>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i> Facturación y Cuotas</h5>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Día de Facturación Automática</label>
                        <input type="number" min="1" max="28" name="billing_day" class="form-control" value="{{ old('billing_day', $school->billing_day) }}" placeholder="Ej: 7">
                        <small class="text-muted">Si se especifica, el sistema generará automáticamente las cuotas este día de cada mes.</small>
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-center mt-3 mt-md-0">
                        <div class="form-check form-switch fs-5 mt-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="autoBilling" name="auto_billing_enabled" value="1" {{ old('auto_billing_enabled', $school->auto_billing_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 ms-2" for="autoBilling">Activar Generación Automática de Cuotas</label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="fw-bold mb-3"><i class="bi bi-credit-card me-2 text-primary"></i> Pasarela de Pagos (Mercado Pago)</h5>
                <div class="row mb-3">
                    <div class="col-12 mb-3">
                        @if($school->mp_access_token)
                            <div class="alert alert-success d-flex align-items-center rounded-4 border-0 shadow-sm">
                                <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                                <div class="text-dark">
                                    <h6 class="mb-1 fw-bold text-dark">Mercado Pago Vinculado</h6>
                                    <p class="mb-0 small text-dark">Su colegio ya está conectado a Mercado Pago y listo para recibir pagos automáticamente en su billetera.</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('mercadopago.redirect') }}" class="btn btn-success btn-sm rounded-pill fw-bold text-white shadow-sm">Actualizar Vinculación</a>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning d-flex align-items-center rounded-4 border-0 shadow-sm">
                                <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning-emphasis"></i>
                                <div class="text-dark">
                                    <h6 class="mb-1 fw-bold text-dark">Mercado Pago No Vinculado</h6>
                                    <p class="mb-0 small text-dark">No podrá procesar pagos reales hasta no vincular su cuenta de Mercado Pago.</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('mercadopago.redirect') }}" class="btn btn-primary btn-sm rounded-pill fw-bold shadow-sm">Vincular Ahora</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">
                        <i class="bi bi-save me-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

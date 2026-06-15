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
                    <div class="col-md-4 text-center mb-3 mb-md-0">
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
                            <small class="text-muted d-block mt-1">Formato PNG o JPG. Max 2MB.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
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

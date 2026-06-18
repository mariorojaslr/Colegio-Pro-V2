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
                    <h5 class="fw-bold mb-4">Información General</h5>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre del Colegio</label>
                            <input type="text" name="name" value="{{ $school->name }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Slug (Subdominio)</label>
                            <input type="text" name="slug" value="{{ $school->slug }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Dominio Propio (Opcional)</label>
                            <input type="text" name="custom_domain" value="{{ $school->custom_domain }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="ej: colegio.cl">
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre Singular del Miembro</label>
                            <input type="text" name="member_singular" value="{{ $school->member_singular }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: Colegiado">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Nombre Plural del Miembro</label>
                            <input type="text" name="member_plural" value="{{ $school->member_plural }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: Colegiados">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $school->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-muted small uppercase ls-1 ms-2" for="is_active">Estado Activo (Permitir acceso)</label>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5">Identidad Visual (Branding)</h5>
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

                    <h5 class="fw-bold mb-4 mt-5">Datos de Contacto (Landing Page)</h5>
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Teléfono</label>
                            <input type="text" name="phone" value="{{ $school->phone }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: +54 9 380 4123456">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Email Público</label>
                            <input type="email" name="email" value="{{ $school->email }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="contacto@colegio.com">
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Dirección Física</label>
                            <input type="text" name="address" value="{{ $school->address }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: San Martín 123, La Rioja">
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Código de Inserción del Mapa (Google Maps Iframe)</label>
                            <textarea name="map_embed_code" class="form-control rounded-4 px-4 py-3 border-light shadow-none" rows="3" placeholder="<iframe src='...'></iframe>">{{ $school->map_embed_code }}</textarea>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-4 mt-5">Redes Sociales</h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Facebook URL</label>
                            <input type="url" name="facebook_url" value="{{ $school->facebook_url }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="https://facebook.com/...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Instagram URL</label>
                            <input type="url" name="instagram_url" value="{{ $school->instagram_url }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="https://instagram.com/...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted small uppercase mb-2 ls-1">Twitter URL</label>
                            <input type="url" name="twitter_url" value="{{ $school->twitter_url }}" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="https://twitter.com/...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar de Acciones --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-5 mb-4 bg-white">
                    <h5 class="fw-bold mb-4">Resumen de Uso</h5>
                    <ul class="list-unstyled d-grid gap-3 small">
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Plan Asignado:</span>
                            <select name="subscription_plan_id" class="form-select form-select-sm w-auto border-light shadow-none fw-bold text-dark bg-white">
                                <option value="">Seleccionar plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ ($school->activeSubscription && $school->activeSubscription->subscription_plan_id == $plan->id) ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span class="text-muted">Usuarios:</span>
                            <span class="fw-bold text-dark">{{ $school->users()->count() }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Acuerdos Particulares (Facturación) --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="bi bi-cash-coin me-2 text-success"></i> Acuerdo de Facturación
                    </h5>
                    <p class="small text-muted mb-4">Aplica bonificaciones especiales o fija un precio que reemplace el costo del plan base.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small uppercase mb-1 ls-1">Descuento (%)</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="discount_percent" class="form-control border-light shadow-none" placeholder="Ej: 100 para bonificado total" value="{{ $school->activeSubscription->discount_percent ?? '' }}" min="0" max="100">
                            <span class="input-group-text bg-light text-muted border-light">%</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small uppercase mb-1 ls-1">Precio Fijo Especial</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted border-light">$</span>
                            <input type="number" step="0.01" name="custom_price" class="form-control border-light shadow-none" placeholder="Fija un monto exacto" value="{{ $school->activeSubscription->custom_price ?? '' }}">
                        </div>
                        <div class="form-text small" style="font-size: 0.75rem;">Deja vacío para usar el precio original del plan.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-muted small uppercase mb-1 ls-1">Vencimiento del Beneficio</label>
                        <input type="datetime-local" name="discount_expires_at" class="form-control form-control-sm border-light shadow-none" value="{{ $school->activeSubscription && $school->activeSubscription->discount_expires_at ? $school->activeSubscription->discount_expires_at->format('Y-m-d\TH:i') : '' }}">
                        <div class="form-text small" style="font-size: 0.75rem;">Si no pones fecha, el acuerdo es por tiempo indefinido.</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold w-100 shadow-lg">
                        Guardar Cambios <i class="bi bi-save ms-2"></i>
                    </button>
                    <a href="{{ route('admin.impersonate', $school->id) }}" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold w-100 mt-3 border-light opacity-75">
                         Entrar como Admin <i class="bi bi-eye ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

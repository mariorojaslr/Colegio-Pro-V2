@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 bg-light-subtle">
    {{-- Breadcrumb Institucional --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-white p-3 rounded-pill shadow-sm px-4">
            <li class="breadcrumb-item small fw-bold"><a href="{{ route('collegiates.index') }}" class="text-decoration-none text-muted">Padrón</a></li>
            <li class="breadcrumb-item active small fw-bold text-primary" aria-current="page">{{ $collegiate->last_name }}, {{ $collegiate->first_name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Perfil Lateral --}}
        <div class="col-lg-3">
            <div class="card-prestige p-4 border-0 bg-white text-center shadow-sm h-100" style="border-radius: 40px">
                <div class="mb-4 position-relative d-inline-block">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                         style="width: 120px; height: 120px; background: var(--primary-color); font-size: 3rem">
                        {{ substr($collegiate->first_name, 0, 1) }}{{ substr($collegiate->last_name, 0, 1) }}
                    </div>
                    @if($collegiate->isEnabledForCertificates())
                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-4 border-white shadow-sm" style="width: 30px; height: 30px" title="Habilitado"></div>
                    @else
                        <div class="position-absolute bottom-0 end-0 bg-danger rounded-circle border border-4 border-white shadow-sm" style="width: 30px; height: 30px" title="Inhabilitado"></div>
                    @endif
                </div>
                
                <h4 class="fw-bold text-dark mb-1">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</h4>
                <p class="text-primary fw-bold small mb-4">MATRÍCULA: {{ $collegiate->registration_number }}</p>
                
                <hr class="my-4 opacity-10">
                
                <div class="d-grid gap-2">
                    @if($collegiate->isEnabledForCertificates())
                        <a href="{{ route('collegiates.certificate', $collegiate) }}" target="_blank" class="btn btn-dark rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-2"></i> Emitir Certificado
                        </a>
                    @else
                        <button class="btn btn-secondary rounded-pill py-3 fw-bold opacity-50" disabled>
                            Certificado Bloqueado
                        </button>
                        <p class="small text-danger fw-bold mt-2"><i class="bi bi-exclamation-triangle"></i> Regularizar para habilitar</p>
                    @endif
                    
                    <button class="btn btn-outline-primary rounded-pill py-2 fw-bold small mt-3">Editar Ficha <i class="bi bi-pencil ms-1"></i></button>
                </div>
            </div>
        </div>

        {{-- Contenido Principal (Pestañas) --}}
        <div class="col-lg-9">
            <div class="card-prestige p-0 border-0 bg-white shadow-sm h-100" style="border-radius: 40px">
                <div class="p-4 border-bottom">
                    <ul class="nav nav-pills nav-fill gap-2 bg-light p-2 rounded-pill" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab">Ficha General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="pills-legajo-tab" data-bs-toggle="pill" data-bs-target="#pills-legajo" type="button" role="tab">Legajo Digital</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="pills-finanzas-tab" data-bs-toggle="pill" data-bs-target="#pills-finanzas" type="button" role="tab">Situación Financiera</button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content p-5" id="pills-tabContent">
                    {{-- Tab 1: General --}}
                    <div class="tab-pane fade show active" id="pills-general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6 text-center text-md-start">
                                <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Información de Contacto</h6>
                                <div class="mb-4">
                                    <label class="small text-muted d-block mb-1">Correo Electrónico</label>
                                    <span class="fw-bold text-dark fs-5">{{ $collegiate->email }}</span>
                                </div>
                                <div class="mb-4">
                                    <label class="small text-muted d-block mb-1">Teléfono</label>
                                    <span class="fw-bold text-dark fs-5">{{ $collegiate->phone ?? 'No registrado' }}</span>
                                </div>
                                <div class="mb-0">
                                    <label class="small text-muted d-block mb-1">Documento Nacional de Identidad</label>
                                    <span class="fw-bold text-dark fs-5">{{ number_format($collegiate->dni, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 border-start ps-md-5">
                                <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Situación Institucional</h6>
                                <div class="p-4 rounded-4 bg-light border">
                                    <div class="mb-3 d-flex justify-content-between">
                                        <span class="small fw-bold text-muted">Estado Actual:</span>
                                        <span class="badge bg-primary rounded-pill px-3">{{ $collegiate->professional_situation ?? 'Activo' }}</span>
                                    </div>
                                    <div class="mb-0">
                                        <label class="small text-muted d-block mb-1">Nota de Situación</label>
                                        <p class="small text-muted italic mb-0">"{{ $collegiate->financial_situation_note ?? 'Sin observaciones registradas.' }}"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Legajo --}}
                    <div class="tab-pane fade" id="pills-legajo" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-0">
                                    <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                        <th class="py-2 px-4 border-0">Requisito Institucional</th>
                                        <th class="py-2 border-0">Estado</th>
                                        <th class="py-2 border-0">Evolución</th>
                                        <th class="py-2 text-end px-4 border-0">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requirements as $req)
                                        @php $doc = $collegiate->documents->where('compliance_requirement_id', $req->id)->first(); @endphp
                                        <tr class="border-bottom border-white border-opacity-10">
                                            <td class="py-2 px-4 border-0">
                                                <div class="fw-bold text-dark small">{{ $req->name }}</div>
                                                @if($req->is_mandatory) 
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold" style="font-size: 8px">OBLIGATORIO</span> 
                                                @endif
                                            </td>
                                            <td class="py-2 border-0">
                                                @if($doc)
                                                    <span class="badge bg-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'pending' ? 'warning' : 'danger') }} rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px">
                                                        {{ $doc->status }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px">FALTA</span>
                                                @endif
                                            </td>
                                            <td class="py-2 xx-small text-muted border-0">
                                                {{ $doc ? $doc->updated_at->format('d/m/Y') : 'Pendiente' }}
                                            </td>
                                            <td class="py-2 text-end px-4 border-0">
                                                @if($doc)
                                                    <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 28px; height: 28px; padding: 0; line-height: 26px"><i class="bi bi-eye"></i></a>
                                                @else
                                                    <button class="btn btn-sm btn-light border rounded-pill px-3 fw-bold xx-small" onclick="alert('Funcionalidad de carga manual próximamente')">Cargar <i class="bi bi-upload"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab 3: Finanzas --}}
                    <div class="tab-pane fade" id="pills-finanzas" role="tabpanel">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-wallet2 display-1 text-{{ $collegiate->is_fees_compliant ? 'success' : 'danger' }} opacity-25"></i>
                            </div>
                            <h3 class="fw-bold text-dark">Situación: {{ $collegiate->is_fees_compliant ? 'Al Día' : 'Con Deuda' }}</h3>
                            <p class="text-muted px-lg-5 mb-5 fs-5">
                                @if($collegiate->is_fees_compliant)
                                    El profesional se encuentra al corriente con el pago de sus cuotas matricularles. No se requieren acciones.
                                @else
                                    Se registra deuda pendiente en el pago de cuotas matricularles. Es necesario regularizar para permitir la emisión de certificados.
                                @endif
                            </p>
                            @if(!$collegiate->is_fees_compliant)
                                <button class="btn btn-danger rounded-pill px-5 py-3 fw-bold shadow-lg">Notificar Deuda Ahora <i class="bi bi-send ms-2"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px }
    .uppercase { text-transform: uppercase; }
    .xx-small { font-size: 10px }
    .nav-pills .nav-link.active { background-color: var(--primary-color) !important; color: white !important; }
    .nav-pills .nav-link { color: #64748b; }
    .card-prestige { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 40px; }
</style>
@endsection

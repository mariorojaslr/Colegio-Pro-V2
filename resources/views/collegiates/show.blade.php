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
                    @if($collegiate->avatar_url)
                        <img src="{{ $collegiate->avatar_url }}" alt="Avatar" class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--primary-color);">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold shadow-sm cursor-pointer" 
                             style="width: 120px; height: 120px; background: var(--primary-color); font-size: 3rem"
                             data-bs-toggle="modal" data-bs-target="#uploadAvatarModal" title="Subir Foto">
                            {{ substr($collegiate->first_name, 0, 1) }}{{ substr($collegiate->last_name, 0, 1) }}
                        </div>
                    @endif
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
                    
                    <button type="button" class="btn btn-outline-primary rounded-pill py-2 fw-bold small mt-3" data-bs-toggle="modal" data-bs-target="#editCollegiateModal">
                        Editar Ficha <i class="bi bi-pencil ms-1"></i>
                    </button>
                    
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 mt-2 mb-2" data-bs-toggle="modal" data-bs-target="#uploadAvatarModal">
                        <i class="bi bi-camera"></i> Subir/Cambiar Foto
                    </button>

                    <hr class="my-2 opacity-10">

                    <button type="button" class="btn btn-outline-danger rounded-pill py-2 fw-bold small mt-2" data-bs-toggle="modal" data-bs-target="#forcePasswordModal">
                        <i class="bi bi-key ms-1"></i> Forzar Contraseña
                    </button>
                </div>
            </div>
        </div>

        {{-- Contenido Principal (Pestañas) --}}
        <div class="col-lg-9">
            <div class="card-prestige p-0 border-0 bg-white shadow-sm h-100" style="border-radius: 40px">
                    <div class="accordion accordion-flush" id="collegiateAccordion">
                        
                        {{-- Acordeón 1: Ficha General --}}
                        <div class="accordion-item border-0 mb-3 bg-light rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="headingGeneral">
                                <button class="accordion-button bg-light fw-bold text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral" aria-expanded="false" aria-controls="collapseGeneral">
                                    <i class="bi bi-person-lines-fill me-2 text-primary"></i> Ficha General y Localización
                                </button>
                            </h2>
                            <div id="collapseGeneral" class="accordion-collapse collapse" aria-labelledby="headingGeneral" data-bs-parent="#collegiateAccordion">
                                <div class="accordion-body bg-white p-4">
                                    <div class="row g-4">
                                        <div class="col-md-6 text-center text-md-start">
                                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Información Personal y Contacto</h6>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Correo Electrónico</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->email }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Teléfono</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->phone ?? 'No registrado' }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Documento Nacional de Identidad</label>
                                                <span class="fw-bold text-dark fs-6">{{ number_format((float)$collegiate->dni, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Fecha de Nacimiento</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->birth_date ? \Carbon\Carbon::parse($collegiate->birth_date)->format('d/m/Y') : 'No registrada' }}</span>
                                            </div>
                                            <div class="mb-0">
                                                <label class="small text-muted d-block mb-1">Título / Especialidad</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->degree ?? 'Terapista Ocupacional' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 border-start ps-md-4">
                                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Lugar de Trabajo y Geolocalización</h6>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Lugares de Trabajo</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->workplaces_info ?? 'No especificado' }}</span>
                                            </div>
                                            <div class="mb-3">
                                                <label class="small text-muted d-block mb-1">Dirección Registrada</label>
                                                <span class="fw-bold text-dark fs-6">{{ $collegiate->address ?? 'No especificada' }}</span>
                                            </div>
                                            
                                            {{-- Mapa Embebido --}}
                                            <div class="rounded-3 overflow-hidden border border-secondary border-opacity-25" style="height: 200px; background: #e9ecef; position: relative;">
                                                @php
                                                    $query = $collegiate->plus_code ?: ($collegiate->latitude && $collegiate->longitude ? "{$collegiate->latitude},{$collegiate->longitude}" : $collegiate->address);
                                                @endphp
                                                @if($query)
                                                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                                        src="https://maps.google.com/maps?q={{ urlencode($query) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" allowfullscreen>
                                                    </iframe>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                        <div class="text-center">
                                                            <i class="bi bi-geo-alt fs-2 d-block mb-2"></i>
                                                            <span class="small fw-bold">Sin datos de geolocalización</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Acordeón 2: Legajo Digital (Papelería) --}}
                        <div class="accordion-item border-0 mb-3 bg-light rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="headingLegajo">
                                <button class="accordion-button bg-light fw-bold text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLegajo" aria-expanded="false" aria-controls="collapseLegajo">
                                    <i class="bi bi-folder-check me-2 text-primary"></i> Legajo Digital (Papelería)
                                    @php
                                        $reqCount = $requirements->count();
                                        $appCount = $collegiate->documents->where('status', 'approved')->count();
                                    @endphp
                                    <span class="badge bg-primary rounded-pill ms-auto me-3">{{ $appCount }} de {{ $reqCount }} Completados</span>
                                </button>
                            </h2>
                            <div id="collapseLegajo" class="accordion-collapse collapse" aria-labelledby="headingLegajo" data-bs-parent="#collegiateAccordion">
                                <div class="accordion-body bg-white p-4">
                                    {{-- Búmetro de Progreso Documental --}}
                                    @php
                                        $percent = $reqCount > 0 ? ($appCount / $reqCount) * 100 : 100;
                                        
                                        // Calcular color basado en progreso (Rojo -> Amarillo -> Verde)
                                        if ($percent < 50) {
                                            $barColor = 'bg-danger';
                                            $textColor = 'text-danger';
                                        } elseif ($percent < 100) {
                                            $barColor = 'bg-warning';
                                            $textColor = 'text-warning';
                                        } else {
                                            $barColor = 'bg-success';
                                            $textColor = 'text-success';
                                        }
                                    @endphp
                                    <div class="mb-4 bg-light rounded-4 p-4 border border-secondary border-opacity-10 text-center">
                                        <h6 class="fw-bold text-dark mb-3 uppercase ls-1">Estado de Completitud del Legajo</h6>
                                        <div class="d-flex justify-content-between align-items-end mb-2">
                                            <span class="fw-bold {{ $textColor }} fs-5">{{ $appCount }} de {{ $reqCount }}</span>
                                            <span class="fw-bold {{ $textColor }} fs-5">{{ round($percent) }}%</span>
                                        </div>
                                        <div class="progress shadow-sm" style="height: 25px; border-radius: 20px; background: #e2e8f0;">
                                            <div class="progress-bar {{ $barColor }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percent }}%; transition: width 1s ease-in-out;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2 xx-small text-muted fw-bold uppercase">
                                            <span>Incompleto</span>
                                            <span>Completado</span>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-0">
                                                <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                                    <th class="py-3 px-4 border-0" style="width: 50%;">Documento Requerido</th>
                                                    <th class="py-3 border-0 text-center" style="width: 15%;">Estado</th>
                                                    <th class="py-3 border-0 text-center" style="width: 15%;">Vencimiento</th>
                                                    <th class="py-3 text-end px-4 border-0" style="width: 20%;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($requirements as $req)
                                                    @php $doc = $collegiate->documents->where('compliance_requirement_id', $req->id)->first(); @endphp
                                                    <tr class="border-bottom border-light">
                                                        <td class="py-3 px-4 border-0">
                                                            <div class="fw-bold text-dark small">{{ $req->name }}</div>
                                                            <div class="xx-small text-muted">{{ $req->expiration_months ? "Vence cada {$req->expiration_months} meses" : "Una sola vez" }}</div>
                                                        </td>
                                                        <td class="py-3 border-0 text-center">
                                                            @if($doc && $doc->status == 'approved')
                                                                <span class="badge rounded-pill px-3 py-1 fw-bold text-uppercase" style="background-color: #198754 !important; color: white !important; font-size: 10px"><i class="bi bi-check-circle-fill me-1"></i> Aprobado</span>
                                                            @elseif($doc && $doc->status == 'pending')
                                                                <span class="badge rounded-pill px-3 py-1 fw-bold text-uppercase" style="background-color: #ffc107 !important; color: #000 !important; font-size: 10px"><i class="bi bi-clock-fill me-1"></i> En Revisión</span>
                                                            @else
                                                                <span class="badge rounded-pill px-3 py-1 fw-bold text-uppercase" style="background-color: #dc3545 !important; color: white !important; font-size: 10px"><i class="bi bi-x-circle-fill me-1"></i> Falta</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 border-0 text-center text-muted small fw-medium">
                                                            @if($doc && $doc->expires_at)
                                                                {{ \Carbon\Carbon::parse($doc->expires_at)->format('d/m/Y') }}
                                                                @if($doc->expires_at < now()) <span class="text-danger fw-bold">(Vencido)</span> @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="py-3 text-end px-4 border-0">
                                                            @if($doc)
                                                                @if($doc->file_url_back)
                                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3" data-bs-toggle="modal" data-bs-target="#viewDocModal{{ $doc->id }}">Ver Frente y Dorso</button>
                                                                    
                                                                    <div class="modal fade text-start" id="viewDocModal{{ $doc->id }}" tabindex="-1" aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                                                <div class="modal-header border-bottom-0 pb-0">
                                                                                    <h5 class="modal-title fw-bold text-dark">{{ $req->name }}</h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                                </div>
                                                                                <div class="modal-body p-4 bg-light">
                                                                                    <div class="row g-3">
                                                                                        <div class="col-md-6">
                                                                                            <div class="bg-dark rounded-4 p-2 text-center shadow-sm position-relative">
                                                                                                <span class="badge bg-dark position-absolute top-0 start-0 m-3 z-index-2 fs-6">FRENTE</span>
                                                                                                <img src="{{ $doc->file_url }}" class="img-fluid rounded-3" style="max-height: 70vh; width: auto;">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="bg-dark rounded-4 p-2 text-center shadow-sm position-relative">
                                                                                                <span class="badge bg-dark position-absolute top-0 start-0 m-3 z-index-2 fs-6">DORSO</span>
                                                                                                <img src="{{ $doc->file_url_back }}" class="img-fluid rounded-3" style="max-height: 70vh; width: auto;">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">Ver Documento</a>
                                                                @endif
                                                                
                                                                @if(auth()->user()->isOwner() || auth()->user()->role === 'ADMIN_COLEGIO')
                                                                    @if($doc->status === 'pending')
                                                                        <form action="{{ route('admin.compliance.approve', $doc->id) }}" method="POST" class="d-inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-success rounded-circle ms-1" title="Aprobar"><i class="bi bi-check-lg"></i></button>
                                                                        </form>
                                                                        <form action="{{ route('admin.compliance.reject', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de rechazar este documento?');">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-danger rounded-circle ms-1" title="Rechazar"><i class="bi bi-x-lg"></i></button>
                                                                        </form>
                                                                    @endif
                                                                @endif

                                                                @if($req->delivery_format != 'physical_only')
                                                                    <button class="btn btn-sm btn-light border rounded-circle ms-1" title="Actualizar Archivo" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $req->id }}"><i class="bi bi-arrow-repeat"></i></button>
                                                                @endif
                                                            @else
                                                                @if($req->delivery_format == 'physical_only')
                                                                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold" disabled><i class="bi bi-person-badge me-1"></i> Presencial</button>
                                                                @else
                                                                    <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $req->id }}">Subir <i class="bi bi-cloud-arrow-up ms-1"></i></button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Modales de Subida de Documentos (Debe ir fuera de la tabla para que no se rompa el diseño) --}}
                            @foreach($requirements as $req)
                                @php $doc = $collegiate->documents->where('compliance_requirement_id', $req->id)->first(); @endphp
                                @if($req->delivery_format != 'physical_only')
                                    <div class="modal fade upload-req-modal" id="uploadModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('compliance.upload', $req->id) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4 bg-body req-form">
                                                @csrf
                                                <input type="hidden" name="collegiate_id" value="{{ $collegiate->id }}">
                                                <!-- Hidden fields to hold cropped base64 if needed -->
                                                <input type="hidden" name="cropped_image" class="cropped-image-input">
                                                <input type="hidden" name="cropped_image_back" class="cropped-image-back-input">
                                                
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-body">Subir Documento</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-4 text-center">
                                                        <h6 class="fw-bold">{{ $req->name }}</h6>
                                                    </div>
                                                    
                                                    @if($req->delivery_format == 'digital_front_back')
                                                        <div class="alert alert-info bg-info bg-opacity-10 text-info border-0 small fw-bold text-center rounded-3 mb-4">
                                                            <i class="bi bi-files me-2"></i> Este documento requiere Frente y Dorso.
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-muted">Seleccionar Frente (PDF/IMG)</label>
                                                            <input type="file" name="document" class="form-control rounded-3 bg-light text-dark file-input-front" required accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-muted">Seleccionar Dorso (PDF/IMG)</label>
                                                            <input type="file" name="document_back" class="form-control rounded-3 bg-light text-dark file-input-back" required accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    @else
                                                        <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 small fw-bold text-center rounded-3 mb-4">
                                                            <i class="bi bi-shield-check me-2"></i> El archivo se almacenará de forma segura.
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small text-muted">Seleccionar Archivo (PDF/IMG)</label>
                                                            <input type="file" name="document" class="form-control rounded-3 bg-light text-dark file-input-front" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm btn-process-upload">Siguiente <i class="bi bi-arrow-right"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Acordeón 3: Situación Financiera --}}
                        <div class="accordion-item border-0 mb-3 bg-light rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="headingFinanzas">
                                <button class="accordion-button bg-light fw-bold text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinanzas" aria-expanded="false" aria-controls="collapseFinanzas">
                                    <i class="bi bi-wallet2 me-2 text-primary"></i> Situación Financiera
                                    <span class="badge bg-{{ $collegiate->is_fees_compliant ? 'success' : 'danger' }} rounded-pill ms-auto me-3">{{ $collegiate->is_fees_compliant ? 'Al Día' : 'Con Deuda' }}</span>
                                </button>
                            </h2>
                            <div id="collapseFinanzas" class="accordion-collapse collapse" aria-labelledby="headingFinanzas" data-bs-parent="#collegiateAccordion">
                                <div class="accordion-body bg-white p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold m-0 text-dark">Historial de Cuotas y Pagos</h6>
                                        <div>
                                            @if($collegiate->dues->whereIn('status', ['pending', 'overdue'])->count() > 0)
                                                <button type="button" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#refinanceModal">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Refinanciar Deuda
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#customDueModal">
                                                <i class="bi bi-file-earmark-plus me-1"></i> Generar Acuerdo / Cuota Libre
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#payInPersonModal">
                                                <i class="bi bi-cash-coin me-1"></i> Registrar Pago Presencial
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-0">
                                                <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                                    <th class="py-2 px-4 border-0">Concepto / Periodo</th>
                                                    <th class="py-2 border-0 text-center">Vencimiento</th>
                                                    <th class="py-2 border-0 text-center">Estado</th>
                                                    <th class="py-2 border-0 text-end">Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($collegiate->dues as $due)
                                                    <tr class="border-bottom border-light">
                                                        <td class="py-3 px-4 border-bottom fw-bold text-dark">
                                                            @if($due->concept)
                                                                {{ $due->concept }}
                                                            @else
                                                                {{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}
                                                            @endif
                                                        </td>
                                                        <td class="py-3 border-0 small text-muted text-center">
                                                            {{ \Carbon\Carbon::parse($due->due_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td class="py-3 border-0 text-center">
                                                            @if($due->status == 'paid')
                                                                <span class="badge bg-success rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px">PAGADO</span>
                                                            @elseif($due->status == 'pending')
                                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px">PENDIENTE</span>
                                                            @else
                                                                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 9px">VENCIDO</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 border-0 text-end fw-bold">
                                                            ${{ number_format($due->amount, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5 text-muted small fw-bold"><i class="bi bi-check-circle display-4 d-block mb-2 text-success opacity-25"></i>No hay cuotas generadas pendientes ni históricas.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Acordeón 4: Código de Ética --}}
                        <div class="accordion-item border-0 mb-3 bg-light rounded-4 overflow-hidden">
                            <h2 class="accordion-header" id="headingEtica">
                                <button class="accordion-button bg-light fw-bold text-dark collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEtica" aria-expanded="false" aria-controls="collapseEtica">
                                    <i class="bi bi-shield-check me-2 text-primary"></i> Código de Ética y Sanciones
                                    @php
                                        $sancCount = clone $collegiate->sanctions;
                                        $activeCount = $sancCount->where('status', 'active')->count();
                                    @endphp
                                    <span class="badge bg-{{ $activeCount > 0 ? 'danger' : 'success' }} rounded-pill ms-auto me-3">{{ $activeCount > 0 ? $activeCount . ' Sanción(es)' : 'Limpio' }}</span>
                                </button>
                            </h2>
                            <div id="collapseEtica" class="accordion-collapse collapse" aria-labelledby="headingEtica" data-bs-parent="#collegiateAccordion">
                                <div class="accordion-body bg-white p-0">
                                    <div class="p-3 text-end bg-light border-bottom">
                                        <button class="btn btn-outline-danger rounded-pill px-4 fw-bold shadow-sm x-small" data-bs-toggle="modal" data-bs-target="#newSanctionModal">
                                            <i class="bi bi-plus-lg me-1"></i> Cargar Infracción
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-0">
                                                <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                                    <th class="py-3 px-4 border-0">Infracción Cometida</th>
                                                    <th class="py-3 border-0 text-center">Estado</th>
                                                    <th class="py-3 border-0 text-center">Inicio</th>
                                                    <th class="py-3 border-0 text-center">Vencimiento</th>
                                                    <th class="py-3 text-end px-4 border-0">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($collegiate->sanctions as $sanc)
                                                    <tr class="border-bottom border-light">
                                                        <td class="py-3 px-4 border-0">
                                                            <div class="fw-bold text-dark small">{{ $sanc->reason }}</div>
                                                            <div class="xx-small text-muted">Falta {{ ucfirst($sanc->severity ?? 'Media') }}</div>
                                                        </td>
                                                        <td class="py-3 border-0 text-center">
                                                            @if($sanc->status == 'active')
                                                                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 10px"><i class="bi bi-exclamation-triangle-fill me-1"></i> Activa</span>
                                                            @else
                                                                <span class="badge bg-success rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 10px"><i class="bi bi-check-circle-fill me-1"></i> Cumplida / Levantada</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 border-0 text-center text-dark small fw-medium">
                                                            {{ \Carbon\Carbon::parse($sanc->start_date)->format('d/m/Y') }}
                                                        </td>
                                                        <td class="py-3 border-0 text-center text-muted small fw-medium">
                                                            {{ $sanc->end_date ? \Carbon\Carbon::parse($sanc->end_date)->format('d/m/Y') : 'Indefinido' }}
                                                        </td>
                                                        <td class="py-3 text-end px-4 border-0">
                                                            <button class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">Ver Resolución</button>
                                                            @if($sanc->status == 'active')
                                                                <button class="btn btn-sm btn-success rounded-pill px-3 ms-1 fw-bold" onclick="alert('Funcionalidad de levantar sanción aquí')">Levantar</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5 text-muted small fw-bold"><i class="bi bi-shield-check display-4 d-block mb-2 text-success opacity-25"></i>No registra ninguna infracción en su historial.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Ficha -->
<div class="modal fade" id="editCollegiateModal" tabindex="-1" aria-labelledby="editCollegiateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark" id="editCollegiateModalLabel">
                    <i class="bi bi-pencil-square me-2 text-primary"></i> Editar Ficha del Colegiado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('collegiates.update', $collegiate) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nombre(s)</label>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $collegiate->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Apellido(s)</label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $collegiate->last_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">DNI</label>
                            <input type="text" class="form-control" name="dni" value="{{ old('dni', $collegiate->dni) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nro. Matrícula</label>
                            <input type="text" class="form-control" name="registration_number" value="{{ old('registration_number', $collegiate->registration_number) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $collegiate->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Teléfono</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $collegiate->phone) }}">
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Datos Personales y Profesionales</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date', $collegiate->birth_date ? \Carbon\Carbon::parse($collegiate->birth_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Título de Especialidad</label>
                            <input type="text" class="form-control" name="degree" value="{{ old('degree', $collegiate->degree) }}" placeholder="Ej. Terapista Ocupacional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Dirección de Residencia</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address', $collegiate->address) }}" placeholder="Calle, Número, Ciudad">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Departamento (Localidad)</label>
                            <select name="city" class="form-select">
                                @php
                                    $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martín', 'Independencia', 'Rosario Vera Peñaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela'];
                                    $currentCity = old('city', $collegiate->city ?? 'Capital');
                                @endphp
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto }}" {{ $currentCity == $depto ? 'selected' : '' }}>{{ $depto }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Dirección de Trabajo</label>
                            <input type="text" class="form-control" name="workplaces_info" value="{{ old('workplaces_info', $collegiate->workplaces_info) }}" placeholder="Clínica, Hospital o Consultorio">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Ubicación GPS / Plus Code</label>
                            <input type="text" class="form-control" name="plus_code" value="{{ old('plus_code', $collegiate->plus_code) }}" placeholder="Enlace de Google Maps o Plus Code">
                        </div>
                        
                        <div class="col-12 mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Estado Institucional</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Situación / Estado Actual</label>
                            <select name="professional_situation" class="form-select" required>
                                <option value="Activo" {{ old('professional_situation', $collegiate->professional_situation ?? 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Suspendido" {{ old('professional_situation', $collegiate->professional_situation) == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                                <option value="Retirado" {{ old('professional_situation', $collegiate->professional_situation) == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                                <option value="Fallecido" {{ old('professional_situation', $collegiate->professional_situation) == 'Fallecido' ? 'selected' : '' }}>Fallecido</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Perfil de Facturación</label>
                            <select name="billing_profile" class="form-select" required>
                                <option value="mensual" {{ old('billing_profile', $collegiate->billing_profile) == 'mensual' ? 'selected' : '' }}>Mensual (Automático)</option>
                                <option value="anual" {{ old('billing_profile', $collegiate->billing_profile) == 'anual' ? 'selected' : '' }}>Anual Bonificado</option>
                                <option value="exento" {{ old('billing_profile', $collegiate->billing_profile) == 'exento' ? 'selected' : '' }}>Exento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Nota Financiera (Opcional)</label>
                            <input type="text" class="form-control" name="financial_situation_note" placeholder="Ej. Plan de pagos" value="{{ old('financial_situation_note', $collegiate->financial_situation_note) }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pago Presencial -->
<div class="modal fade" id="payInPersonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-cash-coin me-2 text-primary"></i> Registrar Pago Presencial
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.billing.pay_in_person') }}" method="POST">
                @csrf
                <input type="hidden" name="collegiate_id" value="{{ $collegiate->id }}">
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Seleccionar Cuotas a Pagar</label>
                        <div class="list-group rounded-3 shadow-sm border-0">
                            @forelse($collegiate->dues->whereIn('status', ['pending', 'overdue']) as $due)
                                <label class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <input class="form-check-input flex-shrink-0" type="checkbox" name="dues_ids[]" value="{{ $due->id }}" checked>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}</div>
                                            <div class="text-muted xx-small uppercase">Vence: {{ \Carbon\Carbon::parse($due->due_date)->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-bold shadow-sm">${{ number_format($due->amount, 0, ',', '.') }}</span>
                                </label>
                            @empty
                                <div class="p-3 text-center text-muted small">No hay cuotas pendientes.</div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Método de Pago</label>
                        <select name="payment_method" class="form-select border-0 shadow-sm rounded-3 py-2" required>
                            <option value="efectivo">Efectivo en Oficina</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                            <option value="debito">Tarjeta de Débito</option>
                            <option value="credito">Tarjeta de Crédito</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">Observaciones Adicionales</label>
                        <input type="text" class="form-control border-0 shadow-sm rounded-3 py-2" name="notes" placeholder="Ej: Recibo Nro. 1234">
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Confirmar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Subir Foto de Perfil -->
<div class="modal fade" id="uploadAvatarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-camera me-2 text-primary"></i> Subir Foto de Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('collegiates.avatar', $collegiate) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="small text-muted mb-4">Sube una foto tipo carnet (busto) donde se vea claramente el rostro, de frente y con fondo liso. Esta foto aparecerá en la credencial digital y en los certificados.</p>
                    
                    <div class="bg-light rounded-4 p-3 mb-4 d-inline-block border border-secondary border-opacity-25">
                        <span class="d-block fw-bold small text-dark mb-2">Ejemplo de foto correcta:</span>
                        <img src="{{ asset('media/photo_guide.png') }}" alt="Guía de Foto" class="img-fluid rounded-3" style="max-height: 150px; border: 2px dashed #cbd5e1;">
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold small text-muted">Seleccionar Archivo (JPG/PNG)</label>
                        <input type="file" name="avatar" class="form-control rounded-3 bg-light text-dark py-2" required accept=".jpg,.jpeg,.png">
                        <small class="text-muted d-block mt-1">Máximo 5MB.</small>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Subir Foto <i class="bi bi-cloud-arrow-up ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Refinanciación / Plan de Pagos -->
<div class="modal fade" id="refinanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3 bg-light-subtle">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-arrow-repeat me-2 text-warning"></i> Refinanciar Deuda / Plan de Pagos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('collegiates.refinance', $collegiate) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="alert alert-warning border-0 rounded-4 mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i> Seleccione las cuotas en mora que desea consolidar en un plan de pagos o pagar mediante tarjeta de crédito.
                    </div>

                    <h6 class="fw-bold mb-3">1. Seleccionar Cuotas Atrasadas</h6>
                    <div class="table-responsive mb-4 border rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr class="xx-small fw-bold text-muted uppercase">
                                    <th class="px-3 py-2 text-center" style="width: 50px;">
                                        <input class="form-check-input" type="checkbox" id="selectAllDues" checked>
                                    </th>
                                    <th class="py-2">Periodo</th>
                                    <th class="py-2 text-end px-3">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collegiate->dues->whereIn('status', ['pending', 'overdue']) as $due)
                                <tr>
                                    <td class="px-3 py-2 text-center">
                                        <input class="form-check-input due-checkbox" type="checkbox" name="due_ids[]" value="{{ $due->id }}" data-amount="{{ $due->amount }}" checked>
                                    </td>
                                    <td class="py-2 small fw-bold text-dark">
                                        Cuota {{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}
                                    </td>
                                    <td class="py-2 text-end px-3 fw-bold">
                                        ${{ number_format($due->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end fw-bold py-3">Total Seleccionado:</td>
                                    <td class="text-end px-3 fw-black fs-5 text-danger py-3" id="totalRefinanceAmount">$0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <h6 class="fw-bold mb-3">2. Opciones de Financiación</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100 position-relative">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="financing_type" id="finCard" value="external_card" required checked>
                                    <label class="form-check-label fw-bold" for="finCard">
                                        Financiación Externa (Tarjeta)
                                    </label>
                                </div>
                                <p class="small text-muted mt-2 mb-0 ms-4">Las cuotas seleccionadas se marcarán como pagadas inmediatamente. La institución ya no gestiona esta deuda.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100 position-relative">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="financing_type" id="finInternal" value="internal_plan" required>
                                    <label class="form-check-label fw-bold" for="finInternal">
                                        Plan de Pagos Institucional
                                    </label>
                                </div>
                                <p class="small text-muted mt-2 mb-0 ms-4">Las cuotas antiguas se anulan/refinancian y se generan N cuotas nuevas.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Formulario condicional para Plan de Pagos Institucional --}}
                    <div id="internalPlanFields" class="mt-4 p-4 bg-light rounded-4 border d-none">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-list-ol me-2"></i> Detalles del Nuevo Plan</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Cantidad de Cuotas</label>
                                <input type="number" name="installments" class="form-control" min="1" max="60" value="3">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Monto por Cuota ($)</label>
                                <input type="number" name="installment_amount" class="form-control" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">1er Vencimiento</label>
                                <input type="date" name="first_due_date" class="form-control" value="{{ now()->addDays(10)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0 small py-2 border-0">
                            <strong>Nota:</strong> Los siguientes vencimientos se programarán mensualmente a partir del primero.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Procesar Refinanciación <i class="bi bi-check-circle ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Refinance total calculation
        const checkboxes = document.querySelectorAll('.due-checkbox');
        const totalEl = document.getElementById('totalRefinanceAmount');
        const selectAll = document.getElementById('selectAllDues');

        function updateTotal() {
            let total = 0;
            checkboxes.forEach(cb => {
                if(cb.checked) total += parseFloat(cb.dataset.amount);
            });
            if(totalEl) {
                totalEl.innerText = '$' + total.toLocaleString('es-AR');
            }
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateTotal();
            });
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateTotal);
            });
            
            updateTotal();
        }

        // Toggle internal plan fields
        const finInternal = document.getElementById('finInternal');
        const finCard = document.getElementById('finCard');
        const internalFields = document.getElementById('internalPlanFields');
        
        function toggleFields() {
            if(finInternal && finInternal.checked) {
                internalFields.classList.remove('d-none');
            } else if(internalFields) {
                internalFields.classList.add('d-none');
            }
        }

        if(finInternal && finCard) {
            finInternal.addEventListener('change', toggleFields);
            finCard.addEventListener('change', toggleFields);
        }
    });
</script>

<!-- Modal Nueva Sanción Ética -->
<div class="modal fade" id="newSanctionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3 bg-danger bg-opacity-10">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-shield-exclamation me-2"></i> Registrar Infracción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('collegiates.sanctions.store', $collegiate) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Gravedad de la Falta</label>
                        <select name="severity" class="form-select border-2" required>
                            <option value="">Seleccione el nivel...</option>
                            <option value="leve">Falta Leve (Ej: Llamado de atención)</option>
                            <option value="media">Falta Media (Ej: Multa económica)</option>
                            <option value="grave">Falta Grave (Ej: Mala praxis - SUSPENSIÓN AUTOMÁTICA)</option>
                        </select>
                        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> Una falta GRAVE suspenderá inmediatamente la matrícula del profesional.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Motivo Resumido</label>
                        <input type="text" name="reason" class="form-control" placeholder="Ej: Cobro indebido a paciente" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Argumentación Detallada (Opcional)</label>
                        <textarea name="arguments" class="form-control" rows="3" placeholder="Detalles de la denuncia, expediente, etc."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Fecha de Inicio</label>
                            <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Fecha de Fin (Opcional)</label>
                            <input type="date" name="end_date" class="form-control">
                            <div class="form-text" style="font-size: 10px;">Dejar vacío si es permanente</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Registrar Sanción <i class="bi bi-gavel ms-1"></i></button>
                </div>
            </form>
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
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .card-prestige { transition: all 0.3s ease; }
    .table-hover tbody tr:hover { background: rgba(0,0,0,0.01); }
</style>
<!-- Modal para Cropper.js -->
<div class="modal fade" id="cropperModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Ajustar / Recortar Imagen</h5>
                <button type="button" class="btn-close cancel-crop"></button>
            </div>
            <div class="modal-body text-center p-4 d-flex flex-column" style="min-height: 75vh;">
                <div class="alert alert-info bg-info bg-opacity-10 text-info border-0 small mb-3">
                    <i class="bi bi-info-circle me-1"></i> Arrastra las esquinas para encuadrar correctamente el documento.
                </div>
                <div class="flex-grow-1 bg-dark rounded-3 d-flex align-items-center justify-content-center" style="overflow: hidden; width: 100%; min-height: 250px; height: 100%;">
                    <img id="imageToCrop" style="max-width: 100%; max-height: 100%; display: block;">
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill me-2 btn-rotate-left"><i class="bi bi-arrow-counterclockwise"></i> Rotar Izq</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill btn-rotate-right"><i class="bi bi-arrow-clockwise"></i> Rotar Der</button>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold cancel-crop">Volver</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold confirm-crop">Confirmar Recorte</button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    let currentInput = null;
    let currentForm = null;
    let isFront = true;
    const cropperModalEl = document.getElementById('cropperModal');
    let cropperModal = null;
    if(cropperModalEl) {
        cropperModal = new bootstrap.Modal(cropperModalEl);
    }
    const imageToCrop = document.getElementById('imageToCrop');

    document.querySelectorAll('.btn-process-upload').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const frontInput = form.querySelector('.file-input-front');
            const backInput = form.querySelector('.file-input-back');

            // Form validation
            if (!frontInput.value && !form.querySelector('.cropped-image-input').value) {
                form.reportValidity();
                return;
            }
            if (backInput && !backInput.value && !form.querySelector('.cropped-image-back-input').value) {
                form.reportValidity();
                return;
            }

            // Process front then back
            processImage(frontInput, form, true, function() {
                if (backInput && backInput.files.length) {
                    processImage(backInput, form, false, function() {
                        form.submit();
                    });
                } else {
                    form.submit();
                }
            });
        });
    });

    function processImage(input, form, front, onComplete) {
        if (!input || !input.files || !input.files.length) {
            onComplete();
            return;
        }

        const file = input.files[0];
        if (!file || !file.type.startsWith('image/')) {
            onComplete();
            return;
        }

        currentInput = input;
        currentForm = form;
        isFront = front;

        const reader = new FileReader();
        reader.onload = function(e) {
            imageToCrop.src = e.target.result;
            // Hide parent modal
            const parentModalEl = form.closest('.modal');
            const parentModal = bootstrap.Modal.getInstance(parentModalEl) || new bootstrap.Modal(parentModalEl);
            parentModal.hide();
            
            cropperModal.show();
            
            if (cropper) cropper.destroy();
            cropper = new Cropper(imageToCrop, {
                viewMode: 1,
                autoCropArea: 0.9,
                background: false
            });
        };
        reader.readAsDataURL(file);

        document.querySelector('.confirm-crop').onclick = function() {
            const canvas = cropper.getCroppedCanvas({
                maxWidth: 1600,
                maxHeight: 1600
            });
            const base64 = canvas.toDataURL('image/jpeg', 0.85);
            
            if (isFront) {
                currentForm.querySelector('.cropped-image-input').value = base64;
                currentInput.value = ''; // clear file to not upload big size
            } else {
                currentForm.querySelector('.cropped-image-back-input').value = base64;
                currentInput.value = '';
            }

            cropperModal.hide();
            
            // Show original modal again
            const parentModalEl = currentForm.closest('.modal');
            const parentModal = bootstrap.Modal.getInstance(parentModalEl) || new bootstrap.Modal(parentModalEl);
            parentModal.show();
            
            setTimeout(() => {
                onComplete();
            }, 300);
        };
    }

    document.querySelectorAll('.cancel-crop').forEach(btn => {
        btn.addEventListener('click', function() {
            if(cropperModal) cropperModal.hide();
            if (currentForm) {
                const parentModalEl = currentForm.closest('.modal');
                const parentModal = bootstrap.Modal.getInstance(parentModalEl) || new bootstrap.Modal(parentModalEl);
                parentModal.show();
            }
        });
    });

    const rotateLeft = document.querySelector('.btn-rotate-left');
    if(rotateLeft) rotateLeft.addEventListener('click', () => { if(cropper) cropper.rotate(-90); });
    const rotateRight = document.querySelector('.btn-rotate-right');
    if(rotateRight) rotateRight.addEventListener('click', () => { if(cropper) cropper.rotate(90); });
});
</script>
<!-- Modal Cuota Personalizada / Acuerdo -->
<div class="modal fade" id="customDueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-file-earmark-plus me-2 text-primary"></i> Generar Acuerdo de Pago / Cuota Libre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('collegiates.custom_due.store', $collegiate) }}" method="POST" id="customDueForm">
                @csrf
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Concepto Base</label>
                            <input type="text" class="form-control" id="baseConcept" placeholder="Ej. Pago Anual 2026 o Refinanciación Histórica" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Monto Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="totalAmount" placeholder="0.00" required step="0.01">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Fecha del 1er Vencimiento</label>
                            <input type="date" class="form-control" id="firstDueDate" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Cantidad de Cuotas</label>
                            <select class="form-select" id="installmentsCount">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'pago' : 'cuotas' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 mt-3 text-end">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill fw-bold" onclick="generateInstallmentRows()">
                                <i class="bi bi-arrow-down-circle me-1"></i> Proyectar Cuotas
                            </button>
                        </div>
                        
                        <div class="col-12 mt-4 pt-3 border-top" id="installmentsContainer" style="display: none;">
                            <h6 class="fw-bold mb-3 text-dark">Detalle de Cuotas (Modificable)</h6>
                            <div id="installmentsList"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btnSaveCustomDues" disabled>Guardar y Generar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Forzar Contraseña --}}
<div class="modal fade" id="forcePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom bg-danger text-white px-4 py-3" style="border-radius: 1rem 1rem 0 0;">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-key me-2"></i> Forzar Nueva Contraseña
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('collegiates.force_password', $collegiate) }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <p class="text-muted small mb-4">
                        Ingresa una nueva contraseña para <strong>{{ $collegiate->first_name }} {{ $collegiate->last_name }}</strong>. El usuario anterior será reemplazado de inmediato y podrás darle esta nueva clave para que acceda.
                    </p>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold text-dark small">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="text" name="new_password" id="new_password_input" class="form-control rounded-start-pill py-2" placeholder="Ej: Temporal2026*" required minlength="8">
                            <button type="button" class="btn btn-outline-secondary rounded-end-pill px-3" onclick="document.getElementById('new_password_input').value = Math.random().toString(36).slice(-8) + 'A*1';">
                                <i class="bi bi-arrow-clockwise"></i> Generar
                            </button>
                        </div>
                        <small class="text-muted mt-1 d-block">Mínimo 8 caracteres.</small>
                    </div>
                    
                    <div class="alert alert-warning text-start small mb-0 mt-3 rounded-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Por seguridad, recuérdale al usuario que cambie su contraseña desde "Mi Perfil" luego de ingresar.
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Forzar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function generateInstallmentRows() {
        const concept = document.getElementById('baseConcept').value;
        const totalAmount = parseFloat(document.getElementById('totalAmount').value);
        const count = parseInt(document.getElementById('installmentsCount').value);
        let firstDate = document.getElementById('firstDueDate').value;
        
        if(!concept || !totalAmount || !firstDate) {
            alert('Por favor complete Concepto, Monto Total y 1er Vencimiento antes de proyectar.');
            return;
        }

        const amountPerInstallment = (totalAmount / count).toFixed(2);
        let listHtml = '';

        let currentDate = new Date(firstDate);

        for(let i = 1; i <= count; i++) {
            // Add month for subsequent installments
            if (i > 1) {
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
            
            let dateStr = currentDate.toISOString().split('T')[0];
            let installmentConcept = count > 1 ? `${concept} (${i}/${count})` : concept;

            listHtml += `
                <div class="row g-2 mb-2 align-items-center bg-white p-2 border rounded">
                    <div class="col-md-5">
                        <input type="text" name="dues[${i}][concept]" class="form-control form-control-sm" value="${installmentConcept}" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="dues[${i}][due_date]" class="form-control form-control-sm" value="${dateStr}" required>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="dues[${i}][amount]" class="form-control" value="${amountPerInstallment}" step="0.01" required>
                        </div>
                    </div>
                </div>
            `;
        }

        document.getElementById('installmentsList').innerHTML = listHtml;
        document.getElementById('installmentsContainer').style.display = 'block';
        document.getElementById('btnSaveCustomDues').disabled = false;
    }
</script>
@endsection

@endsection

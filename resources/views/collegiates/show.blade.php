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
                    
                    <button type="button" class="btn btn-outline-primary rounded-pill py-2 fw-bold small mt-3" data-bs-toggle="modal" data-bs-target="#editCollegiateModal">
                        Editar Ficha <i class="bi bi-pencil ms-1"></i>
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
                                <div class="accordion-body bg-white p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-0">
                                                <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                                    <th class="py-3 px-4 border-0">Documento Requerido</th>
                                                    <th class="py-3 border-0 text-center">Estado</th>
                                                    <th class="py-3 border-0 text-center">Vencimiento</th>
                                                    <th class="py-3 text-end px-4 border-0">Acciones</th>
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
                                                                <span class="badge bg-success rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 10px"><i class="bi bi-check-circle-fill me-1"></i> Completado</span>
                                                            @elseif($doc && $doc->status == 'pending')
                                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 10px"><i class="bi bi-clock-fill me-1"></i> En Revisión</span>
                                                            @else
                                                                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 10px"><i class="bi bi-x-circle-fill me-1"></i> Falta</span>
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
                                                                <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3">Ver Documento</a>
                                                                <button class="btn btn-sm btn-light border rounded-circle ms-1" title="Actualizar Archivo" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $req->id }}"><i class="bi bi-arrow-repeat"></i></button>
                                                            @else
                                                                <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $req->id }}">Subir <i class="bi bi-cloud-arrow-up ms-1"></i></button>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    {{-- Modal Subida --}}
                                                    <div class="modal fade" id="uploadModal{{ $req->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <form action="{{ route('compliance.upload', $req->id) }}" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4 bg-body">
                                                                @csrf
                                                                <input type="hidden" name="collegiate_id" value="{{ $collegiate->id }}">
                                                                <div class="modal-header border-bottom-0 pb-0">
                                                                    <h5 class="modal-title fw-bold text-body">Subir Documento</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-4 text-center">
                                                                        <h6 class="fw-bold">{{ $req->name }}</h6>
                                                                    </div>
                                                                    <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 small fw-bold text-center rounded-3 mb-4">
                                                                        <i class="bi bi-shield-check me-2"></i> El archivo se almacenará de forma segura en nuestro sistema.
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold small text-muted">Seleccionar Archivo (PDF/IMG)</label>
                                                                        <input type="file" name="document_file" class="form-control rounded-3 bg-light text-dark" required accept=".pdf,.jpg,.jpeg,.png">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer border-top-0 pt-0">
                                                                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Subir Archivo</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#payInPersonModal">
                                            <i class="bi bi-cash-coin me-1"></i> Registrar Pago Presencial
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light border-0">
                                                <tr class="xx-small fw-bold text-muted uppercase ls-1">
                                                    <th class="py-2 px-4 border-0">Periodo</th>
                                                    <th class="py-2 border-0 text-center">Vencimiento</th>
                                                    <th class="py-2 border-0 text-center">Estado</th>
                                                    <th class="py-2 border-0 text-end">Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($collegiate->dues as $due)
                                                    <tr class="border-bottom border-light">
                                                        <td class="py-3 px-4 border-0 fw-bold small text-dark">
                                                            {{ \Carbon\Carbon::parse($due->due_date)->translatedFormat('F Y') }}
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
                                                            <div class="xx-small text-muted">{{ $sanc->type == 'grave' ? 'Falta Grave' : 'Falta Leve' }}</div>
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
                            <h6 class="fw-bold mb-3">Estado Institucional</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Situación / Estado Actual</label>
                            <select name="professional_situation" class="form-select" required>
                                <option value="Activo" {{ old('professional_situation', $collegiate->professional_situation ?? 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Suspendido" {{ old('professional_situation', $collegiate->professional_situation) == 'Suspendido' ? 'selected' : '' }}>Suspendido</option>
                                <option value="Retirado" {{ old('professional_situation', $collegiate->professional_situation) == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                                <option value="Fallecido" {{ old('professional_situation', $collegiate->professional_situation) == 'Fallecido' ? 'selected' : '' }}>Fallecido</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nota Financiera (Opcional)</label>
                            <input type="text" class="form-control" name="financial_situation_note" placeholder="Ej. Plan de pagos activo" value="{{ old('financial_situation_note', $collegiate->financial_situation_note) }}">
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

<style>
    .ls-1 { letter-spacing: 1px }
    .uppercase { text-transform: uppercase; }
    .xx-small { font-size: 10px }
    .nav-pills .nav-link.active { background-color: var(--primary-color) !important; color: white !important; }
    .nav-pills .nav-link { color: #64748b; }
    .card-prestige { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border-radius: 40px; }
</style>
@endsection

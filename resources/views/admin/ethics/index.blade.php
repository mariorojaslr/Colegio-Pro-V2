@extends('layouts.main')

@section('styles')
<style>
    /* Estilos Premium para Modales de Ética */
    .modal-custom-blur {
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        background-color: rgba(0, 0, 0, 0.6) !important;
    }
    
    .modal-custom-blur .modal-content {
        border-radius: 20px !important;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6) !important;
        overflow: hidden;
    }

    body.dark-mode .modal-custom-blur .modal-content {
        background-color: #000000 !important;
        border: 2px solid rgba(255, 255, 255, 0.25) !important;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.95) !important;
    }
    
    body:not(.dark-mode) .modal-custom-blur .modal-content {
        background-color: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.15) !important;
    }

    /* Título y Header del Modal */
    .modal-custom-blur .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        padding: 1.5rem 1.75rem !important;
    }
    
    body.dark-mode .modal-custom-blur .modal-header {
        border-bottom: 1.5px solid rgba(255, 255, 255, 0.15) !important;
    }

    /* Forzado de campos y bordes definidos (Límites Marcados) */
    .modal-custom-blur .form-control,
    .modal-custom-blur .form-select {
        border-radius: 12px !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.9rem !important;
        transition: all 0.25s ease-in-out !important;
    }

    body:not(.dark-mode) .modal-custom-blur .form-control,
    body:not(.dark-mode) .modal-custom-blur .form-select {
        background-color: #f8fafc !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #1e293b !important;
    }

    body:not(.dark-mode) .modal-custom-blur .form-control:focus,
    body:not(.dark-mode) .modal-custom-blur .form-select:focus {
        background-color: #ffffff !important;
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.15) !important;
    }

    body.dark-mode .modal-custom-blur .form-control,
    body.dark-mode .modal-custom-blur .form-select {
        background-color: #111111 !important;
        border: 1.5px solid rgba(255, 255, 255, 0.3) !important;
        color: #ffffff !important;
    }

    body.dark-mode .modal-custom-blur .form-control:focus,
    body.dark-mode .modal-custom-blur .form-select:focus {
        background-color: #000000 !important;
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.3) !important;
    }

    /* Labels y Leyendas */
    .modal-custom-blur label {
        font-size: 0.75rem !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 0.5rem !important;
    }

    body.dark-mode .modal-custom-blur label {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    
    body:not(.dark-mode) .modal-custom-blur label {
        color: #475569 !important;
    }

    .modal-custom-blur .text-muted {
        color: #94a3b8 !important;
    }

    body.dark-mode .modal-custom-blur .text-muted {
        color: #64748b !important;
    }

    /* Botones de acción del Modal */
    .modal-custom-blur .modal-footer {
        border-top: none !important;
        padding: 0 1.75rem 1.75rem 1.75rem !important;
    }

    /* Corregir list-group en Comisión de Ética */
    .list-group-item {
        background-color: transparent !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
    }
    
    body.dark-mode .list-group-item {
        color: #ffffff !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
    }
    
    body:not(.dark-mode) .list-group-item {
        color: #1e293b !important;
    }
</style>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card card-premium border-crystalline h-100">
            <div class="card-header bg-transparent border-bottom py-4 px-4" style="border-bottom: 2px solid rgba(255,255,255,0.4) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-black ls-n1">Comisión de Ética Profesional</h4>
                        <p class="text-secondary small mb-0">Gestión de sanciones disciplinarias e inhabilitaciones.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger rounded-pill px-4 btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#newSanctionModal">
                            <i class="bi-plus-circle-fill me-2"></i> Nueva Sanción
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <h6 class="text-uppercase small fw-bold text-secondary mb-3">Sanciones Activas / Inhabilitaciones</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="px-3 py-3 text-uppercase xx-small ls-2">Colegiado</th>
                                <th class="px-3 py-3 text-uppercase xx-small ls-2">Motivo</th>
                                <th class="px-3 py-3 text-uppercase xx-small ls-2">Período</th>
                                <th class="px-3 py-3 text-uppercase xx-small ls-2 text-center">Estado</th>
                                <th class="px-3 py-3 text-uppercase xx-small ls-2 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeSanctions as $sanction)
                            <tr class="border-bottom">
                                <td class="px-3 py-3 border-0">
                                    <h6 class="mb-0 fw-semibold text-dark">{{ $sanction->collegiate->first_name }} {{ $sanction->collegiate->last_name }}</h6>
                                    <span class="text-secondary small">M: {{ $sanction->collegiate->registration_number }}</span>
                                </td>
                                <td class="px-3 py-3 border-0">
                                    <span class="text-dark fw-medium d-block">{{ $sanction->reason }}</span>
                                    <span class="text-secondary small text-lowercase">{{ $sanction->type == 'temporary' ? 'Temporal' : 'Permanente' }}</span>
                                </td>
                                <td class="px-3 py-3 border-0">
                                    <span class="text-secondary small d-block">Desde: {{ $sanction->start_date->format('d/m/Y') }}</span>
                                    <span class="text-secondary small d-block">Hasta: {{ $sanction->end_date ? $sanction->end_date->format('d/m/Y') : 'Permanente' }}</span>
                                </td>
                                <td class="px-3 py-3 border-0 text-center">
                                    <span class="badge rounded-pill bg-danger-soft text-danger border border-danger-soft px-3" style="background: #feeaea;">
                                        <i class="bi-shield-exclamation me-1"></i> Inhabilitado
                                    </span>
                                </td>
                                <td class="px-3 py-3 border-0 text-center">
                                    <button class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#liftSanctionModal{{ $sanction->id }}">Levantar Sanción</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-secondary small">No hay sanciones activas registradas en este período.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <h6 class="text-uppercase small fw-bold text-secondary mt-5 mb-3">Historial de Resoluciones</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 opacity-75">
                         <thead class="bg-light border-0 small">
                            <tr>
                                <th class="border-0 px-3 py-2 text-uppercase fw-bold">Fecha Resuelta</th>
                                <th class="border-0 px-3 py-2 text-uppercase fw-bold">Colegiado</th>
                                <th class="border-0 px-3 py-2 text-uppercase fw-bold">Resolución</th>
                                <th class="border-0 px-3 py-2 text-uppercase fw-bold text-center">Dictamen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $item)
                            <tr>
                                <td class="px-3 py-3 text-secondary small">{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="px-3 py-3 fw-medium text-dark">{{ $item->collegiate->first_name }} {{ $item->collegiate->last_name }}</td>
                                <td class="px-3 py-3 text-secondary small">{{ $item->lifted_reason ?: $item->reason }}</td>
                                <td class="px-3 py-3 text-center">
                                    <span class="badge rounded-pill bg-light text-secondary px-3">Archivada</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha: Comisión -->
    <div class="col-12 col-xl-4">
        <div class="card card-premium border-crystalline h-100">
            <div class="card-header bg-primary text-white p-4 border-0">
                 <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-pill p-2 me-3 shadow-sm">
                        <i class="bi-people-fill text-white fs-4 px-1"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-black ls-1 uppercase small">Comisión de Ética</h6>
                        <span class="xx-small opacity-75 uppercase ls-1">Cuerpo de Veedores</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <p class="text-secondary small mb-4 fst-italic border-start border-primary border-4 ps-3">
                    "Los fallos de esta comisión rigen la ética institucional. El número de veedores es variable según lo determine el consejo."
                </p>
                
                <h6 class="text-dark small fw-bold text-uppercase mb-3">Veedores Activos de la Comisión</h6>
                @foreach($commissionMembers as $member)
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-light text-dark rounded-pill d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 32px; height: 32px;">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-0 small fw-bold">{{ $member->name }}</h6>
                        <span class="text-secondary opacity-75 small">Veedor Titular</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge rounded-pill bg-success-soft text-success small" style="background:#e1f5e6; font-size: 0.7rem;">En línea</span>
                    </div>
                </div>
                @endforeach
                
                <div class="mt-4 pt-3 border-top">
                    <button class="btn btn-outline-dark btn-sm rounded-pill w-100 mb-2" data-bs-toggle="modal" data-bs-target="#adminCommissionModal">Administrar Comisión</button>
                    <button class="btn btn-outline-dark btn-sm rounded-pill w-100 mb-3" data-bs-toggle="modal" data-bs-target="#digitalActBookModal">Ver Libro de Actas Digital</button>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-dark small fw-bold text-uppercase mb-0">Reglas de Sanción (Tipificación)</h6>
                        <button class="btn btn-sm btn-link p-0 text-primary" data-bs-toggle="modal" data-bs-target="#newRuleModal">+ Nueva</button>
                    </div>
                    <ul class="list-group list-group-flush small">
                        @foreach($rules as $rule)
                        <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center border-0">
                            <div>
                                <span class="fw-bold d-block">{{ $rule->name }}</span>
                                <span class="text-muted xx-small">{{ $rule->penalty_type }} ({{ $rule->penalty_days ? $rule->penalty_days . ' días' : 'Perm.' }})</span>
                            </div>
                            <form action="{{ route('admin.ethics.destroy_rule', $rule->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm text-danger p-0" onclick="return confirm('¿Eliminar regla?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Sanción -->
<div class="modal fade modal-custom-blur" id="newSanctionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-danger text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-shield-slash-fill me-2"></i> Registrar Fallo Disciplinario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.create_sanction') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Colegiado a Sancionar</label>
                            <select name="collegiate_id" class="form-select shadow-none custom-select-arrow" required>
                                 <option value="">Seleccionar colegiado...</option>
                                 @foreach(\App\Models\Collegiate::where('school_id', auth()->user()->school_id)->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }} ({{ $c->registration_number }})</option>
                                 @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Regla de Sanción</label>
                            <select name="rule_id" class="form-select shadow-none" required>
                                 <option value="">Seleccionar Regla...</option>
                                 @foreach($rules as $rule)
                                    <option value="{{ $rule->id }}">{{ $rule->name }} ({{ $rule->penalty_type == 'temporary' ? 'Temporal: ' . $rule->penalty_days . ' días' : 'Permanente' }})</option>
                                 @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Fecha Inicio</label>
                            <input type="date" name="start_date" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Fecha Fin</label>
                            <input type="date" name="end_date" class="form-control shadow-none">
                            <span class="xx-small text-muted d-block mt-1">Vacio = Perm.</span>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Notas Adicionales / Nro Expediente</label>
                            <input type="text" name="notes" class="form-control shadow-none" placeholder="Ej: Exp. 1234/26">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Argumentación y Fallo Detallado</label>
                            <textarea name="arguments" class="form-control shadow-none" rows="4" placeholder="Describa el dictamen de la comisión..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Confirmar Sanción</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($activeSanctions as $sanction)
<div class="modal fade modal-custom-blur" id="liftSanctionModal{{ $sanction->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-primary text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-shield-check-fill me-2"></i> Levantar Sanción Disciplinaria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.lift_sanction', $sanction->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-4">Está por restablecer la habilitación ética del colegiado <strong>{{ $sanction->collegiate->first_name }} {{ $sanction->collegiate->last_name }}</strong>. Este acto requiere respaldo documental en el acta de la comisión.</p>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase">Justificación del Levantamiento</label>
                        <textarea name="lifted_reason" class="form-control shadow-none" rows="3" placeholder="Ej: Cumplimiento de plazo o apelación aprobada..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Confirmar y Habilitar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Nueva Regla -->
<div class="modal fade modal-custom-blur" id="newRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-dark text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-file-earmark-ruled me-2"></i> Nueva Regla Disciplinaria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.store_rule') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Nombre de la Falta</label>
                            <input type="text" name="name" class="form-control shadow-none" placeholder="Ej: Falta de pago reiterada" required>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label fw-bold small text-uppercase">Gravedad (Tipo)</label>
                             <select name="penalty_type" class="form-select shadow-none" required>
                                 <option value="temporary">Temporal (Suspensión)</option>
                                 <option value="permanent">Permanente (Expulsión)</option>
                              </select>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label fw-bold small text-uppercase">Días de Sanción</label>
                             <input type="number" name="penalty_days" class="form-control shadow-none" placeholder="Ej: 30" min="1">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase">Descripción (Opcional)</label>
                            <textarea name="description" class="form-control shadow-none" rows="3" placeholder="Detalles de la regla..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4 shadow-sm">Guardar Regla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Info Administrar Comisión -->
<div class="modal fade modal-custom-blur" id="adminCommissionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-primary text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-people-fill me-2"></i> Gestión del Cuerpo de Veedores</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <p class="text-secondary small mb-4">
                    Este panel permite al Administrador del Colegio gestionar formalmente los integrantes activos que componen la <strong>Comisión de Ética Profesional del Colegio de Trabajo Social</strong>.
                </p>
                <h6 class="text-uppercase small fw-bold text-dark mb-3">Funciones del Módulo:</h6>
                <ul class="list-group list-group-flush small mb-0">
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Designación del Presidente de la Comisión de Ética.</span>
                    </li>
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Asignación y relevo de cargos de Veedores (Titulares y Suplentes).</span>
                    </li>
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Administración de vigencias y mandatos de la comisión activa.</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Info Libro de Actas Digital -->
<div class="modal fade modal-custom-blur" id="digitalActBookModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-success text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-book-half me-2"></i> Libro de Actas Digital</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-start">
                <p class="text-secondary small mb-4">
                    Permite acceder al registro oficial, inmutable y digitalizado de todas las sesiones celebradas, dictámenes firmados y resoluciones disciplinarias de la Comisión.
                </p>
                <h6 class="text-uppercase small fw-bold text-dark mb-3">Funciones del Módulo:</h6>
                <ul class="list-group list-group-flush small mb-0">
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Descarga y visualización de Actas en formato PDF foliado.</span>
                    </li>
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Integración de firma electrónica para los veedores activos.</span>
                    </li>
                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start bg-transparent">
                        <i class="bi-check-circle-fill text-success me-2 mt-1"></i>
                        <span>Historial cronológico completo de resoluciones disciplinarias dictadas.</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

@endsection

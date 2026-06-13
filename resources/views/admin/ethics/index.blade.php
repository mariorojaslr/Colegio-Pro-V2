@extends('layouts.main')

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
                    <button class="btn btn-outline-dark btn-sm rounded-pill w-100 mb-2">Administrar Comisión</button>
                    <button class="btn btn-outline-dark btn-sm rounded-pill w-100 mb-3">Ver Libro de Actas Digital</button>
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
<div class="modal fade" id="newSanctionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-danger text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-shield-slash-fill me-2"></i> Registrar Fallo Disciplinario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.create_sanction') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small text-uppercase">Colegiado a Sancionar</label>
                        <select name="collegiate_id" class="form-select border-0 bg-light rounded-3 py-2 px-3 shadow-none custom-select-arrow" required>
                             <option value="">Seleccionar colegiado...</option>
                             @foreach(\App\Models\Collegiate::where('school_id', auth()->user()->school_id)->get() as $c)
                                <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }} ({{ $c->registration_number }})</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                             <label class="form-label text-dark fw-bold small text-uppercase">Regla de Sanción</label>
                             <select name="rule_id" class="form-select border-0 bg-light rounded-3 py-2 px-3 shadow-none" required>
                                 <option value="">Seleccionar Regla...</option>
                                 @foreach($rules as $rule)
                                    <option value="{{ $rule->id }}">{{ $rule->name }}</option>
                                 @endforeach
                             </select>
                        </div>
                        <div class="col-4">
                             <label class="form-label text-dark fw-bold small text-uppercase">Fecha Inicio</label>
                             <input type="date" name="start_date" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" required>
                        </div>
                        <div class="col-4">
                             <label class="form-label text-dark fw-bold small text-uppercase">Fecha Fin</label>
                             <input type="date" name="end_date" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none">
                             <span class="xx-small text-muted">Vacio = Perm.</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small text-uppercase">Notas Adicionales / Nro Expediente</label>
                        <input type="text" name="notes" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" placeholder="Ej: Exp. 1234/26">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small text-uppercase">Argumentación y Fallo Detallado</label>
                        <textarea name="arguments" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-none" rows="4" placeholder="Describa el dictamen de la comisión..."></textarea>
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
<div class="modal fade" id="liftSanctionModal{{ $sanction->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold">Levantar Sanción Disciplinaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.lift_sanction', $sanction->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-4">Está por restablecer la habilitación ética del colegiado <strong>{{ $sanction->collegiate->first_name }} {{ $sanction->collegiate->last_name }}</strong>. Este acto requiere respaldo documental en el acta de la comisión.</p>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small text-uppercase">Justificación del Levantamiento</label>
                        <textarea name="lifted_reason" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-none" rows="3" placeholder="Ej: Cumplimiento de plazo o apelación aprobada..." required></textarea>
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
<div class="modal fade" id="newRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4 bg-dark text-white">
                <h5 class="modal-title fw-bold text-white"><i class="bi-file-earmark-ruled me-2"></i> Nueva Regla Disciplinaria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.ethics.store_rule') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small text-uppercase">Nombre de la Falta</label>
                        <input type="text" name="name" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" placeholder="Ej: Falta de pago reiterada" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                             <label class="form-label text-dark fw-bold small text-uppercase">Gravedad (Tipo)</label>
                             <select name="penalty_type" class="form-select border-0 bg-light rounded-3 py-2 px-3 shadow-none" required>
                                 <option value="temporary">Temporal (Suspensión)</option>
                                 <option value="permanent">Permanente (Expulsión)</option>
                             </select>
                        </div>
                        <div class="col-6">
                             <label class="form-label text-dark fw-bold small text-uppercase">Días de Sanción</label>
                             <input type="number" name="penalty_days" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" placeholder="Ej: 30" min="1">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold small text-uppercase">Descripción (Opcional)</label>
                        <textarea name="description" class="form-control border-0 bg-light rounded-3 px-3 py-2 shadow-none" rows="2" placeholder="Detalles de la regla..."></textarea>
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

@endsection

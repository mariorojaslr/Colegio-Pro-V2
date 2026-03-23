@extends('layouts.main')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card bg-white border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom py-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-dark fw-bold">Tribunal de Ética Profesional</h4>
                        <p class="text-secondary small mb-0">Gestión de sanciones disciplinarias, inhabilitaciones y dictámenes.</p>
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
                        <thead class="bg-light border-0">
                            <tr>
                                <th class="border-0 px-3 py-3 rounded-start text-uppercase small text-secondary fw-bold">Colegiado</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold">Motivo</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold">Período</th>
                                <th class="border-0 px-3 py-3 text-uppercase small text-secondary fw-bold text-center">Estado</th>
                                <th class="border-0 px-3 py-3 rounded-end text-uppercase small text-secondary fw-bold text-center">Acciones</th>
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
                                    <span class="text-secondary small d-block">{{ $sanction->start_date->format('d/m/Y') }}</span>
                                    <span class="text-secondary small d-block">al {{ $sanction->end_date ? $sanction->end_date->format('d/m/Y') : 'Indefinido' }}</span>
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
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden shadow-hover border-hover transition-all">
            <div class="card-header bg-dark text-white p-4">
                 <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-pill p-2 me-3 shadow-sm">
                        <i class="bi-people-fill text-white fs-4 px-1"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Cámara de Ética</h6>
                        <span class="small opacity-50">Cuerpo de Veedores</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <p class="text-secondary small mb-4 font-italic border-start border-primary border-4 ps-3">"Los fallos de esta comisión son vinculantes y restringen automáticamente la emisión de certificados de libre deuda y ética profesional."</p>
                
                <h6 class="text-dark small fw-bold text-uppercase mb-3">Veedores Activos</h6>
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
                    <button class="btn btn-outline-dark btn-sm rounded-pill w-100">Ver Libro de Actas Digital</button>
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
                        <div class="col-6">
                             <label class="form-label text-dark fw-bold small text-uppercase">Tipo de Sanción</label>
                             <select name="type" class="form-select border-0 bg-light rounded-3 py-2 px-3 shadow-none" required>
                                 <option value="temporary">Temporal</option>
                                 <option value="permanent">Permanente</option>
                             </select>
                        </div>
                        <div class="col-6">
                             <label class="form-label text-dark fw-bold small text-uppercase">Fecha Inicio</label>
                             <input type="date" name="start_date" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold small text-uppercase">Motivo Breve (Carátula)</label>
                        <input type="text" name="reason" class="form-control border-0 bg-light rounded-3 py-2 px-3 shadow-none" placeholder="Ej: Incumplimiento Grave Art. 12" required>
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
@endsection

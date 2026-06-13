@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Trámites y Certificados (Entregables)</h1>
            <p class="text-muted">Gestione los documentos valorizados que los colegiados pueden solicitar.</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newTramiteModal">
                <i class="bi bi-plus-circle me-2"></i> Nuevo Trámite
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase small fw-bold">Nombre del Trámite</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold">Precio</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-center">Reglas de Restricción</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-center">Estado</th>
                        <th class="px-4 py-3 text-uppercase small fw-bold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                    <tr>
                        <td class="px-4 py-3">
                            <h6 class="mb-0 fw-bold">{{ $type->name }}</h6>
                            <small class="text-muted">Validez: {{ $type->validity_days ? $type->validity_days . ' días' : 'Ilimitada' }}</small>
                        </td>
                        <td class="px-4 py-3 fw-bold text-success">
                            {{ $type->price > 0 ? '$' . number_format($type->price, 2) : 'Gratuito' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($type->requires_clearance) <span class="badge bg-warning text-dark"><i class="bi bi-cash"></i> Libre de Deuda</span> @endif
                            @if($type->requires_no_sanctions) <span class="badge bg-danger"><i class="bi bi-shield-x"></i> Sin Sanciones Éticas</span> @endif
                            @if(!$type->requires_clearance && !$type->requires_no_sanctions) <span class="badge bg-light text-muted">Ninguna</span> @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="badge bg-{{ $type->is_active ? 'success' : 'secondary' }} rounded-pill">{{ $type->is_active ? 'Activo' : 'Inactivo' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.certificate_types.destroy', $type) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar trámite?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay trámites definidos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="newTramiteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold">Nuevo Trámite / Certificado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.certificate_types.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Nombre del Trámite</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="Ej: Certificado de Ética">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" value="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Validez (Días)</label>
                            <input type="number" name="validity_days" class="form-control rounded-3" placeholder="Vacío = Ilimitado">
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Reglas de Restricción para solicitar</h6>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance">
                        <label class="form-check-label" for="reqClearance">
                            Exigir Libre de Deuda (Estar al día con las cuotas)
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics">
                        <label class="form-check-label" for="reqEthics">
                            Exigir Habilitación Ética (No tener sanciones vigentes)
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Trámite</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

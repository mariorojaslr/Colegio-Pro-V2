@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">Tipos de <span class="text-primary">Infracciones Éticas</span></h1>
            <p class="text-muted x-small mb-0">Define las razones por las cuales se sanciona y su duración.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm x-small" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg me-1"></i> Nueva Infracción
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted x-small text-uppercase">
                    <tr>
                        <th class="px-4 py-3">Razón / Infracción</th>
                        <th class="py-3">Gravedad</th>
                        <th class="py-3">Duración</th>
                        <th class="py-3 text-end px-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($infractions as $inf)
                        <tr class="border-bottom border-light">
                            <td class="px-4 py-3">
                                <span class="fw-bold d-block text-dark">{{ $inf->name }}</span>
                                @if($inf->description)
                                    <span class="x-small text-muted">{{ $inf->description }}</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-pill bg-{{ $inf->severity == 'grave' ? 'danger' : 'warning text-dark' }}">
                                    {{ strtoupper($inf->severity) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="fw-bold text-dark x-small">{{ $inf->duration_months ?? 'Indefinida' }} {{ $inf->duration_months ? 'meses' : '' }}</span>
                            </td>
                            <td class="py-3 text-end px-4">
                                <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#editModal{{ $inf->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('admin.ethics_infractions.destroy', $inf->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este tipo de infracción?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $inf->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('admin.ethics_infractions.update', $inf->id) }}" method="POST" class="modal-content border-0 shadow rounded-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold text-dark">Editar Infracción</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label x-small fw-bold text-muted uppercase">Nombre de la Infracción</label>
                                            <input type="text" name="name" class="form-control form-control-sm rounded-3" value="{{ $inf->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label x-small fw-bold text-muted uppercase">Descripción (Opcional)</label>
                                            <textarea name="description" class="form-control form-control-sm rounded-3">{{ $inf->description }}</textarea>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label x-small fw-bold text-muted uppercase">Gravedad</label>
                                                <select name="severity" class="form-select form-select-sm rounded-3" required>
                                                    <option value="leve" {{ $inf->severity == 'leve' ? 'selected' : '' }}>Leve</option>
                                                    <option value="grave" {{ $inf->severity == 'grave' ? 'selected' : '' }}>Grave</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label x-small fw-bold text-muted uppercase">Duración (Meses)</label>
                                                <input type="number" name="duration_months" class="form-control form-control-sm rounded-3" value="{{ $inf->duration_months }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light rounded-pill x-small fw-bold px-3" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary rounded-pill x-small fw-bold px-4">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-slash fs-1 d-block mb-3 opacity-25"></i>
                                <span class="fw-bold">Aún no has definido infracciones éticas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.ethics_infractions.store') }}" method="POST" class="modal-content border-0 shadow rounded-4">
            @csrf
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Nueva Infracción Ética</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label x-small fw-bold text-muted uppercase">Nombre de la Infracción</label>
                    <input type="text" name="name" class="form-control form-control-sm rounded-3" placeholder="Ej: Ejercicio ilegal" required>
                </div>
                <div class="mb-3">
                    <label class="form-label x-small fw-bold text-muted uppercase">Descripción (Opcional)</label>
                    <textarea name="description" class="form-control form-control-sm rounded-3" placeholder="Detalles de la infracción"></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted uppercase">Gravedad</label>
                        <select name="severity" class="form-select form-select-sm rounded-3" required>
                            <option value="leve">Leve</option>
                            <option value="grave">Grave</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label x-small fw-bold text-muted uppercase">Duración (Meses)</label>
                        <input type="number" name="duration_months" class="form-control form-control-sm rounded-3" placeholder="Ej: 12">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill x-small fw-bold px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill x-small fw-bold px-4">Guardar Infracción</button>
            </div>
        </form>
    </div>
</div>

<style>
    .uppercase { text-transform: uppercase; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection

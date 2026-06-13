@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0">Comisión Directiva</h1>
            <p class="text-muted">Gestione las autoridades del colegio para que se muestren en el organigrama público.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addMemberModal">
            <i class="bi bi-plus-circle me-2"></i> Nueva Autoridad
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 mb-4">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small uppercase">
                        <tr>
                            <th class="ps-4 py-3">Nombre</th>
                            <th class="py-3">Cargo (Rol)</th>
                            <th class="py-3">Departamento/Sección</th>
                            <th class="py-3">Tipo</th>
                            <th class="text-end pe-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $m)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $m->image_path }}" class="rounded-circle me-3 border" style="width: 40px; height: 40px; object-fit: cover;">
                                    <span class="fw-bold">{{ $m->name }}</span>
                                </div>
                            </td>
                            <td>{{ $m->role }}</td>
                            <td>{{ $m->department }}</td>
                            <td>
                                @if($m->is_substitute)
                                    <span class="badge bg-secondary">Suplente</span>
                                @else
                                    <span class="badge bg-primary">Titular</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.cms.board_members.destroy', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Seguro que desea eliminar esta autoridad?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No hay autoridades registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar -->
<div class="modal fade" id="addMemberModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold">Nueva Autoridad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.cms.board_members.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nombre Completo</label>
                        <input type="text" name="name" class="form-control rounded-pill" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold">Cargo (Ej: Presidenta)</label>
                            <input type="text" name="role" class="form-control rounded-pill" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold">Sección (Ej: Junta Ejecutiva)</label>
                            <input type="text" name="department" class="form-control rounded-pill" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">URL de Foto (Opcional)</label>
                        <input type="url" name="image_path" class="form-control rounded-pill" placeholder="https://...">
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold">Orden (Menor es arriba)</label>
                            <input type="number" name="order" value="0" class="form-control rounded-pill">
                        </div>
                        <div class="col-6 d-flex align-items-end pb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_substitute" id="isSub">
                                <label class="form-check-label text-muted small" for="isSub">Es suplente</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

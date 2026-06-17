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
                                    <img src="{{ $m->collegiate ? $m->collegiate->avatar_url : $m->image_path }}" class="rounded-circle me-3 border" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name) }}&background=random'">
                                    <span class="fw-bold fs-6">{{ $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name }}</span>
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
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" data-bs-toggle="modal" data-bs-target="#editMemberModal{{ $m->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.cms.board_members.destroy', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('¿Seguro que desea eliminar esta autoridad?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Editar -->
                        <div class="modal fade" id="editMemberModal{{ $m->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="fw-bold">Editar Autoridad</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.cms.board_members.update', $m->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-bold">Colegiado (Legajo)</label>
                                                <select name="collegiate_id" class="form-select rounded-pill" required>
                                                    <option value="">Seleccione colegiado...</option>
                                                    @foreach($collegiates as $col)
                                                        <option value="{{ $col->id }}" {{ $m->collegiate_id == $col->id ? 'selected' : '' }}>
                                                            {{ $col->last_name }}, {{ $col->first_name }} (Mat: {{ $col->registration_number }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <label class="form-label text-muted small fw-bold">Departamento/Sección</label>
                                                    <select name="department" class="form-select rounded-pill" required>
                                                        @foreach($departments as $dept => $roles)
                                                            <option value="{{ $dept }}" {{ $m->department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label text-muted small fw-bold">Cargo</label>
                                                    <select name="role" class="form-select rounded-pill" required>
                                                        <!-- Option values should be dynamically populated via JS based on Department, but for simplicity we list common ones or keep it as text if JS is not available. To ensure standard roles, we will use a mixed approach. -->
                                                        <option value="{{ $m->role }}" selected>{{ $m->role }}</option>
                                                        <option value="Presidente">Presidente</option>
                                                        <option value="Vicepresidente">Vicepresidente</option>
                                                        <option value="Secretaria">Secretaria</option>
                                                        <option value="Tesorera">Tesorera</option>
                                                        <option value="1er Vocal">1er Vocal</option>
                                                        <option value="2do Vocal">2do Vocal</option>
                                                        <option value="Vocal">Vocal</option>
                                                        <option value="Titular">Titular</option>
                                                        <option value="Suplente">Suplente</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <label class="form-label text-muted small fw-bold">Orden</label>
                                                    <input type="number" name="order" class="form-control rounded-pill" value="{{ $m->order }}">
                                                </div>
                                                <div class="col-6 d-flex align-items-end pb-2">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="is_substitute" id="isSubEdit{{ $m->id }}" {{ $m->is_substitute ? 'checked' : '' }}>
                                                        <label class="form-check-label text-muted small" for="isSubEdit{{ $m->id }}">Es suplente</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold">Actualizar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                <form action="{{ route('admin.cms.board_members.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Colegiado (Legajo)</label>
                        <select name="collegiate_id" class="form-select rounded-pill" required>
                            <option value="">Seleccione colegiado...</option>
                            @foreach($collegiates as $col)
                                <option value="{{ $col->id }}">
                                    {{ $col->last_name }}, {{ $col->first_name }} (Mat: {{ $col->registration_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold">Departamento/Sección</label>
                            <select name="department" class="form-select rounded-pill" required>
                                <option value="">Seleccione...</option>
                                @foreach($departments as $dept => $roles)
                                    <option value="{{ $dept }}">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted small fw-bold">Cargo</label>
                            <select name="role" class="form-select rounded-pill" required>
                                <option value="">Seleccione cargo...</option>
                                <option value="Presidente">Presidente</option>
                                <option value="Vicepresidente">Vicepresidente</option>
                                <option value="Secretaria">Secretaria</option>
                                <option value="Tesorera">Tesorera</option>
                                <option value="1er Vocal">1er Vocal</option>
                                <option value="2do Vocal">2do Vocal</option>
                                <option value="Vocal">Vocal</option>
                                <option value="Titular">Titular</option>
                                <option value="Suplente">Suplente</option>
                            </select>
                        </div>
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

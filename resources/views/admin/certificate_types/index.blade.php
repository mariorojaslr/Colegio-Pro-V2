@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Trámites y Certificados (Entregables)</h1>
            <p class="text-muted">Gestione los documentos valorizados que los colegiados pueden solicitar.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificate_types.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i> Nuevo Trámite
            </a>
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
                            <small class="text-muted">
                                @if($type->is_single_use)
                                    <span class="text-danger fw-bold"><i class="bi bi-1-circle"></i> Un solo uso</span>
                                @elseif($type->validity_days)
                                    Validez: {{ $type->validity_days }} días
                                @else
                                    Validez Ilimitada
                                @endif
                            </small>
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
                            <a href="{{ route('admin.certificate_types.preview', $type) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Vista Previa"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.certificate_types.edit', $type) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.certificate_types.destroy', $type) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Eliminar trámite?')" title="Eliminar"><i class="bi bi-trash"></i></button>
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


@endsection

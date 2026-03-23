@extends('layouts.admin')

@section('header', 'Gestión de Academia Global')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Cursos y Lecciones Disponibles</h5>
                    <p class="text-muted small mb-0">Administre el catálogo educativo global del sistema.</p>
                </div>
                <a href="{{ route('admin.academy.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-2"></i> Crear Nuevo Curso
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-muted small text-uppercase ls-1">
                        <tr>
                            <th>Curso / Portada</th>
                            <th>Institución</th>
                            <th>Categoría</th>
                            <th>Inversión</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessons as $lesson)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 me-3 overflow-hidden shadow-sm" style="width: 60px; height: 40px;">
                                        <img src="{{ $lesson->thumbnail_url ?? 'https://via.placeholder.com/60x40' }}" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $lesson->title }}</div>
                                        <div class="xx-small text-muted text-uppercase fw-bold">{{ $lesson->lecturer ?? 'Docente no asignado' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                    {{ $lesson->school->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold text-uppercase px-2" style="font-size: 10px;">
                                    {{ $lesson->category ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">${{ number_format($lesson->price, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($lesson->is_published)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1" style="font-size: 10px;">
                                        <i class="bi bi-check-circle me-1"></i> PUBLICADO
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold px-2 py-1" style="font-size: 10px;">
                                        <i class="bi bi-pause-circle me-1"></i> BORRADOR
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.academy.edit', $lesson->id) }}" class="btn btn-light btn-sm rounded-circle p-2 shadow-sm">
                                        <i class="bi bi-pencil small"></i>
                                    </a>
                                    <form action="{{ route('admin.academy.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este curso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light text-danger btn-sm rounded-circle p-2 shadow-sm">
                                            <i class="bi bi-trash small"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="bi bi-display fs-1 opacity-25 d-block mb-3"></i>
                                No hay cursos creados todavía.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

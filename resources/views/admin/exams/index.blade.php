@extends('layouts.admin')

@section('header', 'Exámenes de la Academia')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Evaluaciones y Certificación</h5>
                    <p class="text-muted small mb-0">Gestione los exámenes finales para los cursos de la academia.</p>
                </div>
                <a href="{{ route('admin.exams.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-2"></i> Crear Examen
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-muted small text-uppercase ls-1">
                        <tr>
                            <th>Examen / Título</th>
                            <th>Curso Vinculado</th>
                            <th>Preguntas</th>
                            <th>Aprobación</th>
                            <th>Límite de Tiempo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $exam->title }}</div>
                                <div class="xx-small text-muted text-uppercase fw-bold">ID: #{{ $exam->id }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                                    {{ $exam->lesson->title }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ $exam->questions->count() }}</span>
                                <small class="text-muted">ítems</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $exam->passing_score }}% <small class="text-muted fw-normal">mínimo</small></div>
                            </td>
                            <td>
                                @if($exam->time_limit)
                                    <span class="text-dark fw-medium"><i class="bi bi-clock me-1 text-muted"></i> {{ $exam->time_limit }} min</span>
                                @else
                                    <span class="text-muted">Ilimitado</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-light btn-sm rounded-circle p-2 shadow-sm" title="Editar Preguntas">
                                        <i class="bi bi-list-check small"></i>
                                    </a>
                                    <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este examen?')">
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
                                <i class="bi bi-file-earmark-check fs-1 opacity-25 d-block mb-3"></i>
                                No hay exámenes creados todavía.
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

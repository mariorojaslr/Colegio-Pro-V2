@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-5 fw-bold mb-1" style="font-family: 'Outfit', sans-serif">Base de <span class="text-primary">Conocimiento AI</span></h1>
            <p class="text-muted fs-5">Entrena a tu asistente virtual para responder preguntas frecuentes automáticamente.</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addKnowledgeModal">
                <i class="bi bi-plus-lg me-2"></i> Nuevo Conocimiento
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3 text-muted x-small text-uppercase ls-1">Pregunta Origen</th>
                        <th class="border-0 px-4 py-3 text-muted x-small text-uppercase ls-1">Palabras Clave (Keywords)</th>
                        <th class="border-0 px-4 py-3 text-muted x-small text-uppercase ls-1">Respuesta</th>
                        <th class="border-0 px-4 py-3 text-muted x-small text-uppercase ls-1">Estado</th>
                        <th class="border-0 px-4 py-3 text-muted x-small text-uppercase ls-1 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($knowledges as $knowledge)
                    <tr>
                        <td class="px-4 py-3 fw-bold text-dark">{{ $knowledge->question }}</td>
                        <td class="px-4 py-3"><span class="badge bg-secondary rounded-pill bg-opacity-10 text-secondary border border-secondary">{{ $knowledge->keywords ?? 'Sin palabras clave' }}</span></td>
                        <td class="px-4 py-3 small text-muted text-truncate" style="max-width: 250px;">{{ $knowledge->answer ?? 'Sin respuesta aún' }}</td>
                        <td class="px-4 py-3">
                            @if($knowledge->status === 'learned')
                                <span class="badge bg-success rounded-pill bg-opacity-10 text-success border border-success"><i class="bi bi-robot me-1"></i> Aprendido</span>
                            @else
                                <span class="badge bg-warning rounded-pill bg-opacity-10 text-warning border border-warning"><i class="bi bi-clock me-1"></i> Pendiente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#editKnowledgeModal{{ $knowledge->id }}">
                                <i class="bi bi-pencil"></i> Enseñar
                            </button>
                            <form action="{{ route('admin.chatbot.destroy', $knowledge) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('¿Seguro que deseas eliminar este conocimiento?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-robot display-4 opacity-25 mb-3 d-block"></i>
                            <p>El bot aún no ha aprendido nada.<br>Añade conocimientos manualmente o espera a que los usuarios pregunten.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Edit Modals (Outside table to prevent layout breaking) --}}
    @foreach($knowledges as $knowledge)
    <div class="modal fade" id="editKnowledgeModal{{ $knowledge->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg rounded-4" action="{{ route('admin.chatbot.update', $knowledge) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold"><i class="bi bi-robot text-primary me-2"></i> Enseñar al Bot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light-subtle">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Pregunta del Usuario</label>
                        <input type="text" class="form-control" value="{{ $knowledge->question }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Palabras Clave (separadas por coma)</label>
                        <input type="text" name="keywords" class="form-control" value="{{ $knowledge->keywords }}" required placeholder="ej. matricula, costo, inscripcion">
                        <small class="text-muted">Si el usuario menciona alguna de estas palabras, el bot dará esta respuesta.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">La Respuesta del Bot</label>
                        <textarea name="answer" class="form-control" rows="4" required placeholder="Escribe aquí lo que el bot debe contestar...">{{ $knowledge->answer }}</textarea>
                    </div>
                </div>
                <div class="modal-footer py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Guardar Aprendizaje</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addKnowledgeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg rounded-4" action="{{ route('admin.chatbot.store') }}" method="POST">
            @csrf
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-primary me-2"></i> Añadir Conocimiento Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light-subtle">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Pregunta Ejemplo</label>
                    <input type="text" name="question" class="form-control" required placeholder="ej. ¿Cuánto cuesta la matrícula?">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Palabras Clave (separadas por coma)</label>
                    <input type="text" name="keywords" class="form-control" required placeholder="ej. costo, matricula, precio, pagar">
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Respuesta del Bot</label>
                    <textarea name="answer" class="form-control" rows="4" required placeholder="Escribe la respuesta aquí..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-3">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Añadir al Bot</button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('header', 'Editor de Examen: ' . $exam->title)

@section('content')
<div class="row g-4 mb-5">
    {{-- Configuración Base --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px;">
            <div class="p-4 bg-white border-bottom">
                <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Configuración</h5>
            </div>
            <form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-4 bg-white">
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Título</label>
                        <input type="text" name="title" class="form-control rounded-3 border-light-subtle shadow-none" value="{{ $exam->title }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">% Aprobación</label>
                            <input type="number" name="passing_score" class="form-control rounded-3 border-light-subtle shadow-none" value="{{ $exam->passing_score }}" required>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Tiempo (Min)</label>
                            <input type="number" name="time_limit" class="form-control rounded-3 border-light-subtle shadow-none" value="{{ $exam->time_limit }}">
                        </div>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="bg-light p-3 rounded-4">
                        <div class="xx-small text-muted fw-bold uppercase ls-1 mb-1 text-primary">Curso Asociado</div>
                        <div class="fw-bold text-dark small">{{ $exam->lesson->title }}</div>
                    </div>
                </div>
                <div class="card-footer p-4 bg-light border-0 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm">Actualizar Base</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Constructor de Preguntas --}}
    <div class="col-lg-8">
        {{-- Listado de Preguntas --}}
        <h6 class="text-primary xx-small fw-bold ls-2 uppercase mb-3">BANCO DE PREGUNTAS ({{ $exam->questions->count() }})</h6>
        
        @foreach($exam->questions as $index => $question)
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-black" style="width: 35px; height: 35px; font-size: 14px">
                            {{ $index + 1 }}
                        </div>
                        <h6 class="fw-bold m-0 h5 text-dark">{{ $question->question_text }}</h6>
                    </div>
                    <form action="{{ route('admin.exams.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta pregunta?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light text-danger btn-sm rounded-pill px-3 fw-bold xx-small shadow-none">
                            <i class="bi bi-trash me-1"></i> ELIMINAR
                        </button>
                    </form>
                </div>

                <div class="ps-5">
                    <div class="row g-3">
                        @foreach($question->options as $option)
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 border {{ $option->is_correct ? 'border-success bg-success bg-opacity-5' : 'border-light-subtle bg-light' }} d-flex align-items-center gap-3">
                                    <i class="bi {{ $option->is_correct ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                                    <span class="small fw-medium {{ $option->is_correct ? 'text-success fw-bold' : 'text-dark' }}">{{ $option->option_text }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Nueva Pregunta --}}
        <div class="card border-0 shadow-sm rounded-4 p-0 bg-white overflow-hidden border border-primary border-3 border-top-0 border-end-0 border-bottom-0">
            <div class="p-4 bg-white border-bottom">
                <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-plus-circle me-2 text-primary"></i> Añadir Pregunta</h5>
            </div>
            <form action="{{ route('admin.exams.questions.store', $exam->id) }}" method="POST">
                @csrf
                <div class="p-4">
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Enunciado de la Pregunta</label>
                        <textarea name="question_text" rows="3" class="form-control rounded-4 border-light-subtle shadow-none" placeholder="Escriba aquí el enunciado..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Puntos</label>
                        <input type="number" name="points" class="form-control rounded-pill border-light-subtle shadow-none w-25" value="1" required>
                    </div>

                    <h6 class="text-primary xx-small fw-bold ls-2 uppercase mb-3">OPCIONES DE RESPUESTA</h6>
                    <div class="row g-3">
                        @for($i=0; $i<4; $i++)
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-text bg-white border-light-subtle rounded-start-pill">
                                        <input class="form-check-input" type="radio" name="correct_option" value="{{ $i }}" {{ $i==0 ? 'checked' : '' }} required>
                                    </div>
                                    <input type="text" name="options[]" class="form-control border-light-subtle shadow-none rounded-end-pill" placeholder="Opción {{ $i+1 }}..." {{ $i<2 ? 'required' : '' }}>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="p-4 bg-light text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        Guardar Pregunta <i class="bi bi-check2 ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

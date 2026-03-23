@extends('layouts.admin')

@section('header', 'Nuevo Examen de Evaluación')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="p-4 bg-white border-bottom">
                <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Configuración Base</h5>
            </div>
            <form action="{{ route('admin.exams.store') }}" method="POST">
                @csrf
                <div class="card-body p-4 bg-white">
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Vincular al Curso</label>
                        <select name="lesson_id" class="form-select rounded-3 border-light-subtle shadow-none" required>
                            <option value="">Seleccione un curso...</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                        <div class="xx-small text-muted mt-2 uppercase ls-1"><i class="bi bi-info-circle me-1"></i> Solo aparecen cursos que no tienen examen asignado.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Título del Examen</label>
                        <input type="text" name="title" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: Examen Final de Gestión Judicial" required>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">% Aprobación</label>
                                <div class="input-group">
                                    <input type="number" name="passing_score" class="form-control rounded-start-3 border-light-subtle shadow-none text-center" value="60" required>
                                    <span class="input-group-text bg-light border-light-subtle fw-bold">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Límite Tiempo</label>
                                <div class="input-group">
                                    <input type="number" name="time_limit" class="form-control rounded-start-3 border-light-subtle shadow-none text-center" placeholder="--">
                                    <span class="input-group-text bg-light border-light-subtle fw-bold">min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer p-4 bg-light border-0 d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        Continuar <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

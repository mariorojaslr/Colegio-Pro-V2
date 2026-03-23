@extends('layouts.admin')

@section('header', isset($lesson) ? 'Editar Curso' : 'Crear Nuevo Curso')

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <form action="{{ isset($lesson) ? route('admin.academy.update', $lesson->id) : route('admin.academy.store') }}" method="POST">
            @csrf
            @if(isset($lesson)) @method('PUT') @endif

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="p-4 bg-white border-bottom">
                    <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Información General del Curso</h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Título del Curso</label>
                                <input type="text" name="title" class="form-control rounded-3 border-light-subtle shadow-none py-2" placeholder="Ej: Gestión Judicial 4.0" value="{{ old('title', $lesson->title ?? '') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Descripción Detallada</label>
                                <textarea name="description" rows="5" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Describa el contenido y objetivos del curso...">{{ old('description', $lesson->description ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Institución Dueña</label>
                                <select name="school_id" class="form-select rounded-3 border-light-subtle shadow-none" required>
                                    <option value="">Seleccione una institución...</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('school_id', $lesson->school_id ?? '') == $school.id ? 'selected' : '' }}>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Categoría</label>
                                <input type="text" name="category" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: Gestión, Tecnología..." value="{{ old('category', $lesson->category ?? '') }}">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Inversión (Pesos)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-light-subtle">$</span>
                                    <input type="number" name="price" class="form-control rounded-end-3 border-light-subtle shadow-none" placeholder="0" value="{{ old('price', $lesson->price ?? '0') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="p-4 bg-white border-bottom">
                    <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Detalles de Cursado y Apariencia</h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">URL de Portada (Unsplash/CDN)</label>
                                <input type="url" name="thumbnail_url" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="https://..." value="{{ old('thumbnail_url', $lesson->thumbnail_url ?? '') }}">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Docente / Expositor</label>
                                <input type="text" name="lecturer" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: Dra. Elena Blanco" value="{{ old('lecturer', $lesson->lecturer ?? '') }}">
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Duración (Descripción)</label>
                                <input type="text" name="duration" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: 4 Semanas / 20 Horas" value="{{ old('duration', $lesson->duration ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Principales Beneficios</label>
                                <textarea name="benefit" rows="4" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: Certificación oficial, materiales incluidos...">{{ old('benefit', $lesson->benefit ?? '') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Fecha de Inicio</label>
                                        <input type="text" name="start_date" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="Ej: 15 May" value="{{ old('start_date', $lesson->start_date ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1">Video ID (Bunny.net)</label>
                                        <input type="text" name="bunny_video_id" class="form-control rounded-3 border-light-subtle shadow-none" placeholder="ID de Library/Video" value="{{ old('bunny_video_id', $lesson->bunny_video_id ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" {{ old('is_published', $lesson->is_published ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark" for="is_published">Publicar Curso</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_live" id="is_live" {{ old('is_live', $lesson->is_live ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark" for="is_live">Es Clase en Vivo</label>
                        </div>
                    </div>
                    
                    <div id="liveUrlContainer" class="mt-4 {{ old('is_live', $lesson->is_live ?? false) ? '' : 'd-none' }}">
                        <label class="small fw-bold text-muted mb-1 text-uppercase ls-1 text-danger">URL Transmisión Vivo (Zoom/Meet/YouTube)</label>
                        <input type="url" name="live_url" class="form-control rounded-3 border-danger-subtle shadow-none" value="{{ old('live_url', $lesson->live_url ?? '') }}">
                    </div>
                </div>
                <div class="card-footer p-4 bg-light border-0 d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.academy.index') }}" class="btn btn-light rounded-pill px-4 fw-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        {{ isset($lesson) ? 'Actualizar Curso' : 'Guardar Curso' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

            @if(isset($lesson))
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 border border-primary border-3 border-top-0 border-end-0 border-bottom-0">
                    <div class="p-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;"><i class="bi bi-file-earmark-arrow-down me-2 text-primary"></i> Recursos y Materiales</h5>
                        <span class="badge bg-light text-primary border fw-bold px-3 py-2 rounded-pill shadow-sm">
                            {{ $lesson->resources->count() }} RECURSOS
                        </span>
                    </div>
                    <div class="card-body p-4 bg-white">
                        {{-- Listado de Recursos Existentes --}}
                        <div class="row g-3 mb-5">
                            @forelse($lesson->resources as $resource)
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 bg-light border border-light-subtle d-flex justify-content-between align-items-center transition-all hover-shadow">
                                        <div class="d-flex align-items-center gap-3">
                                            @php
                                                $icon = match($resource->type) {
                                                    'pdf' => 'bi-file-pdf text-danger',
                                                    'slides' => 'bi-file-earmark-ppt text-orange',
                                                    'link' => 'bi-link-45deg text-primary',
                                                    'word' => 'bi-file-earmark-word text-info',
                                                    'excel' => 'bi-file-earmark-excel text-success',
                                                    default => 'bi-file-earmark'
                                                };
                                            @endphp
                                            <i class="bi {{ $icon }} fs-3"></i>
                                            <div>
                                                <div class="small fw-bold text-dark">{{ $resource->title }}</div>
                                                <div class="xx-small text-muted text-uppercase fw-bold">{{ $resource->type }}</div>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.lesson_resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este recurso?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-light btn-sm rounded-circle text-danger p-2 shadow-sm"><i class="bi bi-trash small"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4 text-muted small opacity-50">No hay materiales cargados.</div>
                            @endforelse
                        </div>

                        {{-- Formulario para añadir Recurso --}}
                        <div class="bg-light p-4 rounded-4 border border-light-subtle">
                            <h6 class="xx-small fw-bold ls-2 uppercase text-primary mb-4">Añadir Nuevo Material / Diapositivas</h6>
                            <form action="{{ route('admin.lesson_resources.store', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="xx-small fw-bold text-muted mb-2 text-uppercase ls-1">Título del Material</label>
                                        <input type="text" name="title" class="form-control rounded-pill border-light-subtle shadow-none xx-small fw-bold" placeholder="Ej: PPT Clase 01" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="xx-small fw-bold text-muted mb-2 text-uppercase ls-1">Tipo</label>
                                        <select name="type" id="resType" class="form-select rounded-pill border-light-subtle shadow-none xx-small fw-bold" required>
                                            <option value="pdf">PDF (Lectura)</option>
                                            <option value="slides">Slides (PowerPoint)</option>
                                            <option value="word">Word (Documento)</option>
                                            <option value="excel">Excel (Planilla)</option>
                                            <option value="link">Link Externo (URL)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="fileInputContainer">
                                        <label class="xx-small fw-bold text-muted mb-2 text-uppercase ls-1">Archivo (Máx 20MB)</label>
                                        <input type="file" name="file" class="form-control rounded-pill border-light-subtle shadow-none xx-small fw-bold">
                                    </div>
                                    <div class="col-md-4 d-none" id="linkInputContainer">
                                        <label class="xx-small fw-bold text-muted mb-2 text-uppercase ls-1">URL Externa</label>
                                        <input type="url" name="external_url" class="form-control rounded-pill border-light-subtle shadow-none xx-small fw-bold" placeholder="https://...">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold xx-small py-2 ls-1 shadow-sm">
                                            <i class="bi bi-cloud-upload me-2"></i> SUBIR MATERIAL
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .text-orange { color: #f97316; }
    .hover-shadow:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
</style>

@push('scripts')
<script>
    document.getElementById('is_live')?.addEventListener('change', function() {
        const container = document.getElementById('liveUrlContainer');
        if (this.checked) {
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
        }
    });

    document.getElementById('resType')?.addEventListener('change', function() {
        const fileContainer = document.getElementById('fileInputContainer');
        const linkContainer = document.getElementById('linkInputContainer');
        if (this.value === 'link') {
            fileContainer.classList.add('d-none');
            linkContainer.classList.remove('d-none');
        } else {
            fileContainer.classList.remove('d-none');
            linkContainer.classList.add('d-none');
        }
    });
</script>
@endpush
@endsection


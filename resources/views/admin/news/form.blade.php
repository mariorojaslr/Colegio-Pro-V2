@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">{{ isset($newsArticle) ? 'Editar' : 'Redactar' }} <span class="text-gradient-gold">Nota</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Escriba contenido profesional para su comunidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 bg-white rounded-4 shadow-sm">
                <form action="{{ isset($newsArticle) ? route('admin.news.update', $newsArticle->id) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($newsArticle)) @method('PUT') @endif
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">Título de la Noticia</label>
                            <input type="text" name="title" class="form-control rounded-pill border-light-subtle" value="{{ old('title', $newsArticle->title ?? '') }}" required placeholder="Ej: Nueva Ley de Honorarios...">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Estado de Publicación</label>
                            <select name="status" class="form-select rounded-pill border-light-subtle">
                                <option value="draft" {{ old('status', $newsArticle->status ?? '') == 'draft' ? 'selected' : '' }}>Borrador (Oculto)</option>
                                <option value="published" {{ old('status', $newsArticle->status ?? '') == 'published' ? 'selected' : '' }}>Publicado (Visible)</option>
                                <option value="archived" {{ old('status', $newsArticle->status ?? '') == 'archived' ? 'selected' : '' }}>Archivado</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Resumen (Bajada)</label>
                            <textarea name="excerpt" class="form-control rounded-4 border-light-subtle" rows="2" placeholder="Un breve resumen que atrape al lector...">{{ old('excerpt', $newsArticle->excerpt ?? '') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Contenido de la Noticia</label>
                            <!-- Para la versión de producción integraremos Quill.js, TinyMCE o CKEditor -->
                            <textarea name="content" class="form-control rounded-4 border-light-subtle" rows="12" required placeholder="Escriba aquí el cuerpo completo de la noticia (soporta etiquetas HTML básicas)...">{{ old('content', $newsArticle->content ?? '') }}</textarea>
                            <div class="form-text">Nota: En la próxima actualización se activará el editor visual avanzado (WYSIWYG) con soporte para subir imágenes integradas con BunnyCDN.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">Subir Imagen Destacada (Opcional)</label>
                            <input type="file" name="featured_image" accept="image/*" class="form-control rounded-pill border-light-subtle">
                            @if(isset($newsArticle) && $newsArticle->featured_image_url)
                                <div class="mt-3 bg-light p-3 rounded-4 border border-light-subtle d-inline-block">
                                    <p class="small text-muted mb-2 fw-bold">Imagen actual:</p>
                                    <img src="{{ asset($newsArticle->featured_image_url) }}" alt="Imagen actual" style="max-height: 120px; border-radius: 10px;" class="shadow-sm">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12 text-end mt-5 border-top pt-4">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-light rounded-pill px-4 fw-bold me-2">Cancelar</a>
                            <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold">{{ isset($newsArticle) ? 'Actualizar Noticia' : 'Publicar Noticia' }} <i class="bi bi-send-check ms-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
</style>
@endsection

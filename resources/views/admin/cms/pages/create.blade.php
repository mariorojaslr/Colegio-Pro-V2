@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h2>Crear Nueva Página</h2>
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <form action="{{ route('admin.cms.pages.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Título de la Página</label>
                    <input type="text" name="title" class="form-control" required placeholder="Ej: Quiénes Somos">
                </div>
                <div class="mb-3">
                    <label>Contenido (HTML permitido)</label>
                    <!-- Aquí se conectará Quill o TinyMCE -->
                    <textarea name="content" class="form-control editor-html" rows="15" placeholder="<h2>Escribe aquí tu contenido</h2><p>Puedes insertar imágenes, videos y estilos.</p>"></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" checked value="1">
                    <label class="form-check-label" for="is_published">Página Publicada (Visible)</label>
                </div>
                <button type="submit" class="btn btn-success">Guardar Página</button>
                <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Opcional: Cargar un editor visual ligero si se desea en el futuro -->
@endpush
@endsection

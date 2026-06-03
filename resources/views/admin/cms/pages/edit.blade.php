@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h2>Editar Página: {{ $page->title }}</h2>
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <form action="{{ route('admin.cms.pages.update', $page->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Título de la Página</label>
                    <input type="text" name="title" class="form-control" required value="{{ $page->title }}">
                </div>
                <div class="mb-3">
                    <label>Contenido (HTML permitido)</label>
                    <textarea name="content" class="form-control editor-html" rows="15">{{ $page->content }}</textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" {{ $page->is_published ? 'checked' : '' }} value="1">
                    <label class="form-check-label" for="is_published">Página Publicada (Visible)</label>
                </div>
                <button type="submit" class="btn btn-success">Actualizar Página</button>
                <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection

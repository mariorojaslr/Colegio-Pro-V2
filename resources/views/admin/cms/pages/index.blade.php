@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Páginas Dinámicas</h2>
        <a href="{{ route('admin.cms.pages.create') }}" class="btn btn-primary">Crear Nueva Página</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>URL Slug</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td><code>/p/{{ $page->slug }}</code></td>
                        <td>
                            @if($page->is_published)
                                <span class="badge bg-success">Publicado</span>
                            @else
                                <span class="badge bg-secondary">Borrador</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.cms.pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay páginas creadas aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

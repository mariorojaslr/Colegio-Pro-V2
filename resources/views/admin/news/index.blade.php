@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Gestor de <span class="text-gradient-gold">Noticias</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Redacción y publicación del periódico digital institucional.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('admin.news.create') }}" class="btn btn-warning rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Redactar Nueva Nota
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 bg-white min-vh-50">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="bg-light">
                            <tr class="small fw-bold text-muted text-uppercase">
                                <th class="py-3 px-4">Noticia</th>
                                <th class="py-3">Estado</th>
                                <th class="py-3">Fecha</th>
                                <th class="py-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $article)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6 text-dark">{{ $article->title }}</div>
                                    <div class="small text-muted">{{ Str::limit($article->excerpt, 60) }}</div>
                                </td>
                                <td class="py-3">
                                    @if($article->status == 'published')
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 fw-bold small">Publicado</span>
                                    @elseif($article->status == 'draft')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 fw-bold small">Borrador</span>
                                    @else
                                        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 fw-bold small">Archivado</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="text-muted fw-medium">{{ $article->published_at ? $article->published_at->format('d/m/Y') : '-' }}</span>
                                </td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('news.show', $article->slug) }}" target="_blank" class="btn btn-light btn-sm rounded-circle shadow-sm me-1" title="Ver en portal"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.news.edit', $article->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm rounded-circle shadow-sm ms-1 text-danger" onclick="return confirm('¿Eliminar esta noticia?')" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center text-muted">
                                    <i class="bi bi-newspaper fs-1 opacity-25 d-block mb-3"></i>
                                    No hay noticias redactadas.<br>
                                    Haga clic en "Redactar Nueva Nota" para comenzar.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
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
    .card-prestige { transition: all 0.3s ease; }
    .table-hover tbody tr:hover { background: rgba(0,0,0,0.01); }
</style>
@endsection

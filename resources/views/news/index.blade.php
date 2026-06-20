@extends('layouts.main')

@section('content')
<div class="container-fluid py-5 min-vh-100 bg-light-subtle">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-4 p-md-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-7 text-white mb-4 mb-md-0">
                        <h1 class="display-4 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Novedades y <span class="text-gradient-gold">Noticias</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Manténgase informado con los últimos comunicados institucionales.</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a href="{{ url('/') }}" class="btn btn-outline-light rounded-pill px-4 py-2 me-2 mb-2">
                            <i class="bi bi-arrow-left me-1"></i> Volver al Inicio
                        </a>
                        <button onclick="window.close();" class="btn btn-primary rounded-pill px-4 py-2 mb-2" style="background: #f59e0b; border: none; color: #000; font-weight: bold;">
                            <i class="bi bi-x-lg me-1"></i> Cerrar Pestaña
                        </button>
                    </div>
                </div>
                
                <!-- Buscador -->
                <div class="row mt-4 position-relative" style="z-index: 2">
                    <div class="col-md-8 col-lg-6">
                        <form action="{{ route('news.index') }}" method="GET" class="d-flex bg-white p-1 rounded-pill shadow-sm">
                            <input type="text" name="search" class="form-control border-0 rounded-pill px-4 shadow-none" placeholder="Buscar por palabra clave..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-dark rounded-pill px-4">Buscar</button>
                            @if(request('search'))
                                <a href="{{ route('news.index') }}" class="btn btn-light rounded-pill px-3 ms-1" title="Limpiar búsqueda"><i class="bi bi-eraser"></i></a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($articles as $article)
        <div class="col-md-4">
            <a href="{{ route('news.show', $article->slug) }}" class="text-decoration-none">
                <div class="card card-prestige h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="transition: all 0.3s ease;">
                    @if($article->featured_image_url)
                        <img src="{{ $article->featured_image_url }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-newspaper display-4 text-white opacity-50"></i>
                        </div>
                    @endif
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-2 text-muted small fw-bold">
                            <i class="bi bi-calendar3 me-1"></i> {{ $article->published_at->format('d M, Y') }}
                        </div>
                        <h5 class="card-title fw-bold text-dark">{{ $article->title }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}</p>
                        <div class="mt-3 text-primary fw-bold small text-uppercase ls-1">Leer más <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-journal-x display-1 text-muted opacity-25 d-block mb-3"></i>
            <h4 class="text-muted">No hay noticias publicadas en este momento.</h4>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $articles->links() }}
    </div>
</div>

<style>
    .card-prestige:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

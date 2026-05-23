@extends('layouts.main')

@section('content')
<div class="container py-5 min-vh-100">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-decoration-none text-muted fw-bold">Noticias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 40) }}</li>
                </ol>
            </nav>

            <h1 class="display-5 fw-bold text-dark mb-3" style="font-family: 'Outfit', sans-serif;">{{ $article->title }}</h1>
            
            <div class="d-flex align-items-center mb-4 text-muted small fw-bold">
                <div class="me-4"><i class="bi bi-calendar3 me-2"></i> {{ $article->published_at->format('d de F, Y') }}</div>
                <div><i class="bi bi-person-circle me-2"></i> {{ $article->author->name ?? 'Redacción' }}</div>
            </div>

            @if($article->featured_image_url)
                <div class="mb-5 rounded-4 overflow-hidden shadow-sm">
                    <img src="{{ $article->featured_image_url }}" class="img-fluid w-100" alt="{{ $article->title }}" style="max-height: 500px; object-fit: cover;">
                </div>
            @endif

            @if($article->excerpt)
                <div class="lead fw-medium mb-4 p-4 bg-light-subtle rounded-4 border-start border-4 border-warning">
                    {{ $article->excerpt }}
                </div>
            @endif

            <div class="article-content fs-5" style="line-height: 1.8; color: #334155;">
                {!! $article->content !!}
            </div>
            
            <hr class="my-5 opacity-25">
            <div class="text-center">
                <a href="{{ route('news.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold"><i class="bi bi-arrow-left me-2"></i> Volver a Noticias</a>
            </div>
        </div>
    </div>
</div>

<style>
    .article-content h2, .article-content h3 {
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .article-content p {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

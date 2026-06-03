@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <h2>Gestor de Carruseles (Sliders)</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Crear Nuevo Carrusel</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.sliders.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Nombre del Carrusel (Uso interno)</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ej: Carrusel Home Principal">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Crear Carrusel</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            @foreach($sliders as $slider)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $slider->name }}</h5>
                </div>
                <div class="card-body">
                    <h6>Imágenes del Carrusel</h6>
                    <div class="row mb-4">
                        @forelse($slider->items as $item)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title">{{ $item->title ?? 'Sin Título' }}</h6>
                                        <p class="card-text small text-truncate">{{ $item->description }}</p>
                                        <p class="mb-0 small text-muted">Orden: {{ $item->order }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No hay imágenes en este carrusel.</p>
                            </div>
                        @endforelse
                    </div>

                    <h6>Añadir nueva imagen</h6>
                    <form action="{{ route('admin.cms.sliders.items.store', $slider->id) }}" method="POST" class="border p-3 rounded bg-light">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>URL de la Imagen (Bunny.net u otra)</label>
                                <input type="url" name="image_url" class="form-control form-control-sm" required placeholder="https://midominio.b-cdn.net/imagen.jpg">
                                <small class="text-muted">El sistema de subida directa a Bunny.net se implementará en la fase de optimización.</small>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Título (Opcional)</label>
                                <input type="text" name="title" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Enlace (URL al hacer clic)</label>
                                <input type="text" name="link" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-8 mb-2">
                                <label>Descripción breve</label>
                                <input type="text" name="description" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Orden</label>
                                <input type="number" name="order" class="form-control form-control-sm" value="0">
                            </div>
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-sm btn-success">Añadir Imagen</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

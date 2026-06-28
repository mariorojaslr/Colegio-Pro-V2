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
                                <div class="card h-100 position-relative">
                                    <form action="{{ route('admin.cms.sliders.items.destroy', $item->id) }}" method="POST" class="position-absolute" style="top: -10px; right: -10px; z-index: 10;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-close shadow bg-white rounded-circle p-2 border border-2 border-danger" onclick="return confirm('¿Eliminar imagen?')" title="Eliminar" style="opacity: 1; width: 1.5em; height: 1.5em;"></button>
                                    </form>
                                    <img src="{{ Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/'.$item->image_url) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title">{{ $item->title ?? 'Sin Título' }}</h6>
                                        <p class="card-text small text-truncate">{{ $item->description }}</p>
                                        <p class="mb-0 small text-muted"><i class="bi bi-sort-numeric-down"></i> Orden: {{ $item->order }}</p>
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            <div>Desde: {{ $item->starts_at ? $item->starts_at->format('d/m/Y H:i') : 'No def.' }}</div>
                                            <div>Hasta: {{ $item->ends_at ? $item->ends_at->format('d/m/Y H:i') : 'No def.' }}</div>
                                        </div>
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
                    <form action="{{ route('admin.cms.sliders.items.store', $slider->id) }}" method="POST" enctype="multipart/form-data" class="border p-3 rounded bg-light">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold">Subir Foto para el Carrusel</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                                <div class="alert alert-info mt-3 py-2 px-3 small border-0 shadow-sm" style="background-color: rgba(13, 110, 253, 0.05); border-left: 4px solid #0d6efd !important;">
                                    <strong><i class="bi bi-aspect-ratio me-1"></i> Medidas Recomendadas:</strong> 
                                    Para que tu slider se vea profesional, nítido y perfecto en todas las pantallas, la imagen debe tener exactamente <strong>1920 píxeles de ancho por 800 píxeles de alto (1920x800)</strong>. Mantén el peso del archivo por debajo de 2MB para asegurar una carga rápida.
                                </div>
                                <small class="text-muted"><i class="bi bi-cloud-arrow-up me-1"></i> La imagen se subirá automáticamente a Bunny.net</small>
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <label>Fecha de Inicio</label>
                                <input type="datetime-local" name="starts_at" class="form-control form-control-sm" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha de Fin (Caducidad)</label>
                                <input type="datetime-local" name="ends_at" class="form-control form-control-sm" value="{{ now()->addDays(30)->format('Y-m-d\TH:i') }}" required>
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
                                <label>Orden de aparición</label>
                                <input type="number" name="order" class="form-control form-control-sm" value="0">
                                <small class="text-muted" style="font-size: 0.7rem;">0=Primera, 1=Segunda, etc.</small>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-cloud-upload me-2"></i> Subir y Programar Imagen</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (this.files[0].size > maxSize) {
                    alert('La imagen es demasiado pesada. El tamaño máximo permitido es 2MB. Por favor, comprime la imagen e intenta nuevamente.');
                    this.value = ''; // Limpiar el input
                }
            }
        });
    });
});
</script>
@endpush

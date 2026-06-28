@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.agreements.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill fw-bold px-3">
                <i class="bi bi-arrow-left me-1"></i> Volver a Convenios
            </a>
        </div>
    </div>
    
    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h4 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">Crear Nuevo Convenio</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.agreements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Comercio</label>
                            <input type="text" name="name" class="form-control" required placeholder="Ej: Farmacia ABC">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo (Opcional)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Subir imagen en formato PNG, JPG o WEBP.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción del Convenio</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Breve detalle sobre lo que ofrece el convenio..."></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Porcentaje de Descuento (Opcional)</label>
                            <input type="text" name="discount_percentage" class="form-control" placeholder="Ej: 20% OFF">
                        </div>
                        
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                            <label class="form-check-label" for="isActive">Convenio Activo</label>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                Guardar Convenio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

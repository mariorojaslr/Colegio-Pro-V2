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
                    <h4 class="fw-bold mb-0" style="font-family: 'Outfit', sans-serif;">Editar Convenio</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.agreements.update', $agreement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre del Comercio</label>
                            <input type="text" name="name" class="form-control" required value="{{ $agreement->name }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Logo (Opcional)</label>
                            @if($agreement->logo_url)
                                <div class="mb-2">
                                    <img src="{{ asset($agreement->logo_url) }}" alt="Logo actual" class="rounded shadow-sm" style="height: 60px; object-fit: contain;">
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Si subes un nuevo logo, reemplazará al actual.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción del Convenio</label>
                            <textarea name="description" class="form-control" rows="3">{{ $agreement->description }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Porcentaje de Descuento (Opcional)</label>
                            <input type="text" name="discount_percentage" class="form-control" value="{{ $agreement->discount_percentage }}">
                        </div>
                        
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $agreement->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Convenio Activo</label>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                Actualizar Convenio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

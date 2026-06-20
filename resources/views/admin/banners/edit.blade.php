@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left"></i> Volver a Banners</a>
    <h3 class="fw-bold m-0">Editar Flyer Promocional</h3>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Título Interno</label>
                    <input type="text" name="title" class="form-control rounded-3" required value="{{ $banner->title }}">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Enlace (Opcional)</label>
                    <input type="url" name="link_url" class="form-control rounded-3" value="{{ $banner->link_url }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Actualizar Imagen del Flyer</label>
                    <div class="d-flex align-items-center mb-2">
                        @php
                            $imgSrc = Str::startsWith($banner->image_path, ['http://', 'https://']) 
                                ? $banner->image_path 
                                : asset('storage/'.$banner->image_path);
                        @endphp
                        <img src="{{ $imgSrc }}" alt="Banner" class="rounded-3 shadow-sm me-3" style="height: 60px;">
                        <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                    </div>
                    <div class="form-text">Dejar vacío si no deseas cambiar la imagen actual.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-success">Fecha de Inicio</label>
                    <input type="datetime-local" name="starts_at" class="form-control rounded-3" required value="{{ $banner->starts_at->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">Fecha de Fin</label>
                    <input type="datetime-local" name="ends_at" class="form-control rounded-3" required value="{{ $banner->ends_at->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12 mt-4">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="isActive" name="is_active" {{ $banner->is_active ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="isActive">Activar Flyer inmediatamente al iniciar la fecha</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Actualizar Flyer</button>
            </div>
        </form>
    </div>
</div>
@endsection

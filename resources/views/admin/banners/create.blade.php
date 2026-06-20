@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="bi bi-arrow-left"></i> Volver a Banners</a>
    <h3 class="fw-bold m-0">Crear Nuevo Flyer Promocional</h3>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Título Interno</label>
                    <input type="text" name="title" class="form-control rounded-3" required placeholder="Ej: Promo Black Friday">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Enlace (Opcional)</label>
                    <input type="url" name="link_url" class="form-control rounded-3" placeholder="https://...">
                    <div class="form-text">A dónde irá el usuario al hacer clic.</div>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Imagen del Flyer</label>
                    <input type="file" name="image" class="form-control rounded-3" required accept="image/*">
                    <div class="form-text">Formato recomendado: Cuadrado o vertical para móviles. Max 2MB.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-success">Fecha de Inicio</label>
                    <input type="datetime-local" name="starts_at" class="form-control rounded-3" required value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-danger">Fecha de Fin</label>
                    <input type="datetime-local" name="ends_at" class="form-control rounded-3" required value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12 mt-4">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" role="switch" id="isActive" name="is_active" checked>
                        <label class="form-check-label ms-2" for="isActive">Activar Flyer inmediatamente al iniciar la fecha</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Guardar Flyer</button>
            </div>
        </form>
    </div>
</div>
@endsection

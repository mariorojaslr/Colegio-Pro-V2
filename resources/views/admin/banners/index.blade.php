@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-megaphone me-2"></i> Flyers Temporales</h3>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Flyer
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 rounded-start-4">Flyer</th>
                        <th class="border-0">Fechas</th>
                        <th class="border-0 text-center">Estado</th>
                        <th class="pe-4 border-0 rounded-end-4 text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                    @php
                        $imgSrc = Str::startsWith($banner->image_path, ['http://', 'https://']) 
                            ? $banner->image_path 
                            : asset('storage/'.$banner->image_path);
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="{{ $imgSrc }}" alt="Banner" class="rounded-3 shadow-sm me-3" style="width: 80px; height: 50px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $banner->title }}</h6>
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" target="_blank" class="small text-muted"><i class="bi bi-link-45deg"></i> Ver Enlace</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-success fw-bold"><i class="bi bi-play-circle me-1"></i> {{ $banner->starts_at->format('d/m/Y H:i') }}</div>
                                <div class="text-danger fw-bold"><i class="bi bi-stop-circle me-1"></i> {{ $banner->ends_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $banner->is_active ? 'success' : 'secondary' }} rounded-pill" title="Clic para cambiar estado">
                                    {{ $banner->is_active ? 'Activo' : 'Pausado' }}
                                </button>
                            </form>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-light rounded-circle shadow-sm me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este banner?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger rounded-circle shadow-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-image fs-1 d-block mb-3 text-light"></i>
                            No hay banners promocionales configurados.<br>
                            Crea uno para notificar a los usuarios.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

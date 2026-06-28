@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Gestor de <span class="text-gradient-gold">Convenios</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Administración de convenios comerciales vigentes.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('admin.agreements.create') }}" class="btn btn-warning rounded-pill px-4 py-3 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i> Nuevo Convenio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-muted small text-uppercase ls-1">
                                <tr>
                                    <th>Logo</th>
                                    <th>Comercio</th>
                                    <th>Descuento</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agreements as $agreement)
                                <tr>
                                    <td>
                                        @if($agreement->logo_url)
                                            <img src="{{ asset($agreement->logo_url) }}" alt="{{ $agreement->name }}" class="rounded shadow-sm" style="height: 40px; object-fit: contain;">
                                        @else
                                            <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-briefcase"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-bold">{{ $agreement->name }}</h6>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">{{ $agreement->description }}</small>
                                    </td>
                                    <td><span class="badge bg-primary rounded-pill">{{ $agreement->discount_percentage ?? 'N/A' }}</span></td>
                                    <td>
                                        @if($agreement->is_active)
                                            <span class="badge bg-success rounded-pill">Activo</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.agreements.edit', $agreement->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('admin.agreements.destroy', $agreement->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este convenio?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        No hay convenios registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

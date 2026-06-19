@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 bg-light-subtle min-vh-100">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Outfit', sans-serif">Excepciones de Trámites</h1>
            <p class="text-muted">Gestione los certificados solicitados por colegiados que no cumplen con los requisitos (ej. por deuda o sanciones).</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 small fw-bold text-uppercase text-muted ls-1">Fecha</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase text-muted ls-1">Colegiado</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase text-muted ls-1">Trámite / Certificado</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase text-muted ls-1 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($certificates as $cert)
                    <tr>
                        <td class="px-4 py-3">{{ $cert->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="fw-bold text-dark">{{ $cert->collegiate->first_name }} {{ $cert->collegiate->last_name }}</div>
                            <div class="small text-muted">DNI: {{ $cert->collegiate->dni }}</div>
                        </td>
                        <td class="px-4 py-3 fw-medium text-primary">
                            {{ $cert->type->name }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.certificates.approve', $cert) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" onclick="return confirm('¿Aprobar excepción y emitir certificado?')">
                                    <i class="bi bi-check-circle me-1"></i> Aprobar
                                </button>
                            </form>
                            <form action="{{ route('admin.certificates.reject', $cert) }}" method="POST" class="d-inline ms-1">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Rechazar esta solicitud?')">
                                    <i class="bi bi-x-circle me-1"></i> Rechazar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            No hay solicitudes pendientes de aprobación.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

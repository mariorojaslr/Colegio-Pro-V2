@extends('layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-center mb-5">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Tienda de Trámites y Certificados</h1>
            <p class="text-muted">Solicite y descargue sus certificados oficiales y documentación valorizada.</p>
        </div>
    </div>

    @if($hasSanctions)
    <div class="alert alert-danger rounded-4 shadow-sm border-0 d-flex align-items-center mb-5 p-4">
        <i class="bi bi-shield-exclamation fs-1 me-4 text-danger"></i>
        <div>
            <h5 class="fw-bold mb-1">Inhabilitación Ética Activa</h5>
            <p class="mb-0 small">Nuestros registros indican que usted posee una sanción disciplinaria vigente emitida por la Comisión de Ética. Esto le impide solicitar ciertos trámites como el Certificado de Ética Profesional.</p>
        </div>
    </div>
    @endif

    <div class="row g-4 mb-5">
        @forelse($types as $type)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
                <div class="mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 text-primary">
                        <i class="bi bi-file-earmark-medical fs-1"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">{{ $type->name }}</h5>
                <p class="text-muted small mb-3" style="min-height: 40px;">{{ $type->description ?? 'Certificado oficial emitido por la institución.' }}</p>
                
                <div class="mt-auto">
                    <h3 class="fw-bold text-success mb-3">{{ $type->price > 0 ? '$' . number_format($type->price, 2) : 'Gratuito' }}</h3>
                    
                    @php
                        $canBuy = true;
                        $reason = '';
                        if ($type->requires_no_sanctions && $hasSanctions) {
                            $canBuy = false;
                            $reason = 'Sanción Ética Activa';
                        }
                        if ($type->requires_clearance && $hasDebt) {
                            $canBuy = false;
                            $reason = 'Deuda Registrada';
                        }
                    @endphp

                    @if($canBuy)
                        <form action="{{ route('collegiate.certificates.purchase', $type) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm fw-bold">Solicitar y Generar</button>
                        </form>
                    @else
                        <form action="{{ route('collegiate.certificates.purchase', $type) }}" method="POST">
                            @csrf
                            <input type="hidden" name="request_exception" value="1">
                            <button type="submit" class="btn btn-outline-warning rounded-pill w-100 shadow-sm fw-bold" title="Pedir autorización especial">
                                Solicitar Excepción
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">No hay trámites disponibles actualmente.</h5>
        </div>
        @endforelse
    </div>

    <h4 class="fw-bold mb-4">Mis Trámites Generados</h4>
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 small fw-bold text-uppercase">Certificado</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase">Fecha Emisión</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase">Vencimiento</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase">Código Valid.</th>
                        <th class="px-4 py-3 small fw-bold text-uppercase text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myCertificates as $cert)
                    <tr>
                        <td class="px-4 py-3 fw-medium">{{ $cert->type->name ?? 'Trámite Oficial' }}
                            @if($cert->status === 'pending')
                                <span class="badge bg-warning text-dark ms-2">Pendiente de Aprobación</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $cert->issued_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($cert->expires_at)
                                <span class="{{ $cert->expires_at->isPast() ? 'text-danger fw-bold' : '' }}">
                                    {{ $cert->expires_at->format('d/m/Y') }}
                                </span>
                            @else
                                Permanente
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted font-monospace small">{{ $cert->code }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($cert->status === 'active' || $cert->status === 'valid')
                                <a href="{{ route('collegiate.certificates.download', $cert) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3"><i class="bi bi-download me-1"></i> Descargar</a>
                            @else
                                <span class="text-muted small">No disponible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Aún no has generado ningún trámite.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

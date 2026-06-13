@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    {{-- Header de Sección --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Mi <span class="text-gradient-gold">Legajo Digital</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Mantenga su documentación al día para asegurar su habilitación profesional.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="bg-white bg-opacity-10 p-4 rounded-4 backdrop-blur border border-white border-opacity-10">
                            @php
                                $total = $mandatoryReqsCount;
                                $uploaded = $validMandatoryDocsCount;
                                $percent = $total > 0 ? ($uploaded / $total) * 100 : 100;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50 small fw-bold uppercase">Progreso Documental ({{ $uploaded }}/{{ $total }})</span>
                                <span class="text-warning fw-bold">{{ round($percent) }}%</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1)">
                                <div class="progress-bar bg-warning shadow-accent" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Listado de Requisitos --}}
    <div class="row g-4">
        @foreach($requirements as $requirement)
            <div class="col-md-6">
                @php
                    $doc = $myDocuments->get($requirement->id);
                    $status = $doc ? $doc->status : 'missing';
                    $isExpired = false;
                    
                    if ($status === 'approved' && $doc->expires_at && $doc->expires_at < now()) {
                        $status = 'expired';
                        $isExpired = true;
                    }

                    $statusConfig = [
                        'missing' => ['label' => 'Sin Cargar', 'class' => 'bg-secondary-subtle text-muted', 'icon' => 'bi-file-earmark-plus'],
                        'pending' => ['label' => 'En Revisión', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'bi-hourglass-split'],
                        'approved' => ['label' => 'Aprobado y Vigente', 'class' => 'bg-success-subtle text-success', 'icon' => 'bi-patch-check-fill'],
                        'rejected' => ['label' => 'Rechazado', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-exclamation-octagon-fill'],
                        'expired' => ['label' => 'Vencido - Renovar', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-calendar-x-fill'],
                    ];
                @endphp
                <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100" style="border-radius: 30px">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-3 rounded-4 me-3">
                                <i class="bi {{ $statusConfig[$status]['icon'] }} fs-3 {{ $status == 'missing' ? 'text-muted' : '' }}"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $requirement->name }}</h5>
                                <span class="badge {{ $statusConfig[$status]['class'] }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ $statusConfig[$status]['label'] }}
                                </span>
                                @if($requirement->is_mandatory)
                                    <span class="ms-2 text-danger small fw-bold">* Obligatorio</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($status == 'rejected' && $doc->admin_notes)
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small fw-medium">
                            <i class="bi bi-info-circle-fill me-2"></i> <strong>Motivo del rechazo:</strong> {{ $doc->admin_notes }}
                        </div>
                    @endif

                    <div class="bg-light p-4 rounded-4 mb-4">
                        <p class="small text-muted mb-0">
                            @if($requirement->expiration_months)
                                Vencimiento: Cada {{ $requirement->expiration_months }} meses.
                            @else
                                Documento de validez permanente.
                            @endif
                        </p>
                        @if($doc && $doc->expires_at)
                            <p class="small fw-bold text-dark mt-2 mb-0">
                                Fecha de Vencimiento: <span class="{{ $isExpired ? 'text-danger' : 'text-success' }}">{{ \Carbon\Carbon::parse($doc->expires_at)->format('d/m/Y') }}</span>
                            </p>
                        @endif
                    </div>

                    @if($status != 'approved')
                        <form action="{{ route('compliance.upload', $requirement) }}" id="form-{{ $requirement->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="document" id="file-{{ $requirement->id }}" class="d-none" onchange="document.getElementById('form-{{ $requirement->id }}').submit()">
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold small" onclick="document.getElementById('file-{{ $requirement->id }}').click()">
                                        <i class="bi bi-folder2-open me-2"></i> ARCHIVO
                                    </button>
                                </div>
                                <div class="col-6">
                                    <input type="file" name="document" id="camera-{{ $requirement->id }}" class="d-none" accept="image/*" capture="environment" onchange="document.getElementById('form-{{ $requirement->id }}').submit()">
                                    <button type="button" class="btn btn-dark w-100 rounded-pill py-2 fw-bold small shadow-sm" onclick="document.getElementById('camera-{{ $requirement->id }}').click()">
                                        <i class="bi bi-camera me-2"></i> ESCANEAR
                                    </button>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                <span class="xx-small text-muted fw-bold ls-1 uppercase">SOPORTA: PDF, EXCEL, WORD, IMÁGENES (MÁX. 10MB)</span>
                            </div>
                        </form>
                    @else
                        <div class="bg-success bg-opacity-5 border border-success border-opacity-10 rounded-4 p-3 text-center">
                             <span class="text-success fw-bold small"><i class="bi bi-check2-all me-2"></i> DOCUMENTO VERIFICADO</span>
                             <div class="xx-small text-success opacity-75 mt-1 uppercase">HABILITADO EN LEGAJO</div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .backdrop-blur { backdrop-filter: blur(10px); }
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .shadow-accent { box-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }
    .card-prestige { transition: transform 0.3s ease; }
    .card-prestige:hover { transform: translateY(-5px); }
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection

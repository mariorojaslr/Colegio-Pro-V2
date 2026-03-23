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
                                $total = $requirements->count();
                                $uploaded = $myDocuments->count();
                                $percent = $total > 0 ? ($uploaded / $total) * 100 : 0;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50 small fw-bold uppercase">Progreso Documental</span>
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
                    $statusConfig = [
                        'missing' => ['label' => 'Sin Cargar', 'class' => 'bg-secondary-subtle text-muted', 'icon' => 'bi-file-earmark-plus'],
                        'pending' => ['label' => 'En Revisión', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'bi-hourglass-split'],
                        'approved' => ['label' => 'Aprobado', 'class' => 'bg-success-subtle text-success', 'icon' => 'bi-patch-check-fill'],
                        'rejected' => ['label' => 'Rechazado', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-exclamation-octagon-fill'],
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
                            {{ $requirement->expiry_frequency == 'none' ? 'Documento de validez permanente.' : 'Requiere renovación ' . $requirement->expiry_frequency . '.' }}
                        </p>
                    </div>

                    @if($status != 'approved')
                        <form action="{{ route('compliance.upload', $requirement) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="document" class="form-control rounded-start-pill border-light-subtle" required>
                                <button type="submit" class="btn btn-dark rounded-end-pill px-4 fw-bold">
                                    {{ $status == 'missing' ? 'Subir' : 'Corregir' }}
                                </button>
                            </div>
                            <div class="form-text small opacity-50 ps-3">PDF, JPG o PNG (Máx. 5MB)</div>
                        </form>
                    @else
                        <div class="text-center py-2 h-100 d-flex align-items-center justify-content-center border border-success border-opacity-10 rounded-pill bg-success bg-opacity-5">
                            <span class="text-success fw-bold small"><i class="bi bi-check2-all me-2"></i> Documento Correcto</span>
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
</style>
@endsection

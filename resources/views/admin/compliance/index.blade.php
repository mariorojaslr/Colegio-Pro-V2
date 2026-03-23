@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    {{-- Header de Sección --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #1e293b, #334155); border-radius: 40px">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2">
                    <div class="text-white">
                        <h1 class="display-6 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Revisión de <span class="text-gradient-gold">Legajos</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Audite los documentos pendientes de los colegiados para habilitar su ejercicio profesional.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning text-dark rounded-pill px-4 py-3 fw-bold fs-6 shadow-lg">
                            <i class="bi bi-mailbox me-2"></i> {{ $pendingDocuments->count() }} Pendientes
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bandeja de Revisión --}}
    <div class="row g-4">
        @forelse($pendingDocuments as $doc)
            <div class="col-lg-12">
                <div class="card-prestige p-0 border-0 bg-white shadow-sm overflow-hidden mb-4" style="border-radius: 30px">
                    <div class="row g-0">
                        {{-- Info del Colegiado --}}
                        <div class="col-md-4 p-5 bg-light border-end">
                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Colegiado / Matrícula</h6>
                            <h4 class="fw-bold mb-1">{{ $doc->collegiate->first_name }} {{ $doc->collegiate->last_name }}</h4>
                            <p class="text-primary fw-bold mb-4">MATRÍCULA: {{ $doc->collegiate->registration_number }}</p>
                            
                            <hr class="my-4">
                            
                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-2">Requisito a Validar</h6>
                            <div class="bg-white p-3 rounded-4 border border-light-subtle shadow-sm mb-4">
                                <h6 class="fw-bold m-0">{{ $doc->requirement->name }}</h6>
                                <span class="badge bg-info bg-opacity-10 text-info small mt-1">{{ ucfirst($doc->requirement->type) }}</span>
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('admin.compliance.approve', $doc) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm">
                                        <i class="bi bi-check-circle me-2"></i> Aprobar Trámite
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Visor de Documento --}}
                        <div class="col-md-5 p-5">
                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Captura del Colegiado</h6>
                            <div class="bg-dark rounded-5 overflow-hidden shadow-2xl position-relative" style="height: 300px">
                                @if(Str::endsWith($doc->file_url, '.pdf'))
                                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-white p-5 text-center">
                                        <i class="bi bi-file-earmark-pdf fs-1 text-danger mb-3"></i>
                                        <p class="fw-bold mb-3">Documento PDF</p>
                                        <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-light rounded-pill px-4 fw-bold small">Ver Pantalla Completa</a>
                                    </div>
                                @else
                                    <img src="{{ $doc->file_url }}" class="w-100 h-100 object-fit-cover opacity-75" style="transition: opacity 0.3s ease" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.75'">
                                    <div class="position-absolute bottom-0 end-0 p-3">
                                        <a href="{{ $doc->file_url }}" target="_blank" class="btn btn-white btn-sm rounded-circle shadow">
                                            <i class="bi bi-fullscreen"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Acciones de Rechazo --}}
                        <div class="col-md-3 p-5 bg-light-subtle">
                            <h6 class="text-muted small fw-bold uppercase ls-1 mb-4">Observaciones</h6>
                            <form action="{{ route('admin.compliance.reject', $doc) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-danger">Motivo del Rechazo</label>
                                    <textarea name="admin_notes" class="form-control rounded-4 border-light-subtle" rows="5" placeholder="Escriba aquí por qué rechaza el documento (ej: Foto borrosa, vencido...)" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-3 fw-bold">
                                    <i class="bi bi-x-circle me-2"></i> Rechazar y Notificar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-12">
                <div class="text-center py-5">
                    <div class="bg-white d-inline-block p-5 rounded-circle shadow-sm mb-4">
                        <i class="bi bi-check2-all display-1 text-success opacity-25"></i>
                    </div>
                    <h3 class="fw-bold text-dark">¡Todo al día!</h3>
                    <p class="text-muted lead">No hay legajos pendientes de revisión en este momento.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .card-prestige { transition: all 0.3s ease; }
    .uppercase { text-transform: uppercase; }
</style>
@endsection

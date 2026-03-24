@extends('layouts.main')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5" 
     style="background: radial-gradient(circle at top right, #f8fafc, #f1f5f9);">
    
    <div class="col-lg-7 col-xl-6">
        <div class="card border-0 shadow-2xl overflow-hidden animate__animated animate__fadeInUp" style="border-radius: 40px; background: #fff;">
            
            @php
                $isCert = isset($certificate);
                $entity = $isCert ? $certificate : $document;
                $collegiate = $entity->collegiate;
                $isSingleUse = $isCert && $certificate->type->is_single_use;
                $isUsed = $isCert && $certificate->used_at;
            @endphp

            {{-- Header de Validación --}}
            <div class="p-5 text-center {{ $isUsed ? 'bg-warning bg-opacity-10 border-warning' : 'bg-success bg-opacity-10 border-success' }} border-bottom border-opacity-10 position-relative">
                <div class="position-absolute top-0 start-0 p-4 opacity-25">
                    <i class="bi {{ $isUsed ? 'bi-exclamation-octagon' : 'bi-shield-check' }} display-1 {{ $isUsed ? 'text-warning' : 'text-success' }}"></i>
                </div>
                
                <div class="{{ $isUsed ? 'bg-warning' : 'bg-success' }} text-white d-inline-flex align-items-center justify-content-center rounded-circle mb-4 shadow-accent" 
                     style="width: 100px; height: 100px; font-size: 3rem;">
                    <i class="bi {{ $isUsed ? 'bi-lock-fill' : 'bi-check-lg' }}"></i>
                </div>
                
                @if($isUsed)
                    <h1 class="display-6 fw-bold text-warning mb-2" style="font-family: 'Outfit', sans-serif;">Certificado <span class="text-dark">YA UTILIZADO</span></h1>
                    <p class="lead fw-medium text-warning opacity-75 mb-0">Este documento fue invalidado el {{ $certificate->used_at->format('d/m/Y') }}</p>
                @else
                    <h1 class="display-6 fw-bold text-success mb-2" style="font-family: 'Outfit', sans-serif;">{{ $isCert ? 'Certificado' : 'Documento' }} <span class="text-dark">Válido</span></h1>
                    <p class="lead fw-medium text-success opacity-75 mb-0">Autenticidad Confirmada por {{ $collegiate->school->name }}</p>
                @endif
            </div>

            {{-- Detalles del Titular --}}
            <div class="p-5 pb-4">
                <div class="row g-4 align-items-center mb-5">
                    <div class="col-md-7">
                        <h6 class="text-muted small fw-bold text-uppercase ls-2 mb-4">Titular de la Credencial</h6>
                        <h3 class="fw-bold mb-1 text-dark">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</h3>
                        <p class="text-primary fw-bold mb-0 fs-5">MATRÍCULA: {{ $collegiate->registration_number }}</p>
                        <p class="text-muted small mb-4">DNI: {{ $collegiate->dni }}</p>
                        
                        <div class="bg-light p-4 rounded-4 border border-light-subtle">
                            <h6 class="text-muted small fw-bold mb-2">Trámite / Documento</h6>
                            <h5 class="fw-bold m-0">{{ $isCert ? $certificate->type->name : $document->requirement->name }}</h5>
                            <hr class="my-3 opacity-10">
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Emitido el:</span>
                                <span class="fw-bold">{{ $entity->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center border-start ps-lg-5">
                        @if($isUsed)
                            <div class="text-danger mb-3"><i class="bi bi-x-circle fs-1"></i></div>
                            <h6 class="fw-bold text-danger mb-1">TRÁMITE FINALIZADO</h6>
                            <p class="x-small text-muted mb-0">Expediente No: <br><span class="text-dark fw-bold">{{ $certificate->used_for_expedient }}</span></p>
                        @else
                            <div class="digital-seal p-4 border border-5 border-success border-opacity-10 rounded-circle d-inline-block position-relative mb-3">
                                <i class="bi bi-qr-code-scan display-4 text-dark opacity-10"></i>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <span class="badge bg-success rounded-pill px-3 py-2 small shadow">ORIGINAL</span>
                                </div>
                            </div>
                            <h6 class="fw-bold text-success mb-1">HABILITADO</h6>
                            <p class="x-small text-muted mb-0">Este documento es fiel reflejo de la base corporativa.</p>
                        @endif
                    </div>
                </div>

                {{-- Sección de 'Quemado' (Solo si es Single Use y No está usado) --}}
                @if($isSingleUse && !$isUsed)
                    <div class="p-4 bg-primary bg-opacity-5 rounded-4 border border-primary border-opacity-25 mb-5 px-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="bg-primary text-white rounded-circle p-2 d-flex"><i class="bi bi-shield-lock"></i></div>
                            <h5 class="fw-bold m-0 text-primary">Validación de Uso en Trámite</h5>
                        </div>
                        <p class="small text-muted mb-4">Este es un <strong>Certificado de Único Uso</strong>. Si usted es una entidad receptora (Banco, Juzgado, Escribanía), debe "quemar" el certificado vinculándolo al expediente para evitar duplicidad.</p>
                        
                        <form action="{{ route('validation.burn', $certificate->uuid) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="expedient_number" class="form-control rounded-pill border-0 shadow-sm px-4 py-2" 
                                           placeholder="Ej: Exp. 1245/2026 - Juzgado Civil 4" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-sm">
                                        Validar y Usar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Footer Institucional --}}
            <div class="px-5 py-4 {{ $isUsed ? 'bg-warning' : 'bg-light' }} {{ $isUsed ? 'bg-opacity-10' : '' }} border-top text-center">
                <p class="mb-0 small text-muted">© {{ date('Y') }} {{ $collegiate->school->name }} | Verificado vía Colegio-Pro</p>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold small transition-all">Regresar al Portal</a>
        </div>
    </div>
</div>

<style>
    .shadow-2xl { box-shadow: 0 40px 60px -12px rgba(0, 0, 0, 0.15); }
    .shadow-accent { box-shadow: 0 0 30px rgba(25, 135, 84, 0.2); }
    .ls-2 { letter-spacing: 2px; }
    .digital-seal { border-style: double !important; }
    @font-face {
        font-family: 'Outfit';
        src: url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap');
    }
</style>
@endsection

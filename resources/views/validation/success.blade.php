@extends('layouts.main')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5" 
     style="background: radial-gradient(circle at top right, #f8fafc, #f1f5f9);">
    
    <div class="col-lg-6">
        <div class="card-prestige p-0 border-0 bg-white shadow-2xl overflow-hidden" style="border-radius: 40px">
            {{-- Header de Validación --}}
            <div class="p-5 text-center bg-success bg-opacity-10 border-bottom border-success border-opacity-10 position-relative">
                <div class="position-absolute top-0 start-0 p-4 opacity-25">
                    <i class="bi bi-shield-check display-1 text-success"></i>
                </div>
                <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded-circle mb-4 shadow-accent" 
                     style="width: 100px; height: 100px; font-size: 3rem;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="display-6 fw-bold text-success mb-2" style="font-family: 'Outfit', sans-serif;">Documento <span class="text-dark">Verificado</span></h1>
                <p class="lead fw-medium text-success opacity-75 mb-0">Certificación de Autenticidad Institucional</p>
            </div>

            {{-- Detalles del Documento --}}
            <div class="p-5">
                <div class="row g-4 align-items-center">
                    <div class="col-md-7">
                        <h6 class="text-muted small fw-bold uppercase ls-2 mb-4">Titular de la Credencial</h6>
                        <h3 class="fw-bold mb-1 text-dark">{{ $document->collegiate->first_name }} {{ $document->collegiate->last_name }}</h3>
                        <p class="text-primary fw-bold mb-4 fs-5">MATRÍCULA: {{ $document->collegiate->registration_number }}</p>
                        
                        <div class="bg-light p-4 rounded-4 border border-light-subtle">
                            <h6 class="text-muted small fw-bold mb-2">Trámite Validado</h6>
                            <h5 class="fw-bold m-0">{{ $document->requirement->name }}</h5>
                            <hr class="my-3 opacity-10">
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Fecha de Emisión:</span>
                                <span class="fw-bold">{{ $document->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center border-start ps-lg-5">
                        <div class="digital-seal p-4 border border-5 border-success border-opacity-10 rounded-circle d-inline-block position-relative">
                            <i class="bi bi-qr-code-scan display-4 text-dark opacity-10"></i>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <span class="badge bg-success rounded-pill px-3 py-2 small shadow">ORIGINAL</span>
                            </div>
                        </div>
                        <p class="mt-4 small text-muted px-4">Este documento ha sido emitido electrónicamente y validado por el sistema de auditoría del Colegio.</p>
                    </div>
                </div>
            </div>

            {{-- Footer Institucional --}}
            <div class="px-5 py-4 bg-light border-top text-center">
                <p class="mb-0 small text-muted">© {{ date('Y') }} {{ $document->collegiate->school->name }} | Desarrollado por Colegio-Pro</p>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/" class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold small transition-all">Regresar al Portal Principal</a>
        </div>
    </div>
</div>

<style>
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .shadow-accent { box-shadow: 0 0 30px rgba(25, 135, 84, 0.3); }
    .ls-2 { letter-spacing: 2px; }
    .uppercase { text-transform: uppercase; }
    .digital-seal { border-style: double !important; }
    .card-prestige { animation: slideUp 0.6s ease-out; }
    @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endsection

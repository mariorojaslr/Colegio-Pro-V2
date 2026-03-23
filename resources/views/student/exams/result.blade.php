@extends('layouts.main')

@section('title', 'Resultado Evaluación | Academia Pro')

@section('content')
<div class="container-fluid px-4 py-5 min-vh-100 d-flex align-items-center" style="background: #0f172a;">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg text-center overflow-hidden" style="border-radius: 40px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-body p-4 p-lg-5">
                    @if($status == 'passed')
                        <div class="mb-5">
                            <i class="bi bi-patch-check-fill text-success" style="font-size: 5rem; text-shadow: 0 0 30px rgba(25, 135, 84, 0.4)"></i>
                        </div>
                        <h1 class="text-white fw-black mb-1 display-5" style="font-family: 'Outfit', sans-serif;">¡Felicitaciones!</h1>
                        <p class="text-white-50 fs-5 fw-light mb-5">Has aprobado satisfactoriamente la evaluación.</p>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-light bg-opacity-5">
                                    <div class="xx-small text-muted fw-bold ls-2 uppercase">TU PUNTAJE</div>
                                    <h2 class="text-success fw-black m-0">{{ $percentage }}%</h2>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-light bg-opacity-5">
                                    <div class="xx-small text-muted fw-bold ls-2 uppercase">CALIFICACIÓN</div>
                                    <h2 class="text-white fw-black m-0">APROBADO</h2>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success bg-success bg-opacity-10 border-0 rounded-4 text-start p-4 mb-5 shadow-sm text-center">
                            <h6 class="fw-bold mb-3"><i class="bi bi-award me-2"></i> CREDIENCIAL ACADÉMICA LISTA</h6>
                            @php
                                $cert = \App\Models\Certificate::where('user_id', auth()->id())->where('lesson_id', $exam->lesson_id)->first();
                            @endphp
                            @if($cert)
                                <a href="{{ route('student.certificates.download', $cert->id) }}" class="btn btn-warning rounded-pill px-5 py-3 fw-black shadow-lg text-dark">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> DESCARGAR CERTIFICADO
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="mb-5">
                            <i class="bi bi-exclamation-octagon-fill text-danger" style="font-size: 5rem; text-shadow: 0 0 30px rgba(220, 53, 69, 0.4)"></i>
                        </div>
                        <h1 class="text-white fw-black mb-1 display-5" style="font-family: 'Outfit', sans-serif;">Evaluación No Superada</h1>
                        <p class="text-white-50 fs-5 fw-light mb-5">Su puntaje actual no cumple con el mínimo requerido.</p>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-light bg-opacity-5">
                                    <div class="xx-small text-muted fw-bold ls-2 uppercase">TU PUNTAJE</div>
                                    <h2 class="text-danger fw-black m-0">{{ $percentage }}%</h2>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-4 bg-light bg-opacity-5">
                                    <div class="xx-small text-muted fw-bold ls-2 uppercase">REQUERIDO</div>
                                    <h2 class="text-white fw-black m-0">{{ $exam->passing_score }}%</h2>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger bg-danger bg-opacity-10 border-0 rounded-4 text-start p-4 mb-5 shadow-sm">
                            <h6 class="fw-bold mb-2"><i class="bi bi-arrow-repeat me-2"></i> Nuevo Intento</h6>
                            <p class="small text-white-50 m-0">Te recomendamos revisar nuevamente el material del curso antes de intentar la evaluación por segunda vez.</p>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-3 justify-content-center pt-4">
                        <a href="{{ route('student.lessons.show', $exam->lesson_id) }}" class="btn btn-primary rounded-pill px-5 py-3 fw-black shadow-lg">
                            <i class="bi bi-play-circle me-2"></i> VOLVER AL CURSO
                        </a>
                        <a href="{{ route('student.lessons.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold small">ACADEMIA</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .xx-small { font-size: 9px; }
    .ls-2 { letter-spacing: 2px; }
    .fw-black { font-weight: 900; }
</style>
@endsection

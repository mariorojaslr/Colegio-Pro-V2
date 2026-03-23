@extends('layouts.main')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-5" 
     style="background: radial-gradient(circle at top right, #fcf8f8, #f9f1f1);">
    
    <div class="col-lg-5 text-center px-4">
        <div class="bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center rounded-circle mb-5 shadow-sm" 
             style="width: 120px; height: 120px; font-size: 4rem; animation: pulseRed 2s infinite;">
            <i class="bi bi-shield-x"></i>
        </div>
        
        <h1 class="display-5 fw-bold text-dark mb-4" style="font-family: 'Outfit', sans-serif;">Error de <span class="text-danger">Validación</span></h1>
        
        <div class="card-prestige p-5 mb-5 border-0 bg-white shadow-2xl overflow-hidden" style="border-radius: 40px">
            <h4 class="fw-bold m-0 text-danger mb-3">Código Inválido</h4>
            <p class="lead opacity-75 mb-4 px-lg-4">{{ $message ?? 'El código de validación es inválido o el documento ha sido revocado por la institución.' }}</p>
            <p class="small text-muted mb-0">Si cree que esto es un error, por su seguridad no acepte el documento y verifique sus datos directamente con el Colegio Profesional.</p>
        </div>

        <div class="d-grid gap-3 col-md-8 mx-auto">
            <a href="/" class="btn btn-dark rounded-pill py-3 fw-bold small">Regresar al Portal</a>
            <button class="btn btn-outline-danger rounded-pill py-2 fw-bold small opacity-75">Reportar Uso Indebido</button>
        </div>
    </div>
</div>

<style>
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.2); } 70% { box-shadow: 0 0 0 20px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
    .card-prestige { animation: shake 0.5s ease-in-out; }
    @keyframes shake { 0%, 100% { transform: scale(1); } 20%, 60% { transform: scale(1.02); } 40%, 80% { transform: scale(0.98); } }
</style>
@endsection

@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-2xl rounded-4 p-5 bg-white mb-4">
                <div class="text-center mb-5">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-question-circle text-primary fs-1"></i>
                    </div>
                    <h2 class="fw-bold fs-3 mb-1" style="font-family: 'Outfit', sans-serif;">Nuevo Ticket de Soporte</h2>
                    <p class="text-muted">Describa su solicitud y un agente se pondrá en contacto pronto.</p>
                </div>

                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf
                    <div class="row g-4 mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small text-uppercase ls-1">Asunto de la solicitud</label>
                            <input type="text" name="subject" class="form-control rounded-pill px-4 py-3 border-light shadow-none" placeholder="Ej: Error al subir video..." required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase ls-1">Categoría</label>
                            <select name="category" class="form-select rounded-pill px-4 py-3 border-light shadow-none" required>
                                <option value="technical">Soporte Técnico</option>
                                <option value="billing">Facturación y Planes</option>
                                <option value="feature">Sugerencia de Mejora</option>
                                <option value="other">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase ls-1">Prioridad</label>
                            <select name="priority" class="form-select rounded-pill px-4 py-3 border-light shadow-none" required>
                                <option value="low">Baja</option>
                                <option value="medium" selected>Media</option>
                                <option value="high">Alta (Urgente)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Mensaje detallado</label>
                        <textarea name="message" class="form-control rounded-4 p-4 border-light shadow-none" rows="5" placeholder="Describa el problema con el mayor detalle posible..." required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-bold shadow-lg">
                            Enviar Solicitud <i class="bi bi-send ms-2"></i>
                        </button>
                        <a href="{{ route('tickets.index') }}" class="btn btn-light rounded-pill py-3 fw-bold border-light opacity-75">
                            Cancelar y Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

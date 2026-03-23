@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-lg-8">
            <h6 class="text-primary fw-bold text-uppercase ls-1 mb-2">Área Educativa</h6>
            <h1 class="display-5 fw-bold mb-0">Mis Cursos y Certificaciones</h1>
            <p class="text-muted">Gestione sus materiales de estudio, asistencia y descargue sus certificados validados con QR.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="bg-white p-3 rounded-4 shadow-sm border d-inline-block text-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                        <i class="bi bi-person-check-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Usuario Externo</h6>
                        <p class="small text-muted mb-0">Sin vinculación profesional</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Curso Activo --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 25px;">
                <div class="row g-0">
                    <div class="col-md-4" style="background: url('{{ asset('images/flyers/rcp.png') }}') center/cover;">
                        <div class="h-100 min-vh-25"></div>
                    </div>
                    <div class="col-md-8 p-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary px-3 py-2 rounded-pill small">EN CURSO</span>
                            <span class="text-muted small"><i class="bi bi-clock me-1"></i> Siguiente clase: Mañana 19:00hs</span>
                        </div>
                        <h3 class="fw-bold mb-3">Curso de RCP y Primeros Auxilios</h3>
                        <p class="text-muted mb-4">Módulos completados: 2 de 5. Su progreso actual es del 40%.</p>
                        
                        <div class="progress mb-4" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%"></div>
                        </div>

                        <div class="d-flex gap-3">
                            <button class="btn btn-primary rounded-pill px-5 py-3 fw-bold">Continuar Aprendiendo</button>
                            <button class="btn btn-outline-dark rounded-pill px-4">Ver Materiales</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certificados --}}
        <div class="col-md-12 mt-5">
            <h4 class="fw-bold mb-4"><i class="bi bi-award me-2 text-warning"></i> Sus Certificados Validados</h4>
            <div class="table-responsive bg-white rounded-4 shadow-sm p-4">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4">Curso</th>
                            <th class="border-0">Fecha Emisión</th>
                            <th class="border-0">Calificación</th>
                            <th class="border-0 text-end px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf fs-3 text-danger me-3"></i>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Estrategia en Gestión Judicial</h6>
                                        <p class="small text-muted mb-0 text-uppercase" style="font-size: 10px">ID: CERT-{{ uniqid() }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>12 de Febrero, 2026</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success fw-bold px-3">9.5 / 10</span></td>
                            <td class="text-end px-4">
                                <button class="btn btn-light btn-sm rounded-pill px-3 shadow-xs border"><i class="bi bi-qr-code me-1"></i> Ver QR</button>
                                <button class="btn btn-dark btn-sm rounded-pill px-3 shadow-xs"><i class="bi bi-download me-1"></i> Descargar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

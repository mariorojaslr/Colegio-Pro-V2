@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    {{-- Header de Sección --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Plan de <span class="text-gradient-gold">Documentación</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Configure los requisitos y las reglas de caducidad para el legajo profesional de sus colegiados.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button class="btn btn-warning rounded-pill px-4 py-3 fw-bold" data-bs-toggle="collapse" data-bs-target="#newRequirementForm">
                            <i class="bi bi-plus-lg me-2"></i> Nuevo Requisito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario para Nuevo Requisito --}}
    <div class="collapse mb-5" id="newRequirementForm">
        <div class="card-prestige p-5 border-0 bg-white shadow-sm">
            <h5 class="fw-bold mb-4">Configurar Nuevo Requisito</h5>
            <form action="{{ route('compliance_requirements.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Nombre del Documento</label>
                        <input type="text" name="name" class="form-control rounded-pill border-light-subtle" placeholder="Ej: DNI, Matrícula, Antecedentes..." required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Tipo de Trámite</label>
                        <select name="type" class="form-select rounded-pill border-light-subtle" required>
                            <option value="permanent">Permanente (Título, DNI)</option>
                            <option value="perentory">Perentorio (Vence)</option>
                            <option value="special">Especial (Carga única)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Frecuencia de Renovación</label>
                        <select name="expiry_frequency" class="form-select rounded-pill border-light-subtle" required>
                            <option value="none">Sin Vencimiento</option>
                            <option value="semester">Semestral</option>
                            <option value="year">Anual</option>
                            <option value="fixed">Fecha Específica</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check form-switch mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="is_mandatory" value="1" checked id="mandatorySwitch">
                            <label class="form-check-label fw-bold small" for="mandatorySwitch">Es Obligatorio</label>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-dark rounded-pill px-5 py-2 fw-bold">Guardar Requisito <i class="bi bi-check2-circle ms-2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Listado de Requisitos Actual --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 bg-white min-vh-50">
                <h5 class="fw-bold mb-4 text-dark">Matriz de Requisitos Institucionales</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle border-top">
                        <thead class="bg-light">
                            <tr class="small fw-bold text-muted text-uppercase">
                                <th class="py-3 px-4">Documento</th>
                                <th class="py-3">Tipo</th>
                                <th class="py-3">Renovación</th>
                                <th class="py-3">Estado</th>
                                <th class="py-3 text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requirements as $requirement)
                            <tr>
                                <td class="py-3 px-4">
                                    <div class="fw-bold fs-6 text-dark">{{ $requirement->name }}</div>
                                    <div class="small text-muted">{{ $requirement->description ?? 'Sin descripción adicional.' }}</div>
                                </td>
                                <td class="py-3">
                                    @php
                                        $typeMap = [
                                            'permanent' => ['label' => 'Permanente', 'class' => 'bg-primary-subtle text-primary'],
                                            'perentory' => ['label' => 'Caducable', 'class' => 'bg-warning-subtle text-warning'],
                                            'special' => ['label' => 'Especial', 'class' => 'bg-info-subtle text-info']
                                        ];
                                    @endphp
                                    <span class="badge rounded-pill px-3 {{ $typeMap[$requirement->type]['class'] ?? 'bg-light' }}">
                                        {{ $typeMap[$requirement->type]['label'] ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @php
                                        $freqMap = [
                                            'none' => 'Nunca vence',
                                            'semester' => 'Cada 6 meses',
                                            'year' => 'Anual',
                                            'fixed' => 'Fecha fija'
                                        ];
                                    @endphp
                                    <span class="text-muted fw-medium">{{ $freqMap[$requirement->expiry_frequency] ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 text-uppercase">
                                    @if($requirement->is_mandatory)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 fw-bold small">Bloqueante</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 fw-bold small">Opcional</span>
                                    @endif
                                </td>
                                <td class="py-3 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu shadow border-0 rounded-4">
                                            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-pencil me-2"></i> Editar</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('compliance_requirements.destroy', $requirement) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger py-2" onclick="return confirm('¿Seguro quieres eliminar este requisito?')">
                                                        <i class="bi bi-trash me-2"></i> Eliminar
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    <i class="bi bi-folder-x fs-1 opacity-25 d-block mb-3"></i>
                                    No hay requisitos definidos para este colegio.<br>
                                    Cree uno para comenzar a gestionar el legajo digital.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .card-prestige { transition: all 0.3s ease; }
    .table-hover tbody tr:hover { background: rgba(0,0,0,0.01); }
</style>
@endsection

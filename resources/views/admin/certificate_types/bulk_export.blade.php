@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Exportación Masiva de Certificados</h1>
            <p class="text-muted">Seleccione los matriculados para generar el lote de producción para imprenta (Grafilab).</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificate_types.edit', $certificate_type->id) }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver al Diseñador
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Columna Izquierda: Listado de Colegiados y Selección -->
        <div class="col-xl-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <!-- Buscador y Filtros -->
                    <div class="row g-3 mb-4 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-pill px-3"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="collegiateSearchInput" class="form-control bg-light border-0 rounded-end-pill py-2.5 shadow-none" placeholder="Buscar por Nombre, Apellido o DNI...">
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold me-2" onclick="selectAll(true)">Marcar Todos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold" onclick="selectAll(false)">Desmarcar Todos</button>
                        </div>
                    </div>

                    <form id="bulkForm" method="POST">
                        @csrf
                        
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-hover align-middle" id="collegiatesTable">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th style="width: 50px;">Selección</th>
                                        <th>Matrícula</th>
                                        <th>Colegiado</th>
                                        <th>DNI</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($collegiates as $collegiate)
                                        <tr class="collegiate-row">
                                            <td>
                                                <input type="checkbox" name="collegiate_ids[]" value="{{ $collegiate->id }}" class="form-check-input collegiate-checkbox" onchange="updateSelectionsCount()">
                                            </td>
                                            <td class="font-monospace fw-bold text-primary">{{ $collegiate->registration_number }}</td>
                                            <td class="fw-bold search-name">{{ $collegiate->last_name }}, {{ $collegiate->first_name }}</td>
                                            <td class="search-dni">{{ $collegiate->dni }}</td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">Activo</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No se encontraron colegiados activos en esta institución.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Acciones de Imprenta (Grafilab) -->
        <div class="col-xl-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-printer-fill text-primary me-2"></i> Lote de Producción</h6>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info rounded-3 mb-4 border-0 small">
                        <strong>Trámite:</strong> {{ $certificate_type->name }}<br>
                        <strong>Lienzo:</strong> {{ strtoupper($certificate_type->page_size) }} ({{ $certificate_type->page_orientation === 'landscape' ? 'Apaisado' : 'Vertical' }})<br>
                        <strong>Fondo subido:</strong> {{ $certificate_type->background_path ? 'Sí' : 'No (Fallback clásico)' }}
                    </div>

                    <div class="bg-light p-3 rounded-4 mb-4 text-center">
                        <div class="fs-1 fw-black text-primary" id="selectedCount">0</div>
                        <div class="small fw-bold text-muted text-uppercase">Profesionales Seleccionados</div>
                    </div>

                    <!-- Acción 1: Descarga directa PDF -->
                    <button type="button" onclick="submitBulkForm('{{ route('admin.certificate_types.export_bulk_pdf', $certificate_type->id) }}')" class="btn btn-primary rounded-pill px-4 w-100 fw-bold py-2.5 mb-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Descargar PDF Consolidado
                    </button>

                    <hr class="my-4">

                    <!-- Acción 2: Envío a imprenta asociada -->
                    <h6 class="fw-bold mb-3 small text-uppercase text-muted"><i class="bi bi-envelope-fill me-1"></i> Envío Directo a Grafilab</h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email de la Imprenta</label>
                        <input type="email" id="imprentaEmail" class="form-control rounded-3" value="imprenta@grafilab.com" placeholder="ejemplo@grafilab.com">
                        <small class="text-muted d-block mt-1">El PDF masivo se enviará adjunto a esta dirección.</small>
                    </div>

                    <button type="button" onclick="submitEmailForm('{{ route('admin.certificate_types.email_bulk', $certificate_type->id) }}')" class="btn btn-outline-success rounded-pill px-4 w-100 fw-bold py-2.5 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-send-check-fill"></i> Despachar a Imprenta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Buscador interactivo en vivo
    document.getElementById('collegiateSearchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('.collegiate-row');
        
        rows.forEach(row => {
            const name = row.querySelector('.search-name').innerText.toLowerCase();
            const dni = row.querySelector('.search-dni').innerText.toLowerCase();
            
            if (name.includes(query) || dni.includes(query)) {
                row.classList.remove('d-none');
            } else {
                row.classList.add('d-none');
            }
        });
    });

    // Marcar / Desmarcar todos
    function selectAll(status) {
        // Solo marcar los que están visibles (no ocultos por el buscador)
        const visibleRows = document.querySelectorAll('.collegiate-row:not(.d-none)');
        visibleRows.forEach(row => {
            const checkbox = row.querySelector('.collegiate-checkbox');
            checkbox.checked = status;
        });
        updateSelectionsCount();
    }

    // Actualizar contador
    function updateSelectionsCount() {
        const checkedCount = document.querySelectorAll('.collegiate-checkbox:checked').length;
        document.getElementById('selectedCount').innerText = checkedCount;
    }

    // Enviar el formulario para descargar el PDF
    function submitBulkForm(actionUrl) {
        const checkedCount = document.querySelectorAll('.collegiate-checkbox:checked').length;
        if (checkedCount === 0) {
            alert('Por favor, seleccione al menos un colegiado para generar el lote.');
            return;
        }
        
        const form = document.getElementById('bulkForm');
        form.action = actionUrl;
        form.submit();
    }

    // Enviar el formulario por email
    function submitEmailForm(actionUrl) {
        const checkedCount = document.querySelectorAll('.collegiate-checkbox:checked').length;
        if (checkedCount === 0) {
            alert('Por favor, seleccione al menos un colegiado para enviar.');
            return;
        }

        const email = document.getElementById('imprentaEmail').value;
        if (!email) {
            alert('Por favor, introduzca una dirección de correo válida para la imprenta.');
            return;
        }

        if (confirm(`¿Seguro que deseas generar y enviar el lote de ${checkedCount} certificados a la imprenta (${email})?`)) {
            // Añadir campo dinámico de email al formulario
            const form = document.getElementById('bulkForm');
            
            // Eliminar input de email anterior si existe
            const existingEmailInput = document.getElementById('dynamicEmailInput');
            if (existingEmailInput) existingEmailInput.remove();
            
            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.id = 'dynamicEmailInput';
            emailInput.value = email;
            
            form.appendChild(emailInput);
            form.action = actionUrl;
            form.submit();
        }
    }
</script>
@endsection

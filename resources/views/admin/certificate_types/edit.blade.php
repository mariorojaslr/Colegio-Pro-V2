@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Editar Trámite / Certificado</h1>
            <p class="text-muted">Modifique los parámetros y el diseño del certificado.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificate_types.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('admin.certificate_types.update', $certificate_type) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Columna Izquierda: Configuraciones -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-gear-fill text-theme-primary me-2"></i> Configuración General</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nombre del Trámite</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $certificate_type->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" value="{{ $certificate_type->price }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Validez (Días)</label>
                            <input type="number" name="validity_days" class="form-control rounded-3" value="{{ $certificate_type->validity_days }}" placeholder="Vacío = Ilimitado">
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_single_use" value="1" id="isSingleUse" {{ $certificate_type->is_single_use ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-danger" for="isSingleUse">Es de 1 solo uso</label>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Código QR de Validación</h6>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="has_qr" value="1" id="hasQr" {{ $certificate_type->has_qr ? 'checked' : '' }}>
                            <label class="form-check-label small" for="hasQr">Incluir Código QR en el PDF</label>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Reglas de Restricción</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance" {{ $certificate_type->requires_clearance ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqClearance">
                                Exigir Libre de Deuda
                            </label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics" {{ $certificate_type->requires_no_sanctions ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqEthics">
                                Exigir Habilitación Ética
                            </label>
                        </div>

                        <hr>
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $certificate_type->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-success" for="isActive">Trámite Activo (Visible para los usuarios)</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0 text-center">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 w-100 fw-bold">Guardar Cambios</button>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Editor de Plantilla -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-richtext-fill text-theme-primary me-2"></i> Diseño del Certificado</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="alert alert-info rounded-3 small mb-4 border-0">
                            <strong><i class="bi bi-info-circle-fill me-2"></i> Guía de Variables:</strong><br>
                            Puedes utilizar las siguientes variables en el texto. Serán reemplazadas automáticamente por los datos reales del colegiado al momento de generarse el PDF:
                            <ul class="mb-0 mt-2">
                                <li><code>@{{nombre}}</code> - Nombre y Apellido del profesional</li>
                                <li><code>@{{dni}}</code> - Número de Documento</li>
                                <li><code>@{{matricula}}</code> - Número de Matrícula</li>
                                <li><code>@{{fecha_emision}}</code> - Fecha en la que se generó el certificado</li>
                                <li><code>@{{valido_hasta}}</code> - Fecha de vencimiento (si aplica)</li>
                            </ul>
                        </div>

@php
    $defaultTemplate = 'Por la presente, el Consejo Directivo certifica y hace constar que el/la profesional:

<div style="text-align: center; font-size: 22px; font-weight: bold; margin: 20px 0;">@{{nombre}}</div>

Con documento de identidad Nº <strong>@{{dni}}</strong>, se encuentra debidamente registrado/a en esta Institución bajo la matrícula profesional número <strong>@{{matricula}}</strong>.

Se expide el presente certificado a solicitud del interesado/a, a los @{{fecha_emision}}.';
@endphp
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted mb-2">Contenido de la Plantilla HTML</label>
                            
                            <!-- Botones de Variables Rápidas -->
                            <div class="d-flex flex-wrap gap-2 mb-3 bg-light p-2 rounded border">
                                <span class="small fw-bold text-muted d-flex align-items-center me-2">
                                    <i class="bi bi-magic text-primary me-1"></i> Insertar:
                                </span>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="insertVar('@{{nombre}}')">Nombre y Apellido</button>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="insertVar('@{{dni}}')">DNI</button>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="insertVar('@{{matricula}}')">Nº Matrícula</button>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="insertVar('@{{fecha_emision}}')">Fecha de Emisión</button>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="insertVar('@{{valido_hasta}}')">Válido Hasta</button>
                            </div>

                            <textarea name="template_content" id="templateEditor" class="form-control" rows="15">{!! old('template_content', $certificate_type->template_content ?? $defaultTemplate) !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- CKEditor 4 -->
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    // Inicializar CKEditor 4
    CKEDITOR.replace('templateEditor', {
        height: 500,
        language: 'es',
        toolbar: [
            { name: 'document', items: [ 'Source', '-', 'Preview', 'Print' ] },
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
            { name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll' ] },
            '/',
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
            { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak' ] },
            '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] }
        ]
    });

    function insertVar(variableName) {
        CKEDITOR.instances.templateEditor.insertText(variableName);
    }
</script>
@endpush

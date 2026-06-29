@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Nuevo Trámite / Certificado</h1>
            <p class="text-muted">Configure los parámetros y el diseño del nuevo certificado.</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.certificate_types.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('admin.certificate_types.store') }}" method="POST">
        @csrf
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
                            <input type="text" name="name" class="form-control rounded-3" required placeholder="Ej: Certificado de Ética">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" value="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Validez (Días)</label>
                            <input type="number" name="validity_days" class="form-control rounded-3" placeholder="Vacío = Ilimitado">
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_single_use" value="1" id="isSingleUse">
                            <label class="form-check-label small fw-bold text-danger" for="isSingleUse">Es de 1 solo uso</label>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Código QR de Validación</h6>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="has_qr" value="1" id="hasQr" checked>
                            <label class="form-check-label small" for="hasQr">Incluir Código QR en el PDF</label>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Reglas de Restricción</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance">
                            <label class="form-check-label small" for="reqClearance">
                                Exigir Libre de Deuda
                            </label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics">
                            <label class="form-check-label small" for="reqEthics">
                                Exigir Habilitación Ética
                            </label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0 text-center">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 w-100 fw-bold">Guardar Trámite</button>
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

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Contenido de la Plantilla HTML</label>
                        </div>

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

                            <textarea name="template_content" id="templateEditor" class="form-control" rows="15">Por la presente, el Consejo Directivo certifica y hace constar que el/la profesional:

&lt;div style="text-align: center; font-size: 22px; font-weight: bold; margin: 20px 0;"&gt;@{{nombre}}&lt;/div&gt;

Con documento de identidad Nº &lt;strong&gt;@{{dni}}&lt;/strong&gt;, se encuentra debidamente registrado/a en esta Institución bajo la matrícula profesional número &lt;strong&gt;@{{matricula}}&lt;/strong&gt;.

Se expide el presente certificado a solicitud del interesado/a, a los @{{fecha_emision}}.</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- jQuery Local -->
<script src="{{ asset('summernote/jquery.min.js') }}"></script>
<!-- Summernote Local -->
<link href="{{ asset('summernote/summernote-lite.min.css') }}" rel="stylesheet">
<script src="{{ asset('summernote/summernote-lite.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#templateEditor').summernote({
            height: 500,
            lang: 'es-ES',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph', 'height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    function insertVar(variableName) {
        $('#templateEditor').summernote('editor.insertText', variableName);
    }
</script>
@endpush

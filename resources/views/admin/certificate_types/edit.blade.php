@extends('layouts.main')

@section('styles')
<style>
    /* Estilos del Lienzo del Certificado */
    #certificateCanvas {
        position: relative;
        background-color: #e2e8f0;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        overflow: hidden;
        margin: 0 auto;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        transition: all 0.3s ease;
    }
    
    /* Dimensiones en proporción A4 Landscape (base 820px) */
    .canvas-a4-landscape { width: 820px; height: 580px; }
    .canvas-a4-portrait { width: 580px; height: 820px; }
    
    /* Dimensiones en proporción A5 Landscape */
    .canvas-a5-landscape { width: 620px; height: 438px; }
    .canvas-a5-portrait { width: 438px; height: 620px; }

    /* Dimensiones proporción Tarjeta 10x15 (2:3) */
    .canvas-10x15-landscape { width: 620px; height: 413px; }
    .canvas-10x15-portrait { width: 413px; height: 620px; }
    
    /* Dimensiones Flyer (1:1) */
    .canvas-flyer-landscape { width: 620px; height: 620px; }
    .canvas-flyer-portrait { width: 620px; height: 620px; }

    .draggable-var {
        position: absolute;
        padding: 8px 14px;
        background-color: rgba(30, 58, 138, 0.85);
        color: #ffffff;
        border: 1.5px solid #1E3A8A;
        border-radius: 6px;
        cursor: move;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        user-select: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        z-index: 10;
        transition: border-color 0.2s;
    }
    
    /* Cuerpo del texto libre: puede ser multi-linea */
    .draggable-cuerpo {
        background-color: rgba(15, 23, 42, 0.85) !important;
        border: 1.5px solid #0f172a !important;
        white-space: normal !important;
        max-width: 80%;
        text-align: center;
    }
    
    /* Titulo de Cabecera */
    .draggable-titulo {
        background-color: rgba(29, 78, 216, 0.85) !important;
        border: 1.5px solid #1d4ed8 !important;
        font-weight: bold;
    }

    .draggable-var.active {
        border-color: #DC2626 !important;
        background-color: rgba(220, 38, 38, 0.95) !important;
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.4);
        color: #ffffff !important;
    }
    
    /* Bloque QR especial */
    .draggable-qr {
        background-color: rgba(255, 255, 255, 0.95) !important;
        color: #333333 !important;
        border: 2px dashed #000000 !important;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: bold;
    }
    
    /* Firmas de Autoridades */
    .draggable-firma {
        background-color: rgba(5, 150, 105, 0.9) !important;
        border: 1.5px solid #047857 !important;
        text-align: center;
        line-height: 1.3;
        font-size: 11px;
    }

    .canvas-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background-color: #f1f5f9;
        color: #64748b;
        text-align: center;
        padding: 2rem;
    }
</style>
@endsection

@section('content')
@php
    // Configuracion por defecto del lienzo
    $defaultSettings = [
        'titulo' => ['x' => 50, 'y' => 15, 'font_size' => 28, 'font_weight' => 'bold', 'text_align' => 'center', 'color' => '#1e3a8a', 'text' => 'CERTIFICADO', 'visible' => true],
        'cuerpo' => ['x' => 50, 'y' => 40, 'font_size' => 15, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#333333', 'visible' => true],
        'qr' => ['x' => 84, 'y' => 80, 'width' => 80, 'height' => 80, 'visible' => true],
        'firmas' => []
    ];
    
    $settings = array_merge($defaultSettings, $certificate_type->design_settings ?? []);
    
    // Texto por defecto del cuerpo
    $cuerpoTextoRaw = $certificate_type->template_content ?: "Por la presente, el Consejo Directivo certifica y hace constar que el/la profesional:\n\n{{nombre}}\n\nCon documento de identidad Nº {{dni}}, se encuentra debidamente registrado/a en esta Institución bajo la matrícula profesional número {{matricula}}.\n\nSe expide el presente certificado a solicitud del interesado/a, a los {{fecha_emision}}.";
@endphp
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Diseñador de Plantillas de Certificados</h1>
            <p class="text-muted">Acomode textos dinámicos, incorpore firmas de autoridades reales y guarde el formato.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('admin.certificate_types.export_bulk_view', $certificate_type->id) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-printer-fill"></i> Impresión Masiva / Grafilab
            </a>
            <a href="{{ route('admin.certificate_types.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.certificate_types.update', $certificate_type) }}" method="POST" enctype="multipart/form-data" id="designForm">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="design_settings" id="designSettingsInput">

        <div class="row">
            <!-- Columna Izquierda: Configuraciones y Firmas -->
            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-gear-fill text-primary me-2"></i> Parámetros de Configuración</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nombre del Trámite</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $certificate_type->name }}" required>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Precio ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control rounded-3" value="{{ $certificate_type->price }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Validez (Días)</label>
                                <input type="number" name="validity_days" class="form-control rounded-3" value="{{ $certificate_type->validity_days }}" placeholder="Ilimitado">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Tamaño</label>
                                <select name="page_size" id="pageSizeSelect" class="form-select rounded-3">
                                    <option value="a4" {{ $certificate_type->page_size === 'a4' ? 'selected' : '' }}>A4 (Estándar)</option>
                                    <option value="a5" {{ $certificate_type->page_size === 'a5' ? 'selected' : '' }}>A5 (Mediano)</option>
                                    <option value="10x15" {{ $certificate_type->page_size === '10x15' ? 'selected' : '' }}>Tarjeta (10x15 cm)</option>
                                    <option value="flyer" {{ $certificate_type->page_size === 'flyer' ? 'selected' : '' }}>Flyer / Credencial</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Orientación</label>
                                <select name="page_orientation" id="pageOrientationSelect" class="form-select rounded-3">
                                    <option value="landscape" {{ $certificate_type->page_orientation === 'landscape' ? 'selected' : '' }}>Apaisado / Horiz.</option>
                                    <option value="portrait" {{ $certificate_type->page_orientation === 'portrait' ? 'selected' : '' }}>Vertical / Retrato</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Imagen de Fondo (PNG / JPG)</label>
                            <input type="file" name="background_image" id="bgImageFile" class="form-control rounded-3">
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted"><i class="bi bi-pen-fill text-success me-2"></i> Firmantes del Certificado</h6>
                        <p class="text-muted small mb-3">Seleccione qué autoridades de la institución firman este documento. Aparecerán de inmediato en el lienzo para posicionarse.</p>
                        
                        <div class="bg-light p-3 rounded-3 mb-4" style="max-height: 180px; overflow-y: auto;">
                            @forelse($boardMembers as $member)
                                <div class="form-check mb-2">
                                    <input class="form-check-input signatory-checkbox" type="checkbox" name="signatory_ids[]" value="{{ $member->id }}" id="signatory_{{ $member->id }}" {{ in_array($member->id, $selectedSignatoryIds) ? 'checked' : '' }} onchange="toggleSignatoryBlock({{ $member->id }}, '{{ $member->name }}', '{{ $member->role }}')">
                                    <label class="form-check-label small" for="signatory_{{ $member->id }}">
                                        <strong>{{ $member->name }}</strong> <br><span class="text-muted x-small">{{ $member->role }}</span>
                                    </label>
                                </div>
                            @empty
                                <div class="text-muted small text-center">No hay autoridades registradas en la institución.</div>
                            @endforelse
                        </div>

                        <hr class="my-4">
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance" {{ $certificate_type->requires_clearance ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqClearance">Exigir Libre de Deuda</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics" {{ $certificate_type->requires_no_sanctions ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqEthics">Exigir Habilitación Ética</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="has_qr" value="1" id="hasQr" {{ $certificate_type->has_qr ? 'checked' : '' }} onchange="toggleQrBlock()">
                            <label class="form-check-label small fw-bold text-dark" for="hasQr">Incluir QR de Validación</label>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $certificate_type->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-success" for="isActive">Trámite Habilitado para Socios</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0 text-center">
                        <button type="button" onclick="submitDesignForm()" class="btn btn-primary rounded-pill px-4 w-100 fw-bold py-2.5 shadow-sm">Guardar Cambios de Diseño</button>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Diseñador y Modificación de Textos -->
            <div class="col-xl-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-richtext-fill text-primary me-2"></i> Editor y Maqueta en Tiempo Real</h6>
                        <a href="{{ route('admin.certificate_types.preview', $certificate_type->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold"><i class="bi bi-eye"></i> Vista Previa PDF</a>
                    </div>
                    
                    <div class="card-body p-4 text-center">
                        <!-- Panel de Propiedades del Bloque Activo -->
                        <div id="elementSettingsPanel" class="bg-light p-3 rounded-4 mb-4 text-start border border-dashed d-none">
                            <h6 class="small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-sliders me-1"></i> Formato de Bloque: <span id="activeVarName" class="text-primary font-monospace"></span></h6>
                            
                            <!-- Editor de Texto Libre (Solo Cuerpo / Titulo) -->
                            <div class="row g-3 mb-3 d-none" id="textEditingRow">
                                <div class="col-12">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted mb-1">Texto del Bloque</label>
                                    <!-- Si es titulo usaremos input, si es cuerpo usaremos textarea -->
                                    <input type="text" id="activeTextInput" class="form-control form-control-sm rounded-3 d-none" oninput="updateActiveText(this.value)">
                                    <textarea id="activeTextAreaInput" class="form-control rounded-3 d-none" rows="3" oninput="updateActiveText(this.value)" name="template_content">{!! $certificate_type->template_content !!}</textarea>
                                    <small class="text-muted d-none" id="varsGuideText">
                                        Variables disponibles: <code>@{{nombre}}</code>, <code>@{{dni}}</code>, <code>@{{matricula}}</code>, <code>@{{fecha_emision}}</code>, <code>@{{valido_hasta}}</code>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row g-3 align-items-center">
                                <div class="col-6 col-md-2" id="fontSizeCol">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted mb-1">Tamaño</label>
                                    <select id="varFontSize" class="form-select form-select-sm" onchange="updateActiveStyle()">
                                        @for($i=10; $i<=60; $i+=2)
                                            <option value="{{ $i }}">{{ $i }}px</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6 col-md-2" id="fontColorCol">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted mb-1">Color</label>
                                    <input type="color" id="varFontColor" class="form-control form-control-sm form-control-color w-100" onchange="updateActiveStyle()">
                                </div>
                                <div class="col-6 col-md-2" id="fontAlignmentCol">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted mb-1">Alineación</label>
                                    <select id="varFontAlign" class="form-select form-select-sm" onchange="updateActiveStyle()">
                                        <option value="left">Izquierda</option>
                                        <option value="center" selected>Centro</option>
                                        <option value="right">Derecha</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3" id="fontWeightCol">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="varFontBold" onchange="updateActiveStyle()">
                                        <label class="form-check-label x-small fw-bold text-uppercase" for="varFontBold">Negrita</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3" id="visibilityCol">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="varVisible" onchange="updateActiveVisibility()">
                                        <label class="form-check-label x-small fw-bold text-uppercase text-danger" for="varVisible">Mostrar en PDF</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lienzo del Certificado -->
                        <div id="certificateCanvas" class="canvas-a4-landscape">
                            @if($certificate_type->background_path)

                                <!-- Bloque Título de Encabezado -->
                                <div id="var_titulo" class="draggable-var draggable-titulo {{ ($settings['titulo']['visible'] ?? true) ? '' : 'd-none' }}" data-var="titulo" style="left: {{ $settings['titulo']['x'] }}%; top: {{ $settings['titulo']['y'] }}%; font-size: {{ $settings['titulo']['font_size'] ?? 28 }}px; font-weight: {{ $settings['titulo']['font_weight'] ?? 'bold' }}; color: {{ $settings['titulo']['color'] ?? '#1e3a8a' }};">
                                    {{ $settings['titulo']['text'] ?? 'CERTIFICADO' }}
                                </div>
                                
                                <!-- Bloque Cuerpo de Párrafo -->
                                <div id="var_cuerpo" class="draggable-var draggable-cuerpo {{ ($settings['cuerpo']['visible'] ?? true) ? '' : 'd-none' }}" data-var="cuerpo" style="left: {{ $settings['cuerpo']['x'] }}%; top: {{ $settings['cuerpo']['y'] }}%; font-size: {{ $settings['cuerpo']['font_size'] ?? 15 }}px; font-weight: {{ $settings['cuerpo']['font_weight'] ?? 'normal' }}; color: {{ $settings['cuerpo']['color'] ?? '#333333' }};">
                                    <!-- El contenido se reemplazará vía JS -->
                                    Cuerpo del Certificado
                                </div>
                                
                                <!-- Bloque Código QR -->
                                <div id="var_qr" class="draggable-var draggable-qr {{ ($settings['qr']['visible'] ?? true) ? '' : 'd-none' }}" data-var="qr" style="left: {{ $settings['qr']['x'] }}%; top: {{ $settings['qr']['y'] }}%; width: {{ $settings['qr']['width'] ?? 80 }}px; height: {{ $settings['qr']['height'] ?? 80 }}px;">
                                    Código QR<br>Validación
                                </div>

                                <!-- Bloques de Firmas de Autoridades Dinámicas -->
                                @foreach($boardMembers as $member)
                                    @php
                                        $fConfig = $settings['firmas'][$member->id] ?? ['x' => 15 + ($loop->index * 25), 'y' => 80, 'font_size' => 11, 'color' => '#047857', 'visible' => in_array($member->id, $selectedSignatoryIds)];
                                    @endphp
                                    <div id="var_firma_{{ $member->id }}" class="draggable-var draggable-firma {{ $fConfig['visible'] ? '' : 'd-none' }}" data-var="firma_{{ $member->id }}" style="left: {{ $fConfig['x'] }}%; top: {{ $fConfig['y'] }}%; font-size: {{ $fConfig['font_size'] ?? 11 }}px; color: {{ $fConfig['color'] ?? '#047857' }};">
                                        {!! nl2br(e($member->name)) !!}<br><small style="font-size: 8px;">{{ $member->role }}</small>
                                    </div>
                                @endforeach
                            @else
                                <div class="canvas-placeholder">
                                    <i class="bi bi-image fs-1 text-muted mb-2"></i>
                                    <h5 class="fw-bold mb-1">Sin Imagen de Fondo</h5>
                                    <p class="small text-muted max-width-600 mb-0">Sube una imagen de fondo en el panel de configuración de la izquierda para activar el editor Drag & Drop.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Variables de control
    let activeElement = null;
    const canvas = document.getElementById('certificateCanvas');
    
    // Mapeo inicial
    @if($certificate_type->background_path)
        canvas.style.backgroundImage = "url('{{ asset($certificate_type->background_path) }}')";
    @endif

    // Objeto consolidado de diseño
    let designData = {
        titulo: {!! json_encode($settings['titulo'] ?? ['x' => 50, 'y' => 15, 'font_size' => 28, 'font_weight' => 'bold', 'text_align' => 'center', 'color' => '#1e3a8a', 'text' => 'CERTIFICADO', 'visible' => true]) !!},
        cuerpo: {!! json_encode($settings['cuerpo'] ?? ['x' => 50, 'y' => 40, 'font_size' => 15, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#333333', 'visible' => true]) !!},
        qr: {!! json_encode($settings['qr'] ?? ['x' => 84, 'y' => 80, 'width' => 80, 'height' => 80, 'visible' => true]) !!},
        firmas: {!! json_encode($settings['firmas'] ?? (object)[]) !!}
    };

    // Datos por defecto del cuerpo
    let cuerpoTextoRaw = `{!! addslashes($cuerpoTextoRaw) !!}`;

    // Reemplazo en vivo de variables para el lienzo
    function renderCuerpoPreview() {
        const cuerpoEl = document.getElementById('var_cuerpo');
        if (!cuerpoEl) return;
        
        let text = cuerpoTextoRaw;
        
        // Reemplazos de demostración
        text = text.replace(/\{\{nombre\}\}/g, 'Karina Arias');
        text = text.replace(/\{\{dni\}\}/g, '12.345.678');
        text = text.replace(/\{\{matricula\}\}/g, 'MAT-0024');
        text = text.replace(/\{\{fecha_emision\}\}/g, '{{ date("d/m/Y") }}');
        text = text.replace(/\{\{valido_hasta\}\}/g, '{{ $certificate_type->validity_days ? \Carbon\Carbon::parse(now())->addDays($certificate_type->validity_days)->format("d/m/Y") : "Ilimitado" }}');
        
        // Renderizar saltos de linea
        cuerpoEl.innerHTML = text.replace(/\n/g, '<br>');
    }

    // Cambiar tamaño del papel
    function updateCanvasSize() {
        const size = document.getElementById('pageSizeSelect').value;
        const orientation = document.getElementById('pageOrientationSelect').value;
        
        canvas.className = '';
        canvas.classList.add(`canvas-${size}-${orientation}`);
        
        repositionAllElements();
    }

    document.getElementById('pageSizeSelect').addEventListener('change', updateCanvasSize);
    document.getElementById('pageOrientationSelect').addEventListener('change', updateCanvasSize);

    window.addEventListener('DOMContentLoaded', () => {
        updateCanvasSize();
        renderCuerpoPreview();
        initDragAndDrop();
    });

    // Inicializar Drag & Drop
    function initDragAndDrop() {
        let draggables = document.querySelectorAll('.draggable-var');
        draggables.forEach(el => {
            el.addEventListener('mousedown', startDrag);
            el.addEventListener('click', function(e) {
                e.stopPropagation();
                selectElement(this);
            });
            el.addEventListener('touchstart', startDragTouch, { passive: false });
        });

        canvas.addEventListener('click', () => {
            deselectAll();
        });
    }

    function selectElement(el) {
        deselectAll();
        activeElement = el;
        el.classList.add('active');
        
        const varName = el.getAttribute('data-var');
        document.getElementById('activeVarName').innerText = varName.toUpperCase();
        document.getElementById('elementSettingsPanel').classList.remove('d-none');
        
        // Ocultar fila de textos por defecto
        document.getElementById('textEditingRow').classList.add('d-none');
        document.getElementById('activeTextInput').classList.add('d-none');
        document.getElementById('activeTextAreaInput').classList.add('d-none');
        document.getElementById('varsGuideText').classList.add('d-none');
        
        // Mostrar variables de fuente
        document.getElementById('fontSizeCol').classList.remove('d-none');
        document.getElementById('fontColorCol').classList.remove('d-none');
        document.getElementById('fontAlignmentCol').classList.remove('d-none');
        document.getElementById('fontWeightCol').classList.remove('d-none');
        document.getElementById('visibilityCol').classList.remove('d-none');

        // Cargar valores según tipo de elemento
        if (varName === 'titulo') {
            document.getElementById('textEditingRow').classList.remove('d-none');
            document.getElementById('activeTextInput').classList.remove('d-none');
            document.getElementById('activeTextInput').value = designData.titulo.text || 'CERTIFICADO';
            
            document.getElementById('varFontSize').value = designData.titulo.font_size || 28;
            document.getElementById('varFontColor').value = designData.titulo.color || '#1e3a8a';
            document.getElementById('varFontAlign').value = designData.titulo.text_align || 'center';
            document.getElementById('varFontBold').checked = designData.titulo.font_weight === 'bold';
            document.getElementById('varVisible').checked = designData.titulo.visible !== false;
            
        } else if (varName === 'cuerpo') {
            document.getElementById('textEditingRow').classList.remove('d-none');
            document.getElementById('activeTextAreaInput').classList.remove('d-none');
            document.getElementById('varsGuideText').classList.remove('d-none');
            document.getElementById('activeTextAreaInput').value = cuerpoTextoRaw;
            
            document.getElementById('varFontSize').value = designData.cuerpo.font_size || 15;
            document.getElementById('varFontColor').value = designData.cuerpo.color || '#333333';
            document.getElementById('varFontAlign').value = designData.cuerpo.text_align || 'center';
            document.getElementById('varFontBold').checked = designData.cuerpo.font_weight === 'bold';
            document.getElementById('varVisible').checked = designData.cuerpo.visible !== false;
            
        } else if (varName === 'qr') {
            document.getElementById('fontSizeCol').classList.add('d-none');
            document.getElementById('fontColorCol').classList.add('d-none');
            document.getElementById('fontAlignmentCol').classList.add('d-none');
            document.getElementById('fontWeightCol').classList.add('d-none');
            document.getElementById('varVisible').checked = designData.qr.visible !== false;
            
        } else if (varName.startsWith('firma_')) {
            const memberId = varName.replace('firma_', '');
            const config = designData.firmas[memberId] || { font_size: 11, color: '#047857', visible: true };
            
            document.getElementById('varFontSize').value = config.font_size || 11;
            document.getElementById('varFontColor').value = config.color || '#047857';
            document.getElementById('fontAlignmentCol').classList.add('d-none');
            document.getElementById('fontWeightCol').classList.add('d-none');
            document.getElementById('varVisible').checked = config.visible !== false;
        }
    }

    function deselectAll() {
        activeElement = null;
        document.querySelectorAll('.draggable-var').forEach(el => el.classList.remove('active'));
        document.getElementById('elementSettingsPanel').classList.add('d-none');
    }

    // Actualizar el texto del titulo/cuerpo
    function updateActiveText(val) {
        if (!activeElement) return;
        const varName = activeElement.getAttribute('data-var');
        
        if (varName === 'titulo') {
            activeElement.innerText = val;
            designData.titulo.text = val;
        } else if (varName === 'cuerpo') {
            cuerpoTextoRaw = val;
            renderCuerpoPreview();
        }
    }

    // Actualizar estilos del elemento activo
    function updateActiveStyle() {
        if (!activeElement) return;
        const varName = activeElement.getAttribute('data-var');
        
        const size = document.getElementById('varFontSize').value;
        const color = document.getElementById('varFontColor').value;
        const align = document.getElementById('varFontAlign').value;
        const isBold = document.getElementById('varFontBold').checked;

        // Actualizar visualmente
        activeElement.style.fontSize = `${size}px`;
        activeElement.style.color = color;
        
        if (varName !== 'qr' && !varName.startsWith('firma_')) {
            activeElement.style.fontWeight = isBold ? 'bold' : 'normal';
            activeElement.style.textAlign = align;
        }

        // Guardar configuración
        if (varName === 'titulo') {
            designData.titulo.font_size = parseInt(size);
            designData.titulo.color = color;
            designData.titulo.text_align = align;
            designData.titulo.font_weight = isBold ? 'bold' : 'normal';
        } else if (varName === 'cuerpo') {
            designData.cuerpo.font_size = parseInt(size);
            designData.cuerpo.color = color;
            designData.cuerpo.text_align = align;
            designData.cuerpo.font_weight = isBold ? 'bold' : 'normal';
        } else if (varName.startsWith('firma_')) {
            const memberId = varName.replace('firma_', '');
            if (!designData.firmas[memberId]) {
                designData.firmas[memberId] = { x: 50, y: 80 };
            }
            designData.firmas[memberId].font_size = parseInt(size);
            designData.firmas[memberId].color = color;
        }
    }

    // Mostrar/ocultar variables
    function updateActiveVisibility() {
        if (!activeElement) return;
        const varName = activeElement.getAttribute('data-var');
        const isVisible = document.getElementById('varVisible').checked;

        if (varName === 'titulo') {
            designData.titulo.visible = isVisible;
        } else if (varName === 'cuerpo') {
            designData.cuerpo.visible = isVisible;
        } else if (varName === 'qr') {
            designData.qr.visible = isVisible;
        } else if (varName.startsWith('firma_')) {
            const memberId = varName.replace('firma_', '');
            if (!designData.firmas[memberId]) {
                designData.firmas[memberId] = { x: 50, y: 80 };
            }
            designData.firmas[memberId].visible = isVisible;
            
            // Sincronizar con el checkbox del panel de la izquierda
            document.getElementById(`signatory_${memberId}`).checked = isVisible;
        }

        if (isVisible) {
            activeElement.classList.remove('d-none');
        } else {
            activeElement.classList.add('d-none');
            deselectAll();
        }
    }

    // Toggle de Firmantes por Checkbox
    function toggleSignatoryBlock(memberId, name, role) {
        const checkbox = document.getElementById(`signatory_${memberId}`);
        const block = document.getElementById(`var_firma_${memberId}`);
        
        if (!designData.firmas[memberId]) {
            designData.firmas[memberId] = { x: 25, y: 80, font_size: 11, color: '#047857', visible: false };
        }
        
        designData.firmas[memberId].visible = checkbox.checked;
        
        if (checkbox.checked) {
            block.classList.remove('d-none');
            // Reposicionar
            const newLeft = (designData.firmas[memberId].x / 100) * canvas.clientWidth;
            const newTop = (designData.firmas[memberId].y / 100) * canvas.clientHeight;
            block.style.left = `${newLeft}px`;
            block.style.top = `${newTop}px`;
        } else {
            block.classList.add('d-none');
        }
    }

    // Toggle de QR por Switch de Habilitación
    function toggleQrBlock() {
        const switchBtn = document.getElementById('hasQr');
        const block = document.getElementById('var_qr');
        designData.qr.visible = switchBtn.checked;
        if (switchBtn.checked) {
            block.classList.remove('d-none');
        } else {
            block.classList.add('d-none');
        }
    }

    // Arrastre mouse
    function startDrag(e) {
        e.preventDefault();
        const el = this;
        let startX = e.clientX;
        let startY = e.clientY;
        let startLeft = el.offsetLeft;
        let startTop = el.offsetTop;
        
        function doDrag(ev) {
            let dx = ev.clientX - startX;
            let dy = ev.clientY - startY;
            
            let newLeft = startLeft + dx;
            let newTop = startTop + dy;
            
            let maxLeft = canvas.clientWidth - el.offsetWidth;
            let maxTop = canvas.clientHeight - el.offsetHeight;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            el.style.left = newLeft + 'px';
            el.style.top = newTop + 'px';
            
            savePercentageCoordinates(el, newLeft, newTop);
        }
        
        function stopDrag() {
            document.removeEventListener('mousemove', doDrag);
            document.removeEventListener('mouseup', stopDrag);
        }
        
        document.addEventListener('mousemove', doDrag);
        document.addEventListener('mouseup', stopDrag);
    }

    // Arrastre touch
    function startDragTouch(e) {
        const el = this;
        const touch = e.touches[0];
        let startX = touch.clientX;
        let startY = touch.clientY;
        let startLeft = el.offsetLeft;
        let startTop = el.offsetTop;
        
        function doDragTouch(ev) {
            const t = ev.touches[0];
            let dx = t.clientX - startX;
            let dy = t.clientY - startY;
            
            let newLeft = startLeft + dx;
            let newTop = startTop + dy;
            
            let maxLeft = canvas.clientWidth - el.offsetWidth;
            let maxTop = canvas.clientHeight - el.offsetHeight;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            el.style.left = newLeft + 'px';
            el.style.top = newTop + 'px';
            
            savePercentageCoordinates(el, newLeft, newTop);
        }
        
        function stopDragTouch() {
            el.removeEventListener('touchmove', doDragTouch);
            el.removeEventListener('touchend', stopDragTouch);
        }
        
        el.addEventListener('touchmove', doDragTouch, { passive: false });
        el.addEventListener('touchend', stopDragTouch);
    }

    function savePercentageCoordinates(el, leftPx, topPx) {
        const varName = el.getAttribute('data-var');
        const xPercent = (leftPx / canvas.clientWidth) * 100;
        const yPercent = (topPx / canvas.clientHeight) * 100;
        
        if (varName.startsWith('firma_')) {
            const memberId = varName.replace('firma_', '');
            if (!designData.firmas[memberId]) {
                designData.firmas[memberId] = { font_size: 11, color: '#047857', visible: true };
            }
            designData.firmas[memberId].x = parseFloat(xPercent.toFixed(2));
            designData.firmas[memberId].y = parseFloat(yPercent.toFixed(2));
        } else {
            designData[varName].x = parseFloat(xPercent.toFixed(2));
            designData[varName].y = parseFloat(yPercent.toFixed(2));
        }
    }

    function repositionAllElements() {
        document.querySelectorAll('.draggable-var').forEach(el => {
            const varName = el.getAttribute('data-var');
            let config = null;
            
            if (varName.startsWith('firma_')) {
                const memberId = varName.replace('firma_', '');
                config = designData.firmas[memberId];
            } else {
                config = designData[varName];
            }
            
            if (config) {
                const newLeft = (config.x / 100) * canvas.clientWidth;
                const newTop = (config.y / 100) * canvas.clientHeight;
                
                el.style.left = `${newLeft}px`;
                el.style.top = `${newTop}px`;
            }
        });
    }

    function submitDesignForm() {
        // Enviar el JSON configurado
        document.getElementById('designSettingsInput').value = JSON.stringify(designData);
        document.getElementById('designForm').submit();
    }
</script>
@endsection

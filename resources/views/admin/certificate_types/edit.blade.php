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
    
    /* Dimensiones en proporción A4 Landscape (base 800px) */
    .canvas-a4-landscape { width: 800px; height: 565px; }
    .canvas-a4-portrait { width: 565px; height: 800px; }
    
    /* Dimensiones en proporción A5 Landscape */
    .canvas-a5-landscape { width: 600px; height: 424px; }
    .canvas-a5-portrait { width: 424px; height: 600px; }

    /* Dimensiones proporción Tarjeta 10x15 (2:3) */
    .canvas-10x15-landscape { width: 600px; height: 400px; }
    .canvas-10x15-portrait { width: 400px; height: 600px; }
    
    /* Dimensiones Flyer (1:1 o similar) */
    .canvas-flyer-landscape { width: 600px; height: 600px; }
    .canvas-flyer-portrait { width: 600px; height: 600px; }

    .draggable-var {
        position: absolute;
        padding: 6px 12px;
        background-color: rgba(30, 58, 138, 0.9);
        color: #ffffff;
        border: 1.5px solid #1E3A8A;
        border-radius: 6px;
        cursor: move;
        font-family: 'Arial', sans-serif;
        font-size: 14px;
        white-space: nowrap;
        user-select: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        z-index: 10;
        transition: border-color 0.2s;
    }

    .draggable-var.active {
        border-color: #DC2626 !important;
        background-color: rgba(220, 38, 38, 0.95) !important;
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.4);
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
    
    .draggable-qr.active {
        border-color: #DC2626 !important;
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.4);
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
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-0">Diseñador Visual de Certificados</h1>
            <p class="text-muted">Suba un diseño de fondo y arrastre las variables del socio a las posiciones de impresión.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('admin.certificate_types.export_bulk_view', $certificate_type->id) }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-printer-fill"></i> Exportación Masiva / Grafilab
            </a>
            <a href="{{ route('admin.certificate_types.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.certificate_types.update', $certificate_type) }}" method="POST" enctype="multipart/form-data" id="designForm">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="design_settings" id="designSettingsInput">

        <div class="row">
            <!-- Columna Izquierda: Parámetros del Trámite -->
            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-gear-fill text-primary me-2"></i> Configuración General</h6>
                    </div>
                    <div class="card-body p-4">
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

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Formato y Lienzo de Papel</h6>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Tamaño</label>
                                <select name="page_size" id="pageSizeSelect" class="form-select rounded-3">
                                    <option value="a4" {{ $certificate_type->page_size === 'a4' ? 'selected' : '' }}>A4 (Estándar)</option>
                                    <option value="a5" {{ $certificate_type->page_size === 'a5' ? 'selected' : '' }}>A5 (Mediano)</option>
                                    <option value="10x15" {{ $certificate_type->page_size === '10x15' ? 'selected' : '' }}>Tarjeta (10x15 cm)</option>
                                    <option value="flyer" {{ $certificate_type->page_size === 'flyer' ? 'selected' : '' }}>Flyer (Cuadrado)</option>
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

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Imagen de Fondo (PNG / JPG)</label>
                            <input type="file" name="background_image" id="bgImageFile" class="form-control rounded-3">
                            <small class="text-muted d-block mt-1">Sube el diseño del diploma vacío. Máximo 3MB.</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Reglas y Habilitaciones</h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="requires_clearance" value="1" id="reqClearance" {{ $certificate_type->requires_clearance ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqClearance">Exigir Libre de Deuda</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="requires_no_sanctions" value="1" id="reqEthics" {{ $certificate_type->requires_no_sanctions ? 'checked' : '' }}>
                            <label class="form-check-label small" for="reqEthics">Exigir Habilitación Ética</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="has_qr" value="1" id="hasQr" {{ $certificate_type->has_qr ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-dark" for="hasQr">Incluir QR de Validación</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_single_use" value="1" id="isSingleUse" {{ $certificate_type->is_single_use ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-danger" for="isSingleUse">Habilitar un único uso</label>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ $certificate_type->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-success" for="isActive">Trámite Visible para Socios</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 pt-0 text-center">
                        <button type="button" onclick="submitDesignForm()" class="btn btn-primary rounded-pill px-4 w-100 fw-bold py-2.5">Guardar Diseño y Cambios</button>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Diseñador Interactivo -->
            <div class="col-xl-8 mb-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-richtext-fill text-primary me-2"></i> Lienzo de Configuración Visual</h6>
                        <a href="{{ route('admin.certificate_types.preview', $certificate_type->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold"><i class="bi bi-eye"></i> Vista Previa PDF</a>
                    </div>
                    <div class="card-body p-4 text-center">
                        <!-- Panel de Personalización del Elemento Seleccionado -->
                        <div id="elementSettingsPanel" class="bg-light p-3 rounded-4 mb-4 text-start border border-dashed d-none">
                            <h6 class="small fw-bold text-uppercase text-muted mb-3"><i class="bi bi-sliders me-1"></i> Personalización de Variable: <span id="activeVarName" class="text-primary font-monospace"></span></h6>
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
                                        <label class="form-check-label x-small fw-bold text-uppercase" for="varFontBold">Texto en Negrita</label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="form-check mt-3">
                                        <input class="form-check-input" type="checkbox" id="varVisible" onchange="updateActiveVisibility()">
                                        <label class="form-check-label x-small fw-bold text-uppercase text-danger" for="varVisible">Mostrar en el PDF</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lienzo del Certificado -->
                        <div id="certificateCanvas" class="canvas-a4-landscape">
                            @if($certificate_type->background_path)
                                @php
                                    $defaultSettings = [
                                        'nombre' => ['x' => 50, 'y' => 42, 'font_size' => 24, 'font_weight' => 'bold', 'text_align' => 'center', 'color' => '#1e3a8a', 'visible' => true],
                                        'dni' => ['x' => 33, 'y' => 55, 'font_size' => 14, 'font_weight' => 'normal', 'text_align' => 'left', 'color' => '#334155', 'visible' => true],
                                        'matricula' => ['x' => 67, 'y' => 55, 'font_size' => 14, 'font_weight' => 'bold', 'text_align' => 'left', 'color' => '#1e3a8a', 'visible' => true],
                                        'fecha_emision' => ['x' => 50, 'y' => 70, 'font_size' => 12, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#64748b', 'visible' => true],
                                        'valido_hasta' => ['x' => 50, 'y' => 76, 'font_size' => 12, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#64748b', 'visible' => true],
                                        'qr' => ['x' => 84, 'y' => 80, 'width' => 80, 'height' => 80, 'visible' => true]
                                    ];
                                    
                                    $settings = array_merge($defaultSettings, $certificate_type->design_settings ?? []);
                                @endphp

                                <!-- Variables arrastrables -->
                                <div id="var_nombre" class="draggable-var {{ ($settings['nombre']['visible'] ?? true) ? '' : 'd-none' }}" data-var="nombre" style="left: {{ $settings['nombre']['x'] }}%; top: {{ $settings['nombre']['y'] }}%; font-size: {{ $settings['nombre']['font_size'] }}px; font-weight: {{ $settings['nombre']['font_weight'] }}; color: {{ $settings['nombre']['color'] }};">
                                    [Nombre y Apellido del Matriculado]
                                </div>
                                
                                <div id="var_dni" class="draggable-var {{ ($settings['dni']['visible'] ?? true) ? '' : 'd-none' }}" data-var="dni" style="left: {{ $settings['dni']['x'] }}%; top: {{ $settings['dni']['y'] }}%; font-size: {{ $settings['dni']['font_size'] }}px; font-weight: {{ $settings['dni']['font_weight'] }}; color: {{ $settings['dni']['color'] }};">
                                    DNI: [Número de Documento]
                                </div>
                                
                                <div id="var_matricula" class="draggable-var {{ ($settings['matricula']['visible'] ?? true) ? '' : 'd-none' }}" data-var="matricula" style="left: {{ $settings['matricula']['x'] }}%; top: {{ $settings['matricula']['y'] }}%; font-size: {{ $settings['matricula']['font_size'] }}px; font-weight: {{ $settings['matricula']['font_weight'] }}; color: {{ $settings['matricula']['color'] }};">
                                    M.P. Nº [Número Matrícula]
                                </div>
                                
                                <div id="var_fecha_emision" class="draggable-var {{ ($settings['fecha_emision']['visible'] ?? true) ? '' : 'd-none' }}" data-var="fecha_emision" style="left: {{ $settings['fecha_emision']['x'] }}%; top: {{ $settings['fecha_emision']['y'] }}%; font-size: {{ $settings['fecha_emision']['font_size'] }}px; font-weight: {{ $settings['fecha_emision']['font_weight'] }}; color: {{ $settings['fecha_emision']['color'] }};">
                                    Emitido el: [Fecha Emisión]
                                </div>
                                
                                <div id="var_valido_hasta" class="draggable-var {{ ($settings['valido_hasta']['visible'] ?? true) ? '' : 'd-none' }}" data-var="valido_hasta" style="left: {{ $settings['valido_hasta']['x'] }}%; top: {{ $settings['valido_hasta']['y'] }}%; font-size: {{ $settings['valido_hasta']['font_size'] }}px; font-weight: {{ $settings['valido_hasta']['font_weight'] }}; color: {{ $settings['valido_hasta']['color'] }};">
                                    Válido hasta: [Fecha Vencimiento]
                                </div>
                                
                                <div id="var_qr" class="draggable-var draggable-qr {{ ($settings['qr']['visible'] ?? true) ? '' : 'd-none' }}" data-var="qr" style="left: {{ $settings['qr']['x'] }}%; top: {{ $settings['qr']['y'] }}%; width: {{ $settings['qr']['width'] ?? 80 }}px; height: {{ $settings['qr']['height'] ?? 80 }}px;">
                                    Código QR<br>Validación
                                </div>
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
    // Variables para control del lienzo
    let activeElement = null;
    const canvas = document.getElementById('certificateCanvas');
    
    // Configuración inicial de fondo y canvas
    @if($certificate_type->background_path)
        canvas.style.backgroundImage = "url('{{ asset($certificate_type->background_path) }}')";
    @endif

    // Mapeo inicial de la base de datos
    let designData = {
        nombre: {!! json_encode($settings['nombre'] ?? ['x'=>50, 'y'=>42, 'font_size'=>24, 'font_weight'=>'bold', 'text_align'=>'center', 'color'=>'#1e3a8a', 'visible'=>true]) !!},
        dni: {!! json_encode($settings['dni'] ?? ['x'=>33, 'y'=>55, 'font_size'=>14, 'font_weight'=>'normal', 'text_align'=>'left', 'color'=>'#334155', 'visible'=>true]) !!},
        matricula: {!! json_encode($settings['matricula'] ?? ['x'=>67, 'y'=>55, 'font_size'=>14, 'font_weight'=>'bold', 'text_align'=>'left', 'color'=>'#1e3a8a', 'visible'=>true]) !!},
        fecha_emision: {!! json_encode($settings['fecha_emision'] ?? ['x'=>50, 'y'=>70, 'font_size'=>12, 'font_weight'=>'normal', 'text_align'=>'center', 'color'=>'#64748b', 'visible'=>true]) !!},
        valido_hasta: {!! json_encode($settings['valido_hasta'] ?? ['x'=>50, 'y'=>76, 'font_size'=>12, 'font_weight'=>'normal', 'text_align'=>'center', 'color'=>'#64748b', 'visible'=>true]) !!},
        qr: {!! json_encode($settings['qr'] ?? ['x'=>84, 'y'=>80, 'width'=>80, 'height'=>80, 'visible'=>true]) !!}
    };

    // Ajustar dimensiones del Canvas al cambiar formato y orientación
    function updateCanvasSize() {
        const size = document.getElementById('pageSizeSelect').value;
        const orientation = document.getElementById('pageOrientationSelect').value;
        
        // Remover clases actuales
        canvas.className = '';
        
        // Aplicar clase correspondiente
        canvas.classList.add(`canvas-${size}-${orientation}`);
        
        // Reposicionar elementos según el nuevo tamaño
        repositionAllElements();
    }

    document.getElementById('pageSizeSelect').addEventListener('change', updateCanvasSize);
    document.getElementById('pageOrientationSelect').addEventListener('change', updateCanvasSize);

    // Al cargar la página, ajustar el tamaño
    window.addEventListener('DOMContentLoaded', () => {
        updateCanvasSize();
        initDragAndDrop();
    });

    // Iniciar arrastre
    function initDragAndDrop() {
        let draggables = document.querySelectorAll('.draggable-var');
        draggables.forEach(el => {
            el.addEventListener('mousedown', startDrag);
            el.addEventListener('click', function(e) {
                e.stopPropagation();
                selectElement(this);
            });
            // Soporte para touch en móviles
            el.addEventListener('touchstart', startDragTouch, { passive: false });
        });

        // Click en canvas deselecciona
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
        
        // Cargar controles con el estilo del elemento
        const config = designData[varName];
        document.getElementById('varVisible').checked = config.visible !== false;
        
        if (varName === 'qr') {
            document.getElementById('fontSizeCol').classList.add('d-none');
            document.getElementById('fontColorCol').classList.add('d-none');
            document.getElementById('fontAlignmentCol').classList.add('d-none');
            document.getElementById('fontWeightCol').classList.add('d-none');
        } else {
            document.getElementById('fontSizeCol').classList.remove('d-none');
            document.getElementById('fontColorCol').classList.remove('d-none');
            document.getElementById('fontAlignmentCol').classList.remove('d-none');
            document.getElementById('fontWeightCol').classList.remove('d-none');
            
            document.getElementById('varFontSize').value = config.font_size || 14;
            document.getElementById('varFontColor').value = config.color || '#000000';
            document.getElementById('varFontAlign').value = config.text_align || 'center';
            document.getElementById('varFontBold').checked = config.font_weight === 'bold';
        }
    }

    function deselectAll() {
        activeElement = null;
        document.querySelectorAll('.draggable-var').forEach(el => el.classList.remove('active'));
        document.getElementById('elementSettingsPanel').classList.add('d-none');
    }

    function updateActiveStyle() {
        if (!activeElement) return;
        const varName = activeElement.getAttribute('data-var');
        if (varName === 'qr') return;

        const size = document.getElementById('varFontSize').value;
        const color = document.getElementById('varFontColor').value;
        const align = document.getElementById('varFontAlign').value;
        const isBold = document.getElementById('varFontBold').checked;

        // Actualizar visualmente
        activeElement.style.fontSize = `${size}px`;
        activeElement.style.color = color;
        activeElement.style.fontWeight = isBold ? 'bold' : 'normal';

        // Actualizar en el objeto de datos
        designData[varName].font_size = parseInt(size);
        designData[varName].color = color;
        designData[varName].text_align = align;
        designData[varName].font_weight = isBold ? 'bold' : 'normal';
    }

    function updateActiveVisibility() {
        if (!activeElement) return;
        const varName = activeElement.getAttribute('data-var');
        const isVisible = document.getElementById('varVisible').checked;

        designData[varName].visible = isVisible;
        
        if (isVisible) {
            activeElement.classList.remove('d-none');
        } else {
            activeElement.classList.add('d-none');
            deselectAll();
        }
    }

    // Funciones de Arrastre Nativas
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
            
            // Limitar dentro del lienzo
            let maxLeft = canvas.clientWidth - el.offsetWidth;
            let maxTop = canvas.clientHeight - el.offsetHeight;
            
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            el.style.left = newLeft + 'px';
            el.style.top = newTop + 'px';
            
            // Guardar porcentajes
            savePercentageCoordinates(el, newLeft, newTop);
        }
        
        function stopDrag() {
            document.removeEventListener('mousemove', doDrag);
            document.removeEventListener('mouseup', stopDrag);
        }
        
        document.addEventListener('mousemove', doDrag);
        document.addEventListener('mouseup', stopDrag);
    }

    // Touch support para tablets
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
        
        // Convertir coordenadas a porcentajes
        const xPercent = (leftPx / canvas.clientWidth) * 100;
        const yPercent = (topPx / canvas.clientHeight) * 100;
        
        designData[varName].x = parseFloat(xPercent.toFixed(2));
        designData[varName].y = parseFloat(yPercent.toFixed(2));
    }

    // Reposicionar basándose en los porcentajes guardados (cuando cambia el tamaño del lienzo)
    function repositionAllElements() {
        document.querySelectorAll('.draggable-var').forEach(el => {
            const varName = el.getAttribute('data-var');
            const config = designData[varName];
            
            if (config) {
                const newLeft = (config.x / 100) * canvas.clientWidth;
                const newTop = (config.y / 100) * canvas.clientHeight;
                
                el.style.left = `${newLeft}px`;
                el.style.top = `${newTop}px`;
            }
        });
    }

    // Guardar el formulario y codificar el JSON
    function submitDesignForm() {
        // Asignar el JSON al input oculto antes de enviar
        document.getElementById('designSettingsInput').value = JSON.stringify(designData);
        document.getElementById('designForm').submit();
    }
</script>
@endsection

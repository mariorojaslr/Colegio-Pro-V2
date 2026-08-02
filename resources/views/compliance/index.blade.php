@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    {{-- Header de Sección --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Mi <span class="text-gradient-gold">Legajo Digital</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5 text-white-50">Mantenga su documentación al día para asegurar su habilitación profesional.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="bg-white bg-opacity-10 p-4 rounded-4 backdrop-blur border border-white border-opacity-10">
                            @php
                                $total = $mandatoryReqsCount;
                                $uploaded = $validMandatoryDocsCount;
                                $percent = $total > 0 ? ($uploaded / $total) * 100 : 100;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-white-50 small fw-bold uppercase">Progreso Documental ({{ $uploaded }}/{{ $total }})</span>
                                <span class="text-warning fw-bold">{{ round($percent) }}%</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px; background: rgba(255,255,255,0.1)">
                                <div class="progress-bar bg-warning shadow-accent" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Listado de Requisitos --}}
    <div class="row g-4">
        @foreach($requirements as $requirement)
            <div class="col-md-6">
                @php
                    $doc = $myDocuments->get($requirement->id);
                    $status = $doc ? $doc->status : 'missing';
                    $isExpired = false;
                    
                    if ($status === 'approved' && $doc->expires_at && $doc->expires_at < now()) {
                        $status = 'expired';
                        $isExpired = true;
                    }

                    $statusConfig = [
                        'missing' => ['label' => 'Sin Cargar', 'class' => 'bg-secondary-subtle text-muted', 'icon' => 'bi-file-earmark-plus'],
                        'pending' => ['label' => 'En Revisión', 'class' => 'bg-warning-subtle text-warning', 'icon' => 'bi-hourglass-split'],
                        'approved' => ['label' => 'Aprobado y Vigente', 'class' => 'bg-success-subtle text-success', 'icon' => 'bi-patch-check-fill'],
                        'rejected' => ['label' => 'Rechazado', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-exclamation-octagon-fill'],
                        'expired' => ['label' => 'Vencido - Renovar', 'class' => 'bg-danger-subtle text-danger', 'icon' => 'bi-calendar-x-fill'],
                    ];
                @endphp
                <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100" style="border-radius: 30px">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-3 rounded-4 me-3">
                                <i class="bi {{ $statusConfig[$status]['icon'] }} fs-3 {{ $status == 'missing' ? 'text-muted' : '' }}"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $requirement->name }}</h5>
                                <span class="badge {{ $statusConfig[$status]['class'] }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ $statusConfig[$status]['label'] }}
                                </span>
                                @if($requirement->is_mandatory)
                                    <span class="ms-2 text-danger small fw-bold">* Obligatorio</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($status == 'rejected' && $doc->admin_notes)
                        <div class="alert alert-danger border-0 rounded-4 mb-4 small fw-medium">
                            <i class="bi bi-info-circle-fill me-2"></i> <strong>Motivo del rechazo:</strong> {{ $doc->admin_notes }}
                        </div>
                    @endif

                    <div class="bg-light p-4 rounded-4 mb-4">
                        <p class="small text-muted mb-0">
                            @if($requirement->expiration_months)
                                Vencimiento: Cada {{ $requirement->expiration_months }} meses.
                            @else
                                Documento de validez permanente.
                            @endif
                        </p>
                        @if($doc && $doc->expires_at)
                            <p class="small fw-bold text-dark mt-2 mb-0">
                                Fecha de Vencimiento: <span class="{{ $isExpired ? 'text-danger' : 'text-success' }}">{{ \Carbon\Carbon::parse($doc->expires_at)->format('d/m/Y') }}</span>
                            </p>
                        @endif
                    </div>

                    @if($status != 'approved')
                        @if($requirement->delivery_method === 'physical' || $requirement->delivery_method === 'both')
                            <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 p-3 text-center mb-3">
                                <span class="text-warning fw-bold small"><i class="bi bi-person-badge me-2"></i> ENTREGA PRESENCIAL</span>
                                <div class="xx-small text-muted mt-1 uppercase">Debe presentar este requisito físicamente en la institución.</div>
                            </div>
                        @endif

                        @if($requirement->delivery_method === 'digital' || $requirement->delivery_method === 'both')
                            <form action="{{ route('compliance.upload', $requirement) }}" id="form-{{ $requirement->id }}" class="form-compliance" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="cropped_image" class="cropped-image-input">
                                
                                @if(Str::contains(strtolower($requirement->name), 'dni') || Str::contains(strtolower($requirement->name), 'documento'))
                                    <input type="hidden" name="cropped_image_back" class="cropped-image-back-input">
                                    <input type="file" name="document" id="file-front-{{ $requirement->id }}" class="visually-hidden file-input-front" onchange="previewSelection(this, 'badge-front-{{ $requirement->id }}')">
                                    <input type="file" id="camera-front-{{ $requirement->id }}" class="visually-hidden file-input-front" accept="image/*" capture="environment" onchange="previewSelection(this, 'badge-front-{{ $requirement->id }}')">
                                    
                                    <input type="file" name="document_back" id="file-back-{{ $requirement->id }}" class="visually-hidden file-input-back" onchange="previewSelection(this, 'badge-back-{{ $requirement->id }}')">
                                    <input type="file" id="camera-back-{{ $requirement->id }}" class="visually-hidden file-input-back" accept="image/*" capture="environment" onchange="previewSelection(this, 'badge-back-{{ $requirement->id }}')">
                                    
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold text-muted">Frente</span>
                                            <span class="badge bg-success visually-hidden" id="badge-front-{{ $requirement->id }}"><i class="bi bi-check"></i> Listo</span>
                                        </div>
                                        <div class="col-6">
                                            <label for="file-front-{{ $requirement->id }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold small m-0 d-block cursor-pointer">
                                                <i class="bi bi-folder2-open me-2"></i> ARCHIVO
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label for="camera-front-{{ $requirement->id }}" class="btn btn-dark w-100 rounded-pill py-2 fw-bold small shadow-sm m-0 d-block cursor-pointer">
                                                <i class="bi bi-camera me-2"></i> ESCANEAR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold text-muted">Dorso</span>
                                            <span class="badge bg-success visually-hidden" id="badge-back-{{ $requirement->id }}"><i class="bi bi-check"></i> Listo</span>
                                        </div>
                                        <div class="col-6">
                                            <label for="file-back-{{ $requirement->id }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold small m-0 d-block cursor-pointer">
                                                <i class="bi bi-folder2-open me-2"></i> ARCHIVO
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label for="camera-back-{{ $requirement->id }}" class="btn btn-dark w-100 rounded-pill py-2 fw-bold small shadow-sm m-0 d-block cursor-pointer">
                                                <i class="bi bi-camera me-2"></i> ESCANEAR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold btn-process-upload">Siguiente Paso <i class="bi bi-arrow-right"></i></button>
                                    </div>
                                @else
                                    <input type="file" name="document" id="file-{{ $requirement->id }}" class="visually-hidden file-input-front" onchange="previewSelection(this, 'badge-{{ $requirement->id }}')">
                                    <input type="file" id="camera-{{ $requirement->id }}" class="visually-hidden file-input-front" accept="image/*" capture="environment" onchange="previewSelection(this, 'badge-{{ $requirement->id }}')">
                                    
                                    <div class="row g-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center mb-1">
                                            <span class="small fw-bold text-muted d-none">Documento</span>
                                            <span class="badge bg-success visually-hidden" id="badge-{{ $requirement->id }}"><i class="bi bi-check"></i> Seleccionado</span>
                                        </div>
                                        <div class="col-6">
                                            <label for="file-{{ $requirement->id }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold small m-0 d-block cursor-pointer">
                                                <i class="bi bi-folder2-open me-2"></i> ARCHIVO
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label for="camera-{{ $requirement->id }}" class="btn btn-dark w-100 rounded-pill py-2 fw-bold small shadow-sm m-0 d-block cursor-pointer">
                                                <i class="bi bi-camera me-2"></i> ESCANEAR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold btn-process-upload" style="display: none;" id="btn-submit-{{ $requirement->id }}">Siguiente Paso <i class="bi bi-arrow-right"></i></button>
                                    </div>
                                @endif
                                
                                <div class="mt-3 text-center">
                                    <span class="xx-small text-muted fw-bold ls-1 uppercase">SOPORTA: PDF, EXCEL, WORD, IMÁGENES (MÁX. 10MB)</span>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="bg-success bg-opacity-5 border border-success border-opacity-10 rounded-4 p-3 text-center">
                             <span class="text-success fw-bold small"><i class="bi bi-check2-all me-2"></i> DOCUMENTO VERIFICADO</span>
                             <div class="xx-small text-success opacity-75 mt-1 uppercase">HABILITADO EN LEGAJO</div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .backdrop-blur { backdrop-filter: blur(10px); }
    .text-gradient-gold {
        background: linear-gradient(135deg, #fde68a, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .shadow-text { text-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .shadow-accent { box-shadow: 0 0 20px rgba(234, 179, 8, 0.4); }
    .card-prestige { transition: transform 0.3s ease; }
    .card-prestige:hover { transform: translateY(-5px); }
    .xx-small { font-size: 10px; }
    .ls-1 { letter-spacing: 1px; }
</style>

<!-- Modal para Cropper.js -->
<div class="modal fade" id="cropperModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Ajustar / Recortar Documento</h5>
                <button type="button" class="btn-close cancel-crop"></button>
            </div>
            <div class="modal-body text-center p-4 d-flex flex-column" style="min-height: 75vh;">
                <div class="alert alert-info bg-info bg-opacity-10 text-info border-0 small mb-3">
                    <i class="bi bi-info-circle me-1"></i> Arrastra las esquinas para encuadrar correctamente el documento.
                </div>
                <div class="flex-grow-1 bg-dark rounded-3 d-flex align-items-center justify-content-center" style="overflow: hidden; width: 100%; min-height: 480px; height: 100%;">
                    <img id="imageToCrop" style="max-width: 100%; max-height: 100%; display: block;">
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill me-2 btn-rotate-left"><i class="bi bi-arrow-counterclockwise"></i> Rotar Izq</button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill btn-rotate-right"><i class="bi bi-arrow-clockwise"></i> Rotar Der</button>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold cancel-crop">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold confirm-crop">Confirmar Recorte</button>
            </div>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.previewSelection = function(input, badgeId) {
        const badge = document.getElementById(badgeId);
        if (input.files && input.files.length > 0) {
            if (badge) badge.classList.remove('visually-hidden');
            
            // Si es un documento simple, mostrar el boton de submit
            const form = input.closest('form');
            const submitBtn = form.querySelector('.btn-process-upload');
            if(submitBtn && submitBtn.style.display === 'none') {
                submitBtn.style.display = 'block';
            }
            
            // Deseleccionar el otro input gemelo (si eligió file, limpiar camera, y viceversa)
            const inputsOfSameClass = form.querySelectorAll('.' + input.className.split(' ').join('.'));
            inputsOfSameClass.forEach(otherInput => {
                if (otherInput !== input) {
                    otherInput.value = ''; // clear sibling
                }
            });
        } else {
            if (badge) badge.classList.add('visually-hidden');
        }
    };

    let cropper = null;
    let currentInput = null;
    let currentForm = null;
    let isFront = true;
    let nextCallback = null;

    const cropperModalEl = document.getElementById('cropperModal');
    let cropperModal = null;
    if(cropperModalEl) {
        cropperModal = new bootstrap.Modal(cropperModalEl);
    }
    const imageToCrop = document.getElementById('imageToCrop');

    document.querySelectorAll('.btn-process-upload').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            // Obtener el input seleccionado real (el file o el camera)
            const frontInputs = form.querySelectorAll('.file-input-front');
            let frontInput = null;
            frontInputs.forEach(i => { if(i.files.length > 0) frontInput = i; });
            
            const backInputs = form.querySelectorAll('.file-input-back');
            let backInput = null;
            backInputs.forEach(i => { if(i.files.length > 0) backInput = i; });

            if (!frontInput && !form.querySelector('.cropped-image-input').value) {
                alert('Debes seleccionar al menos el documento principal (Frente).');
                return;
            }
            
            const backInputNeeded = form.querySelector('.cropped-image-back-input');
            if (backInputNeeded && !backInput && !backInputNeeded.value) {
                alert('Debes seleccionar también el dorso del documento.');
                return;
            }

            processImage(frontInput, form, true, function() {
                if (backInput) {
                    processImage(backInput, form, false, function() {
                        submitFormSafe(form);
                    });
                } else {
                    submitFormSafe(form);
                }
            });
        });
    });

    function submitFormSafe(form) {
        // Asegurarnos de que si hay duplicados de input (file y camera), solo se mande uno
        ['.file-input-front', '.file-input-back'].forEach(cls => {
            const inputs = form.querySelectorAll(cls);
            let hasNamed = false;
            inputs.forEach(i => {
                if (i.files.length > 0 && !hasNamed) {
                    hasNamed = true;
                } else {
                    i.removeAttribute('name');
                }
            });
        });
        form.submit();
    }

    function processImage(input, form, front, onComplete) {
        if (!input || !input.files || !input.files.length) {
            onComplete();
            return;
        }

        const file = input.files[0];
        if (!file.type.startsWith('image/')) {
            onComplete();
            return;
        }

        currentInput = input;
        currentForm = form;
        isFront = front;
        nextCallback = onComplete;

        const reader = new FileReader();
        reader.onload = function(e) {
            imageToCrop.src = e.target.result;
            
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            const initCropper = function() {
                cropper = new Cropper(imageToCrop, {
                    viewMode: 1,
                    autoCropArea: 0.9,
                    background: false,
                    responsive: true,
                    restore: false,
                    checkCrossOrigin: false,
                    modal: true,
                    guides: true,
                    center: true,
                    highlight: true,
                    cropBoxMovable: true,
                    cropBoxResizable: true
                });
                cropperModalEl.removeEventListener('shown.bs.modal', initCropper);
            };
            cropperModalEl.addEventListener('shown.bs.modal', initCropper);
            cropperModal.show();
        };
        reader.readAsDataURL(file);
    }

    const confirmBtn = document.querySelector('.confirm-crop');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({ maxWidth: 1600, maxHeight: 1600 });
            const base64 = canvas.toDataURL('image/jpeg', 0.85);
            
            if (isFront) {
                currentForm.querySelector('.cropped-image-input').value = base64;
            } else {
                currentForm.querySelector('.cropped-image-back-input').value = base64;
            }
            currentInput.removeAttribute('name');
            currentInput.value = '';
            
            cropperModal.hide();
            
            setTimeout(() => {
                if(nextCallback) nextCallback();
            }, 300);
        };
    }

    document.querySelectorAll('.cancel-crop').forEach(btn => {
        btn.addEventListener('click', function() {
            if(cropperModal) cropperModal.hide();
        });
    });

    const rotateLeft = document.querySelector('.btn-rotate-left');
    if(rotateLeft) rotateLeft.addEventListener('click', () => { if(cropper) cropper.rotate(-90); });
    const rotateRight = document.querySelector('.btn-rotate-right');
    if(rotateRight) rotateRight.addEventListener('click', () => { if(cropper) cropper.rotate(90); });
});
</script>
@endsection

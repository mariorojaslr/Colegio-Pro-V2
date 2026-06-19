@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row mb-5 align-items-center">
        <div class="col">
            <h1 class="display-5 fw-bold mb-1" style="font-family: 'Outfit', sans-serif">Importación <span class="text-primary">Masiva</span></h1>
            <p class="text-muted fs-5">Cargue su padrón de colegiados desde archivos externos de forma segura.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <a href="{{ route('collegiates.import.template') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-cloud-download me-2"></i> Descargar Plantilla CSV
            </a>
            <a href="{{ route('collegiates.index') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Volver al Padrón
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card-prestige bg-white border-0 shadow-2xl p-5" style="border-radius: 40px">
                <form action="{{ route('collegiates.import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-center mb-5">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px">
                            <i class="bi bi-file-earmark-arrow-up text-primary display-4"></i>
                        </div>
                        <h4 class="fw-bold">Subir Archivo de Colegiados</h4>
                        <p class="text-muted">El sistema procesará automáticamente los registros y los vinculará a su institución.</p>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger rounded-4 border-0 mb-4 p-4 small">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-5">
                        <label class="form-label fw-bold text-muted small uppercase mb-3 ls-1">Seleccionar Archivo (CSV)</label>
                        <div class="border-dashed-2 rounded-4 p-5 text-center bg-light position-relative" id="drop-zone">
                            <input type="file" name="file" class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer" id="file-input" required>
                            <i class="bi bi-cloud-upload fs-1 text-primary opacity-50 mb-3 d-block" id="file-icon"></i>
                            <div class="fw-bold text-muted" id="file-name-display">Haga clic o arrastre el archivo aquí</div>
                            <div class="small text-muted mt-2" id="file-size-display">Tamaño máximo: 10MB</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-prestige w-100 rounded-pill py-3 fw-bold shadow-lg fs-5">
                        Procesar Importación <i class="bi bi-gear-wide-connected ms-2"></i>
                    </button>
                </form>

                <div class="mt-5 p-4 bg-light rounded-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i> Formato sugerido para el CSV:</h6>
                    <code class="small d-block text-primary bg-white p-3 rounded-3 border">
                        Matricula, Nombre, Apellido, Email, DNI<br>
                        MAT-101, Juan, Perez, juan@email.com, 12345678<br>
                        MAT-102, Maria, Gomez, maria@email.com, 87654321
                    </code>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card-prestige p-5 text-white h-100" style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: 40px">
                <h4 class="fw-bold mb-4">Instrucciones de <span class="text-primary">Elite</span></h4>
                <div class="d-grid gap-4 opacity-75">
                    <div class="d-flex">
                        <div class="me-3"><i class="bi bi-1-circle fs-4 text-primary"></i></div>
                        <div>Prepare su archivo Excel y expórtelo como formato <strong>CSV delimitado por comas</strong>.</div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3"><i class="bi bi-2-circle fs-4 text-primary"></i></div>
                        <div>Asegúrese de que la primera fila contenga los títulos de las columnas (el sistema los ignorará).</div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3"><i class="bi bi-3-circle fs-4 text-primary"></i></div>
                        <div>Los números de matrícula deben ser únicos; si existe un registro previo con la misma matrícula, el sistema actualizará sus datos.</div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3"><i class="bi bi-4-circle fs-4 text-primary"></i></div>
                        <div>La duración del proceso dependerá de la cantidad de registros; el sistema le notificará al finalizar.</div>
                    </div>
                </div>
                
                <div class="mt-auto pt-5">
                    <div class="bg-white bg-opacity-10 p-4 rounded-4 border border-white border-opacity-10">
                        <div class="small fw-bold mb-2">PRO TIP:</div>
                        <div class="small opacity-75 italic">Si tiene problemas con archivos muy grandes (más de 5.000 registros), divídalos en partes menores para un procesamiento óptimo.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed-2 { border: 2px dashed #cbd5e1; }
    .cursor-pointer { cursor: pointer; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file-input');
    const fileNameDisplay = document.getElementById('file-name-display');
    const fileSizeDisplay = document.getElementById('file-size-display');
    const fileIcon = document.getElementById('file-icon');
    const dropZone = document.getElementById('drop-zone');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileNameDisplay.innerHTML = `<span class="text-primary fw-bold">${file.name}</span>`;
            fileSizeDisplay.textContent = 'Archivo seleccionado y listo para subir.';
            fileIcon.classList.remove('bi-cloud-upload');
            fileIcon.classList.add('bi-file-earmark-check-fill');
            fileIcon.classList.remove('opacity-50');
            dropZone.classList.add('border-primary', 'bg-primary-subtle');
        } else {
            fileNameDisplay.textContent = 'Haga clic o arrastre el archivo aquí';
            fileSizeDisplay.textContent = 'Tamaño máximo: 10MB';
            fileIcon.classList.add('bi-cloud-upload');
            fileIcon.classList.remove('bi-file-earmark-check-fill');
            fileIcon.classList.add('opacity-50');
            dropZone.classList.remove('border-primary', 'bg-primary-subtle');
        }
    });
});
</script>
@endsection

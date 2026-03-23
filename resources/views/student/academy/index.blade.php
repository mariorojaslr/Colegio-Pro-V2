@extends('layouts.main')

@section('title', 'Academia | Colegio-Pro')

@push('styles')
<style>
    .ls-1 { letter-spacing: 0.5px; }
    .ls-2 { letter-spacing: 2px; }
    .fw-black { font-weight: 900; }
    .x-small { font-size: 10px; }
    .xx-small { font-size: 8px; }

    /* ACADEMY HERO SLIM (Negro a la izquierda, imagen natural a la derecha) */
    .academy-hero {
        background: linear-gradient(90deg, #0f172a 0%, #0f172a 10%, rgba(15, 23, 42, 0.4) 40%, transparent 85%), url('https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center right;
        border-radius: 15px;
        min-height: 130px; 
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 25px; 
    }

    /* COURSE CARD ROLLS-ROYCE SLIM */
    .course-poster-wrapper {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        background: #f1f5f9;
        aspect-ratio: 2/3;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .course-poster-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    .course-poster-inner {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-color: #e2e8f0;
    }

    .course-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40%; 
        background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0.3) 70%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 12px;
        color: white;
    }

    .course-category-pill {
        background: #2563eb !important;
        color: white !important;
        font-size: 7px; 
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 50px;
        width: fit-content;
        margin-bottom: 4px;
        letter-spacing: 0.5px;
    }

    .course-title-card {
        font-size: 0.85rem; 
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 1px;
        font-family: 'Outfit', sans-serif;
    }

    .course-date-card {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
    }

    /* FILTER BAR */
    .filter-pill {
        cursor: pointer;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        padding: 6px 16px;
        border-radius: 50px;
        background: #F1F5F9;
        color: #475569;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .filter-pill.active {
        background: #0F172A;
        color: white;
    }

    /* MODAL SALES STYLE */
    .modal-sales-header {
        height: 220px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .modal-sales-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(0deg, #0f172a 0%, transparent 100%);
    }

    .feature-item {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 10px;
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3 min-vh-100 bg-light-subtle">
    
    {{-- Hero Sector --}}
    <div class="academy-hero p-4 px-5 shadow-sm">
        <div class="animate__animated animate__fadeIn">
            <h6 class="text-white-50 xx-small fw-bold ls-2 uppercase mb-1">ESCUELA VIRTUAL</h6>
            <h1 class="h4 fw-black text-white mb-0" style="font-family: 'Outfit', sans-serif;">Academia Pro</h1>
            <p class="text-white-50 xx-small fw-light mb-3">Excelencia académica para la judicatura moderna.</p>
            
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-black xx-small shadow-sm">
                    VER TEMARIO
                </a>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="mb-4 d-flex flex-wrap gap-2 justify-content-center">
        <span class="filter-pill active">Todos</span>
        <span class="filter-pill">Gestión Judicial</span>
        <span class="filter-pill">Tecnología</span>
        <span class="filter-pill">Mediación</span>
        <span class="filter-pill">Especialización</span>
        <span class="filter-pill">Internacional</span>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 mb-5">
        @php
            $cursos = [
                ['title' => 'Gestión Judicial 4.0', 'date' => '15 May', 'duration' => '4 Semanas', 'cat' => 'Gestión', 'price' => '$25.000', 'lecturer' => 'Dr. Alberto Ruiz', 'description' => 'Optimice el flujo de expedientes con herramientas modernas.', 'benefit' => 'Reducción de tiempos procesales.', 'img' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'I.A. en Tribunales', 'date' => '22 May', 'duration' => '6 Semanas', 'cat' => 'Tecnología', 'price' => '$32.000', 'lecturer' => 'Mag. Juan Pérez', 'description' => 'Implementación de asistencia inteligente judicial.', 'benefit' => 'Dominio de Prompt Engineering.', 'img' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1932&auto=format&fit=crop'],
                ['title' => 'Ciberseguridad Legal', 'date' => '02 Jun', 'duration' => '3 Semanas', 'cat' => 'Seguridad', 'price' => '$18.500', 'lecturer' => 'Lic. Pedro Gómez', 'description' => 'Protección de datos y normativas de seguridad digital.', 'benefit' => 'Kit de herramientas digitales.', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Marketing Jurídico', 'date' => '10 Jun', 'duration' => '8 Semanas', 'cat' => 'Negocios', 'price' => '$12.000', 'lecturer' => 'Lic. Elena Blanco', 'description' => 'Posicionamiento de marca y atracción de clientes.', 'benefit' => 'Plan de marketing personalizado.', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2026&auto=format&fit=crop'],
                ['title' => 'Mediación 2024', 'date' => '18 Jun', 'duration' => '5 Semanas', 'cat' => 'Mediación', 'price' => '$21.000', 'lecturer' => 'Escribano Carlos Paz', 'description' => 'Técnicas de resolución alterna de conflictos.', 'benefit' => 'Habilitación para mediación remota.', 'img' => 'https://images.unsplash.com/photo-1521791136364-758a64045057?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Blockchain Público', 'date' => '25 Jun', 'duration' => '4 Semanas', 'cat' => 'Tecnología', 'price' => '$28.000', 'lecturer' => 'Dr. Jorge Lin', 'description' => 'Registro distribuido en la fe pública y registros.', 'benefit' => 'Certificado de innovación digital.', 'img' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2032&auto=format&fit=crop'],
                ['title' => 'Peritaje Digital', 'date' => '05 Jul', 'duration' => '4 Semanas', 'cat' => 'Especialización', 'price' => '$19.000', 'lecturer' => 'Mag. Lucia Fern', 'description' => 'Recolección de evidencia digital en procesos legales.', 'benefit' => 'Validación forense avanzada.', 'img' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Ética en Algoritmos', 'date' => '12 Jul', 'duration' => '3 Semanas', 'cat' => 'Tecnología', 'price' => '$15.000', 'lecturer' => 'Dr. Mario San', 'description' => 'Desafíos éticos de la I.A. en la justicia civil.', 'benefit' => 'Pensamiento crítico jurídico.', 'img' => 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Oratoria Forense', 'date' => '20 Jul', 'duration' => '5 Semanas', 'cat' => 'Especialización', 'price' => '$16.500', 'lecturer' => 'Dra. Ana López', 'description' => 'Argumentación efectiva en juicios orales modernos.', 'benefit' => 'Técnicas de persuasión jurídica.', 'img' => 'https://images.unsplash.com/photo-1475721027187-40247339a3f9?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'Derecho Ambiental', 'date' => '02 Ago', 'duration' => '8 Semanas', 'cat' => 'Especialización', 'price' => '$27.500', 'lecturer' => 'Lic. Clara Montes', 'description' => 'Legislación y protección del medio ambiente.', 'benefit' => 'Diploma de experto ambiental.', 'img' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=2013&auto=format&fit=crop'],
                ['title' => 'Liderazgo 360', 'date' => '15 Ago', 'duration' => '4 Semanas', 'cat' => 'Gestión', 'price' => '$22.000', 'lecturer' => 'Dr. Sergio Val', 'description' => 'Habilidades directivas en el ámbito institucional legal.', 'benefit' => 'Mejora en gestión de equipos.', 'img' => 'https://images.unsplash.com/photo-1519389950473-4422e4a2e1dc?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'Protección de Datos', 'date' => '05 Sep', 'duration' => '6 Semanas', 'cat' => 'Seguridad', 'price' => '$24.000', 'lecturer' => 'Mag. Carla Rius', 'description' => 'Normativas internacionales de privacidad y GDPR.', 'benefit' => 'Certificación avanzada.', 'img' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Derecho Espacial', 'date' => '15 Sep', 'duration' => '4 Semanas', 'cat' => 'Internacional', 'price' => '$35.000', 'lecturer' => 'Dr. Neil Stron', 'description' => 'Nuevas fronteras legales fuera de la atmósfera terrestre.', 'benefit' => 'Certificado de vanguardia legal.', 'img' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?q=80&w=1772&auto=format&fit=crop'],
                ['title' => 'Legal Design Think', 'date' => '22 Sep', 'duration' => '3 Semanas', 'cat' => 'Tecnología', 'price' => '$19.500', 'lecturer' => 'Lic. Maria Bold', 'description' => 'Cree documentos legales que el usuario final sí entienda.', 'benefit' => 'Mejora en UX legal y cumplimiento.', 'img' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Derecho Marítimo', 'date' => '01 Oct', 'duration' => '10 Semanas', 'cat' => 'Internacional', 'price' => '$55.000', 'lecturer' => 'Mag. Raul Buque', 'description' => 'Legislación de comercio internacional en aguas abiertas.', 'benefit' => 'Especialista en logística global.', 'img' => 'https://images.unsplash.com/photo-1520440229334-962aee11302c?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Sentencias Masivas', 'date' => '10 Oct', 'duration' => '6 Semanas', 'cat' => 'Gestión', 'price' => '$45.000', 'lecturer' => 'Dr. Automation', 'description' => 'Cómo gestionar flujos de sentencias de alta demanda.', 'benefit' => 'Optimización de juzgados de masa.', 'img' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Justicia Abierta', 'date' => '15 Oct', 'duration' => '4 Semanas', 'cat' => 'Especialización', 'price' => '$15.000', 'lecturer' => 'Lic. Clara Trasp', 'description' => 'Transparencia y datos abiertos en el poder judicial.', 'benefit' => 'Habilitación de observatorios.', 'img' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Derecho Deportivo', 'date' => '25 Oct', 'duration' => '8 Semanas', 'cat' => 'Especialización', 'price' => '$22.000', 'lecturer' => 'Mag. Leo Gol', 'description' => 'Gestión legal de contratos y federaciones internacionales.', 'benefit' => 'Agente FIFA / Especialista deportivo.', 'img' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?q=80&w=1770&auto=format&fit=crop']
            ];
        @endphp

        @foreach($cursos as $curso)
            <div class="col text-center">
                <div class="course-poster-wrapper shadow-sm mx-auto" onclick='showCourseDetails(@json($curso))'>
                    <div class="course-poster-inner" style="background-image: url('{{ $curso['img'] }}');"></div>
                    <div class="course-overlay text-start">
                        <div class="course-category-pill">{{ $curso['cat'] }}</div>
                        <h6 class="course-title-card">{{ $curso['title'] }}</h6>
                        <div class="course-date-card">{{ $curso['date'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- MODAL SALES ROLLS-ROYCE --}}
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden shadow-lg" style="border-radius: 20px; background: #0f172a;">
            <div id="modalHeaderImage" class="modal-sales-header">
                <div class="modal-sales-overlay"></div>
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 p-2 bg-dark bg-opacity-50 rounded-circle" data-bs-dismiss="modal" style="font-size: 8px;"></button>
                <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white">
                    <span id="modalCategory" class="badge bg-primary rounded-pill px-3 py-1 xx-small fw-black mb-2"></span>
                    <h2 id="modalTitle" class="fw-black mb-0 display-6" style="font-family: 'Outfit', sans-serif;"></h2>
                </div>
            </div>
            <div class="modal-body p-4 p-lg-5 text-white">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <section class="mb-4">
                            <h6 class="text-primary x-small fw-bold ls-2 uppercase mb-3">¿DE QUÉ TRATA EL CURSO?</h6>
                            <p id="modalDescription" class="text-white-50 small fw-light"></p>
                        </section>
                        <section class="mb-4">
                            <h6 class="text-primary x-small fw-bold ls-2 uppercase mb-3">PRINCIPALES BENEFICIOS</h6>
                            <p id="modalBenefit" class="text-white-50 small fw-light"></p>
                        </section>
                        <section>
                            <h6 class="text-primary x-small fw-bold ls-2 uppercase mb-3">DOCENTES ACADÉMICOS</h6>
                            <p id="modalLecturer" class="fw-bold text-white small"></p>
                        </section>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-white bg-opacity-5 p-4 rounded-4 border border-white border-opacity-10 shadow-sm">
                            <div class="d-flex justify-content-between mb-3 feature-item">
                                <span class="xx-small text-white-50 fw-bold uppercase">DURACIÓN</span>
                                <span id="modalDuration" class="x-small fw-bold text-white"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 feature-item">
                                <span class="xx-small text-white-50 fw-bold uppercase">CERTIFICACIÓN</span>
                                <span class="x-small fw-bold text-white text-end">INTERNACIONAL</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 feature-item">
                                <span class="xx-small text-white-50 fw-bold uppercase">INVERSIÓN</span>
                                <span id="modalPrice" class="h5 fw-black text-warning mb-0"></span>
                            </div>

                            <button class="btn btn-primary w-100 rounded-pill py-3 fw-black shadow-sm mb-3">
                                INSCRIBIRSE AHORA <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <div class="text-center">
                                <span class="xx-small text-white-50 ls-1 fw-bold">INICIO: <span id="modalDate" class="text-white"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showCourseDetails(item) {
        document.getElementById('modalTitle').innerText = item.title;
        document.getElementById('modalCategory').innerText = item.cat;
        document.getElementById('modalDate').innerText = item.date;
        document.getElementById('modalPrice').innerText = item.price;
        document.getElementById('modalLecturer').innerText = item.lecturer;
        document.getElementById('modalDescription').innerText = item.description;
        document.getElementById('modalBenefit').innerText = item.benefit;
        document.getElementById('modalDuration').innerText = item.duration;
        document.getElementById('modalHeaderImage').style.backgroundImage = `url('${item.img}')`;
        
        var myModal = new bootstrap.Modal(document.getElementById('courseModal'));
        myModal.show();
    }
</script>
@endsection

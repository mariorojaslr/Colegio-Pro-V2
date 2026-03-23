@extends('layouts.main')

@section('title', 'Academia | Colegio-Pro')

@push('styles')
<style>
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2.5px; }
    .fw-black { font-weight: 900; }
    .x-small { font-size: 10px; }

    /* ACADEMY HERO */
    .academy-hero {
        background: linear-gradient(90deg, #0F172A 0%, rgba(15, 23, 42, 0.6) 100%), url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        min-height: 220px;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    /* COURSE CARD ROLLS-ROYCE STYLE */
    .course-poster-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        background: #000;
        aspect-ratio: 2/3;
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .course-poster-wrapper:hover {
        transform: scale(1.03) translateY(-4px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        z-index: 10;
    }

    .course-poster-inner {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        transition: transform 0.8s ease;
    }

    .course-poster-wrapper:hover .course-poster-inner {
        transform: scale(1.08);
    }

    /* GRADIENT OVERLAY (Exacto a la Captura) */
    .course-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 65%;
        background: linear-gradient(0deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 50%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
        color: white;
    }

    .course-category-pill {
        background: #0D6EFD; /* Azul Vibrante solicitado */
        color: white;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 50px;
        width: fit-content;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .course-title-card {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 6px;
        font-family: 'Outfit', sans-serif;
    }

    .course-date-card {
        font-size: 14px;
        color: #94A3B8; /* Plata sutil */
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* FILTER BAR PILLS */
    .filter-pill {
        cursor: pointer;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        padding: 8px 22px;
        border-radius: 50px;
        border: 1px solid #E2E8F0;
        background: white;
        color: #64748B;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .filter-pill.active {
        background: #0F172A;
        color: white;
        border-color: #0F172A;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4 min-vh-100 bg-light-subtle">
    
    {{-- Hero de Alta Gama --}}
    <div class="academy-hero p-5 mb-5 shadow-sm">
        <div class="col-lg-7 animate__animated animate__fadeIn">
            <h6 class="text-white-50 x-small fw-bold ls-2 uppercase mb-2">ESCUELA VIRTUAL</h6>
            <h1 class="display-4 fw-black text-white mb-2" style="font-family: 'Outfit', sans-serif; letter-spacing: -2px;">Academia Pro</h1>
            <p class="text-white-50 lead fs-6 fw-light mb-4 ls-1">Excelencia académica para la judicatura moderna.</p>
            
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-primary btn-sm rounded-pill px-4 fw-black shadow-sm">
                    <i class="bi bi-play-fill me-1"></i> VER AHORA
                </a>
                <a href="#" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">
                   EXPLORAR MÓDULOS
                </a>
            </div>
        </div>
    </div>

    {{-- Temas Discretos --}}
    <div class="mb-5 d-flex flex-wrap gap-2 justify-content-center">
        <span class="filter-pill active">Todos</span>
        <span class="filter-pill">Gestión Judicial</span>
        <span class="filter-pill">Tecnología</span>
        <span class="filter-pill">Mediación</span>
        <span class="filter-pill">Administración</span>
    </div>

    {{-- Grid de Cursos (6 Columnas en Monitor Grande) --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 g-4 mb-5">
        @php
            $cursos = [
                ['title' => 'Innovación en Gestión Judicial', 'date' => '15 May', 'cat' => 'Tecnología', 'img' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop', 'price' => '$25.000', 'lecturer' => 'Dr. Alberto Ruiz'],
                ['title' => 'Inteligencia Artificial Legal', 'date' => '22 May', 'cat' => 'Digital', 'img' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1932&auto=format&fit=crop', 'price' => '$32.000', 'lecturer' => 'Ing. Marta Sosa'],
                ['title' => 'Ciberseguridad Notarial', 'date' => '02 Jun', 'cat' => 'Seguridad', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1770&auto=format&fit=crop', 'price' => '$18.500', 'lecturer' => 'Lic. Pedro Gómez'],
                ['title' => 'Marketing para Abogados', 'date' => '10 Jun', 'cat' => 'Negocios', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2026&auto=format&fit=crop', 'price' => '$12.000', 'lecturer' => 'Mag. Lucia Fernández'],
                ['title' => 'Mediación Digital 2024', 'date' => '18 Jun', 'cat' => 'Legal', 'img' => 'https://images.unsplash.com/photo-1521791136364-758a64045057?q=80&w=1770&auto=format&fit=crop', 'price' => '$21.000', 'lecturer' => 'Escribano Carlos Paz'],
                ['title' => 'Blockchain en Registros', 'date' => '25 Jun', 'cat' => 'Innovación', 'img' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2032&auto=format&fit=crop', 'price' => '$28.000', 'lecturer' => 'Dr. Jorge Lin'],
                ['title' => 'Contratos Inteligentes', 'date' => '05 Jul', 'cat' => 'Tecnología', 'img' => 'https://images.unsplash.com/photo-1633533405342-6e27161b32de?q=80&w=1964&auto=format&fit=crop', 'price' => '$35.000', 'lecturer' => 'Dr. Alberto Ruiz'],
                ['title' => 'Ética en Algoritmos', 'date' => '12 Jul', 'cat' => 'Ética', 'img' => 'https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1770&auto=format&fit=crop', 'price' => '$15.000', 'lecturer' => 'Marta Sosa'],
                ['title' => 'Técnicas de Oratoria', 'date' => '20 Jul', 'cat' => 'Habilidades', 'img' => 'https://images.unsplash.com/photo-1475721027187-40247339a3f9?q=80&w=2070&auto=format&fit=crop', 'price' => '$10.500', 'lecturer' => 'Pedro Gómez'],
                ['title' => 'Gestión de Estudios', 'date' => '01 Ago', 'cat' => 'Adm.', 'img' => 'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2070&auto=format&fit=crop', 'price' => '$19.000', 'lecturer' => 'Carlos Paz'],
                ['title' => 'Liderazgo Estratégico', 'date' => '15 Ago', 'cat' => 'Negocios', 'img' => 'https://images.unsplash.com/photo-1519389950473-4422e4a2e1dc?q=80&w=2070&auto=format&fit=crop', 'price' => '$24.000', 'lecturer' => 'Lucia Fernández'],
                ['title' => 'Peritaje Informático', 'date' => '30 Ago', 'cat' => 'Peritaje', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1770&auto=format&fit=crop', 'price' => '$27.500', 'lecturer' => 'Jorge Lin']
            ];
        @endphp

        @foreach($cursos as $curso)
            <div class="col">
                <div class="course-poster-wrapper" onclick='showCourseDetails(@json($curso))'>
                    <div class="course-poster-inner" style="background-image: url('{{ $curso['img'] }}');"></div>
                    <div class="course-overlay">
                        <div class="course-category-pill">{{ $curso['cat'] }}</div>
                        <h5 class="course-title-card">{{ $curso['title'] }}</h5>
                        <div class="course-date-card">{{ $curso['date'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- MODAL PRESTIGIO --}}
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden shadow-lg" style="border-radius: 30px; background: #0F172A;">
            <div class="position-relative" id="modalHeaderImage" style="height: 280px; background-size: cover; background-position: center;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 p-2 bg-dark bg-opacity-50 rounded-circle" data-bs-dismiss="modal" style="font-size: 10px;"></button>
                <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white" style="background: linear-gradient(to top, #0F172A, transparent);">
                    <div id="modalCategory" class="course-category-pill mb-1"></div>
                    <h3 id="modalTitle" class="fw-black mb-0 display-6"></h3>
                </div>
            </div>
            <div class="modal-body p-4 text-white">
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <label class="x-small fw-bold text-muted text-uppercase mb-1 d-block ls-1">PRÓXIMO INICIO</label>
                        <h6 id="modalDate" class="fw-bold mb-0 text-white"></h6>
                    </div>
                    <div class="col-6 border-start border-secondary py-1">
                        <label class="x-small fw-bold text-muted text-uppercase mb-1 d-block ls-1">INVERSIÓN</label>
                        <h5 id="modalPrice" class="fw-black text-warning mb-0"></h5>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="x-small fw-bold text-muted text-uppercase mb-1 d-block ls-1">DOCENTE A CARGO</label>
                    <p id="modalLecturer" class="fw-medium text-white-50 mb-0"></p>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary rounded-pill py-3 fw-black shadow-sm">
                        INSCRIBIRSE AHORA <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    <div class="text-center mt-2">
                        <img src="https://static.cdnlogo.com/logos/v/20/visa.svg" height="15" class="opacity-50 me-2">
                        <img src="https://static.cdnlogo.com/logos/m/20/mastercard.svg" height="15" class="opacity-50 me-2">
                        <span class="x-small text-muted fw-bold ls-1">PAGOS SEGUROS</span>
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
        document.getElementById('modalHeaderImage').style.backgroundImage = `url('${item.img}')`;
        
        var myModal = new bootstrap.Modal(document.getElementById('courseModal'));
        myModal.show();
    }
</script>
@endsection

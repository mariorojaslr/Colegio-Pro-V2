@extends('layouts.main')

@section('title', 'Academia | Colegio-Pro')

@push('styles')
<style>
    .ls-1 { letter-spacing: 0.5px; }
    .ls-2 { letter-spacing: 2px; }
    .fw-black { font-weight: 900; }
    .x-small { font-size: 10px; }
    .xx-small { font-size: 9px; }

    /* ACADEMY HERO SLIM (Fino y delicado) */
    .academy-hero {
        background: linear-gradient(90deg, #0F172A 0%, rgba(15, 23, 42, 0.4) 100%), url('https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        min-height: 140px; /* Reducción drástica de altura */
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
        margin-bottom: 25px; /* Menos derroche de espacio */
    }

    /* COURSE CARD ROLLS-ROYCE SLIM */
    .course-poster-wrapper {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        background: #f8fafc;
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
    }

    /* GRADIENT OVERLAY (Más transparente, deja ver la imagen) */
    .course-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 45%; /* Menor altura para no tapar la imagen */
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 15px;
        color: white;
    }

    .course-category-pill {
        background: #2563eb !important;
        color: white !important;
        font-size: 8.5px; /* Más pequeño */
        font-weight: 800;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 50px;
        width: fit-content;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .course-title-card {
        font-size: 0.95rem; /* Más pequeño y fino */
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 3px;
        font-family: 'Outfit', sans-serif;
    }

    .course-date-card {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
    }

    /* FILTER BAR COMPACTA */
    .filter-pill {
        cursor: pointer;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.8px;
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
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3 min-vh-100 bg-light-subtle">
    
    {{-- Hero Sector (Slim & Elegant) --}}
    <div class="academy-hero p-4 px-5">
        <div class="animate__animated animate__fadeIn">
            <h6 class="text-white-50 xx-small fw-bold ls-2 uppercase mb-1">ESCUELA VIRTUAL</h6>
            <h1 class="h3 fw-black text-white mb-1" style="font-family: 'Outfit', sans-serif;">Academia Pro</h1>
            <p class="text-white-50 x-small fw-light mb-3">Excelencia académica para la judicatura moderna.</p>
            
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-black x-small shadow-sm">
                    VER AHORA
                </a>
                <a href="#" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold x-small">
                   DETALLES
                </a>
            </div>
        </div>
    </div>

    {{-- Temas (Compacto) --}}
    <div class="mb-4 d-flex flex-wrap gap-2 justify-content-center">
        <span class="filter-pill active">Todos</span>
        <span class="filter-pill">Gestión Judicial</span>
        <span class="filter-pill">Nuevas Tecnologías</span>
        <span class="filter-pill">Mediación</span>
        <span class="filter-pill">Administración</span>
    </div>

    {{-- Grid de Cursos (6 Columnas) --}}
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-3 mb-5">
        @php
            $cursos = [
                ['title' => 'Gestión Judicial 4.0', 'date' => '15 May', 'cat' => 'Tecnología', 'img' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'I.A. en Tribunales', 'date' => '22 May', 'cat' => 'Digital', 'img' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=1932&auto=format&fit=crop'],
                ['title' => 'Ciberseguridad Legal', 'date' => '02 Jun', 'cat' => 'Seguridad', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Marketing Jurídico', 'date' => '10 Jun', 'cat' => 'Negocios', 'img' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2026&auto=format&fit=crop'],
                ['title' => 'Mediación 2024', 'date' => '18 Jun', 'cat' => 'Legal', 'img' => 'https://images.unsplash.com/photo-1521791136364-758a64045057?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Blockchain Público', 'date' => '25 Jun', 'cat' => 'Innovación', 'img' => 'https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2032&auto=format&fit=crop'],
                ['title' => 'Contratos Smart', 'date' => '05 Jul', 'cat' => 'Tecnología', 'img' => 'https://images.unsplash.com/photo-1510511459019-5dee9954889c?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Ética Algorítmica', 'date' => '12 Jul', 'cat' => 'Ética', 'img' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Oratoria Forense', 'date' => '20 Jul', 'cat' => 'Habilidades', 'img' => 'https://images.unsplash.com/photo-1475721027187-40247339a3f9?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'Liderazgo 360', 'date' => '15 Ago', 'cat' => 'Gestión', 'img' => 'https://images.unsplash.com/photo-1519389950473-4422e4a2e1dc?q=80&w=2070&auto=format&fit=crop'],
                ['title' => 'Peritaje Digital', 'date' => '30 Ago', 'cat' => 'Peritaje', 'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1770&auto=format&fit=crop'],
                ['title' => 'Derecho de Autor', 'date' => '10 Sep', 'cat' => 'Legal', 'img' => 'https://images.unsplash.com/photo-1589254065878-42c9da997008?q=80&w=1770&auto=format&fit=crop']
            ];
        @endphp

        @foreach($cursos as $curso)
            <div class="col">
                <div class="course-poster-wrapper" onclick='showCourseDetails(@json($curso))'>
                    <div class="course-poster-inner" style="background-image: url('{{ $curso['img'] }}');"></div>
                    <div class="course-overlay">
                        <div class="course-category-pill">{{ $curso['cat'] }}</div>
                        <h6 class="course-title-card">{{ $curso['title'] }}</h6>
                        <div class="course-date-card">{{ $curso['date'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- MODAL REFINADO --}}
<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 overflow-hidden shadow-lg" style="border-radius: 15px; background: #0F172A;">
            <div class="position-relative" id="modalHeaderImage" style="height: 180px; background-size: cover; background-position: center;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 p-2 bg-dark bg-opacity-50 rounded-circle" data-bs-dismiss="modal" style="font-size: 8px;"></button>
            </div>
            <div class="modal-body p-4 text-white">
                <h5 id="modalTitle" class="fw-bold mb-3"></h5>
                <div class="d-flex justify-content-between mb-4 border-top border-secondary pt-3">
                    <div>
                        <small class="text-white-50 xx-small d-block uppercase ls-1 mb-1">INICIO</small>
                        <strong id="modalDate" class="small"></strong>
                    </div>
                    <div class="text-end">
                        <small class="text-white-50 xx-small d-block uppercase ls-1 mb-1">TEMA</small>
                        <strong id="modalCategory" class="small text-primary"></strong>
                    </div>
                </div>
                <button class="btn btn-primary w-100 rounded-pill py-2 fw-black x-small">INSCRIBIRSE</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showCourseDetails(item) {
        document.getElementById('modalTitle').innerText = item.title;
        document.getElementById('modalCategory').innerText = item.cat;
        document.getElementById('modalDate').innerText = item.date;
        document.getElementById('modalHeaderImage').style.backgroundImage = `url('${item.img}')`;
        
        var myModal = new bootstrap.Modal(document.getElementById('courseModal'));
        myModal.show();
    }
</script>
@endsection

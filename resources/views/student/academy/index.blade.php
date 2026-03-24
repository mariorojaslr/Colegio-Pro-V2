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
        background: linear-gradient(90deg, #020617 0%, #020617 10%, rgba(2, 6, 23, 0.6) 40%, transparent 85%), url('https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center right;
        border-radius: 24px;
        min-height: 160px; 
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.05);
        margin-bottom: 30px; 
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    /* COURSE CARD ROLLS-ROYCE SLIM */
    .course-poster-wrapper {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        background: #f8fafc;
        aspect-ratio: 2/3;
        transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.05);
    }

    body.dark-mode .course-poster-wrapper {
        background: #000;
        border: 1px solid rgba(255, 255, 255, 0.35) !important; /* Crystalline Border */
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .course-poster-wrapper:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border-color: #2563eb;
    }

    body.dark-mode .course-poster-wrapper:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.8);
        border-color: rgba(255, 255, 255, 0.3) !important;
    }

    .course-poster-inner {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-color: #e2e8f0;
        transition: transform 0.8s ease;
    }

    .course-poster-wrapper:hover .course-poster-inner {
        transform: scale(1.1);
    }

    .course-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 50%; 
        background: linear-gradient(0deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 60%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 15px;
        color: white;
    }

    .course-category-pill {
        background: #2563eb !important;
        color: white !important;
        font-size: 8px; 
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 10px;
        border-radius: 50px;
        width: fit-content;
        margin-bottom: 6px;
        letter-spacing: 0.8px;
    }

    .course-title-card {
        font-size: 0.9rem; 
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 2px;
        font-family: 'Outfit', sans-serif;
    }

    .course-date-card {
        font-size: 10px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
    }

    /* FILTER BAR PRIVACY/OLED */
    .filter-pill {
        cursor: pointer;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        padding: 8px 20px;
        border-radius: 50px;
        background: transparent !important;
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #64748b !important;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    body.dark-mode .filter-pill {
        border-color: rgba(255, 255, 255, 0.35) !important;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    .filter-pill.active {
        background: #0f172a !important;
        color: white !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    body.dark-mode .filter-pill.active {
        background: white !important;
        color: black !important;
        border-color: white !important;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
    }
    
    .filter-pill:hover {
        border-color: #0f172a !important;
        color: #0f172a !important;
    }

    body.dark-mode .filter-pill:hover {
        border-color: white !important;
        color: white !important;
        background: rgba(255, 255, 255, 0.05) !important;
    }

    /* SEARCH INPUT OLED */
    .academy-search-group {
        border-radius: 50px; 
        overflow: hidden; 
        border: 1px solid rgba(0,0,0,0.08); 
        background: white;
        transition: all 0.3s ease;
    }

    body.dark-mode .academy-search-group {
        background: #000 !important;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .academy-search-group:focus-within {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    body.dark-mode .academy-search-group:focus-within {
        border-color: white;
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
    }

    /* MODAL SALES STYLE */
    .modal-sales-header {
        height: 280px;
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
        background: linear-gradient(0deg, #020617 0%, rgba(2, 6, 23, 0.4) 60%, transparent 100%);
    }

    .feature-item {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        padding: 12px;
        border-radius: 14px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: rgba(255,255,255,0.06);
        border-color: rgba(255,255,255,0.12);
    }

    mark { 
        background: #fff3cd !important; 
        padding: 0.1em 0.2em !important; 
        color: #856404; 
        font-weight: 800; 
        border-radius: 4px; 
    }

    body.dark-mode mark {
        background: #2563eb !important;
        color: white !important;
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

    {{-- Buscador Premium --}}
    <div class="row justify-content-center mb-5">
        <div class="col-md-6 col-lg-5">
            <div class="input-group input-group-lg shadow-sm academy-search-group">
                <span class="input-group-text bg-transparent border-0 ps-4"><i class="bi bi-search text-primary"></i></span>
                <input type="text" id="academySearch" class="form-control border-0 py-3 ps-2 bg-transparent" placeholder="Buscar cursos, docentes o especialidades..." style="font-family: 'Outfit', sans-serif; font-size: 0.9rem; font-weight: 500;">
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="mb-5 d-flex flex-wrap gap-2 justify-content-center">
        <span class="filter-pill active" onclick="filterAcademy('Todos')">Todos</span>
        @foreach($lessons->pluck('category')->unique()->filter() as $cat)
            <span class="filter-pill" onclick="filterAcademy('{{ $cat }}')">{{ $cat }}</span>
        @endforeach
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-4 mb-5" id="academyGrid">
        @foreach($lessons as $lesson)
            @php
                $courseData = [
                    "id" => $lesson->id,
                    "title" => $lesson->title,
                    "cat" => $lesson->category ?? "General",
                    "date" => $lesson->start_date ?? "Próximamente",
                    "price" => "$" . number_format($lesson->price ?? 0, 0, ",", "."),
                    "lecturer" => $lesson->lecturer ?? "Docente Staff",
                    "description" => $lesson->description ?? "",
                    "benefit" => $lesson->benefit ?? "Certificación Internacional",
                    "duration" => $lesson->duration ?? "4 Semanas",
                    "img" => $lesson->thumbnail_url ?? "https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=2070&auto=format&fit=crop",
                    "enrolled" => in_array($lesson->id, $enrolledLessons ?? []),
                    "cert" => isset($certificates[$lesson->id]) ? route('student.certificates.download', $certificates[$lesson->id]->id) : null
                ];
            @endphp
            <div class="col text-center academy-item" data-category="{{ $lesson->category ?? 'General' }}">
                <div class="course-poster-wrapper shadow-sm mx-auto" 
                     onclick="showCourseDetails(JSON.parse(this.dataset.course))" 
                     data-course="{{ json_encode($courseData) }}">
                    <div class="course-poster-inner" style="background-image: url('{{ $lesson->thumbnail_url ?? "https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=2070&auto=format&fit=crop" }}');"></div>
                    <div class="course-overlay text-start">
                        <div class="course-category-pill">{{ $lesson->category ?? "GENERAL" }}</div>
                        <h6 class="course-title-card">{{ $lesson->title }}</h6>
                        <div class="course-date-card">{{ $lesson->start_date ?? "Próximamente" }}</div>
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

                            <div id="enrollmentActions">
                                <form id="enrollForm" method="POST" action="">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-black shadow-sm mb-3">
                                        INSCRIBIRSE AHORA <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </form>
                                <a id="viewCourseBtn" href="" class="btn btn-success w-100 rounded-pill py-3 fw-black shadow-sm mb-3" style="display:none;">
                                    COMENZAR APRENDIZAJE <i class="bi bi-play-fill ms-2"></i>
                                </a>
                                <a id="downloadCertBtn" href="" class="btn btn-warning w-100 rounded-pill py-3 fw-black shadow-sm mb-3 text-dark" style="display:none;">
                                    DESCARGAR CERTIFICADO <i class="bi bi-award-fill ms-2"></i>
                                </a>
                            </div>
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
        
        const enrollForm = document.getElementById('enrollForm');
        const viewBtn = document.getElementById('viewCourseBtn');
        const certBtn = document.getElementById('downloadCertBtn');

        if (item.cert) {
            certBtn.style.display = 'block';
            certBtn.href = item.cert;
        } else {
            certBtn.style.display = 'none';
        }

        if (item.enrolled) {
            enrollForm.style.display = 'none';
            viewBtn.style.display = 'block';
            viewBtn.href = `/mis-clases/${item.id}`;
        } else {
            enrollForm.style.display = 'block';
            viewBtn.style.display = 'none';
            enrollForm.action = `/mis-clases/inscribirse/${item.id}`;
        }

        var myModal = new bootstrap.Modal(document.getElementById('courseModal'));
        myModal.show();
    }

    function filterAcademy(cat) {
        // Update pills
        document.querySelectorAll('.filter-pill').forEach(p => {
            p.classList.remove('active');
            if(p.innerText === cat) p.classList.add('active');
        });

        // Filter items
        const searchTerm = document.getElementById('academySearch').value.toLowerCase();
        document.querySelectorAll('.academy-item').forEach(item => {
            const category = item.getAttribute('data-category');
            const title = item.querySelector('.course-title-card').innerText.toLowerCase();
            
            const matchCategory = (cat === 'Todos' || category === cat);
            const matchSearch = title.includes(searchTerm) || category.toLowerCase().includes(searchTerm);

            if(matchCategory && matchSearch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.getElementById('academySearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const activeCat = document.querySelector('.filter-pill.active').innerText;
        
        document.querySelectorAll('.academy-item').forEach(item => {
            const titleEl = item.querySelector('.course-title-card');
            const catEl = item.querySelector('.course-category-pill');
            const titleText = titleEl.innerText;
            const catText = catEl.innerText;

            const matchCategory = (activeCat === 'Todos' || item.getAttribute('data-category') === activeCat);
            const matchSearch = titleText.toLowerCase().includes(searchTerm) || catText.toLowerCase().includes(searchTerm);

            if(matchCategory && matchSearch) {
                item.style.display = 'block';
                // Highlight search term
                if(searchTerm.length > 0) {
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    titleEl.innerHTML = titleText.replace(regex, '<mark>$1</mark>');
                } else {
                    titleEl.innerHTML = titleText;
                }
            } else {
                item.style.display = 'none';
                titleEl.innerHTML = titleText;
            }
        });
    });
</script>
@endsection

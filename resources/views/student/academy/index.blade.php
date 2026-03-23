@extends('layouts.main')

@section('title', 'Academia | Colegio-Pro')

@push('styles')
<style>
    :root {
        --poster-ratio: 2/3;
    }
    .academy-hero {
        background: linear-gradient(90deg, #0F172A 0%, rgba(15, 23, 42, 0.7) 100%), 
                    url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1600&q=80') center/cover;
        min-height: 180px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        padding: 30px;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .course-card-wrapper {
        transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
    }
    .course-card-wrapper:hover {
        transform: translateY(-8px);
    }
    .course-poster {
        aspect-ratio: var(--poster-ratio);
        border-radius: 10px;
        overflow: hidden;
        background: #111;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }
    .course-poster img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .course-card-wrapper:hover img {
        transform: scale(1.1);
    }
    .course-info-footer {
        padding: 0 4px;
    }
    .course-title {
        font-size: 0.85rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 2px;
        color: var(--primary-color);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.1rem;
    }
    .course-category {
        font-size: 10px;
        font-weight: 800;
        text-uppercase: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3 bg-light-subtle">
    
    {{-- Hero Slim --}}
    <div class="academy-hero text-white animate__animated animate__fadeIn">
        <div class="hero-content">
            <span class="x-small fw-bold text-primary text-uppercase ls-2 d-block mb-1">Especialización Profesional</span>
            <h2 class="fw-black mb-2 h3">Academia <span class="text-primary">Virtual</span></h2>
            <p class="small mb-3 opacity-75" style="max-width: 500px;">Certificaciones oficiales con validación QR y material de consulta permanente.</p>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">Continuar Taller</button>
            </div>
        </div>
    </div>

    {{-- Filtros Discretos --}}
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 overflow-auto no-scrollbar">
            <span class="x-small fw-bold text-muted text-uppercase me-2" style="font-size: 9px;">FILTRAR POR:</span>
            <button class="btn btn-sm btn-dark rounded-pill px-3 py-1 x-small fw-bold shadow-sm">TODOS</button>
            <button class="btn btn-sm btn-white text-muted border rounded-pill px-3 py-1 x-small fw-bold">DERECHO CIVIL</button>
            <button class="btn btn-sm btn-white text-muted border rounded-pill px-3 py-1 x-small fw-bold">PENAL</button>
            <button class="btn btn-sm btn-white text-muted border rounded-pill px-3 py-1 x-small fw-bold">SALUD</button>
            <button class="btn btn-sm btn-white text-muted border rounded-pill px-3 py-1 x-small fw-bold">GESTIÓN</button>
        </div>
    </div>

    {{-- Grilla de 6 Columnas en Monitor Grande --}}
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-4 mb-5">
        @php
            $especialidades = [
                ['SALUD', 'RCP & Primeros Auxilios', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_rcp_course_1774222864134.png', 'Dr. Roberto García', '12h', '25.000'],
                ['LEGAL TECH', 'Arquitectura Legal en Salud', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_legal_health_1774222880824.png', 'Dra. Elena Martínez', '15h', '38.000'],
                ['GESTIÓN', 'Innovación Gestión Judicial', 'https://mariorojaslr.github.io/Colegio-Pro-V2/artifacts/flyer_judicial_innovation_1774222897356.png', 'Dr. Juan Pérez', '8h', '45.000'],
                ['PENAL', 'Reformas Procesales 2026', 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=500&q=80', 'Dr. Sergio Massa', '20h', '55.000'],
                ['SUCESIONES', 'Práctica Juicios Sucesorios', 'https://images.unsplash.com/photo-1589994160839-163cd2b5ecce?auto=format&fit=crop&w=500&q=80', 'Dra. María Lopez', '10h', '40.000'],
                ['TRIBUTARIO', 'Actualización AFIP 2026', 'https://images.unsplash.com/photo-1554224155-16974301755d?auto=format&fit=crop&w=500&q=80', 'Cont. Ana Sosa', '14h', '42.000'],
                ['COMERCIAL', 'Contratos & Startups', 'https://images.unsplash.com/photo-1454165833767-0275084927ed?auto=format&fit=crop&w=500&q=80', 'Dr. Luis Caputo', '11h', '35.000'],
                ['ADMIN', 'Proc. Administrativo', 'https://images.unsplash.com/photo-1423592707957-3b212afa6733?auto=format&fit=crop&w=500&q=80', 'Dra. Patricia Bull', '9h', '28.000'],
                ['MEDIACIÓN', 'Resolución Conflictos', 'https://images.unsplash.com/photo-1573164773501-229ef2159f81?auto=format&fit=crop&w=500&q=80', 'Dr. Jorge Macri', '12h', '30.000'],
                ['INMOB.', 'Alquileres & Práctica', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=500&q=80', 'Dra. Victoria V.', '10h', '34.000'],
                ['CIVIL', 'Resp. Civil Profesional', 'https://images.unsplash.com/photo-1507679799987-c7377ec48696?auto=format&fit=crop&w=500&q=80', 'Dr. Ricardo Darín', '6h', '25.000'],
                ['IDIOMAS', 'Legal English for Lawyers', 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=500&q=80', 'Prof. Sarah Connor', '30h', '60.000']
            ];
        @endphp

        @foreach($especialidades as $index => $esp)
        <div class="col">
            <div class="course-card-wrapper h-100" data-bs-toggle="modal" data-bs-target="#courseModal{{ $index }}">
                <div class="course-poster">
                    <img src="{{ $esp[2] }}" alt="{{ $esp[1] }}" 
                         onerror="this.src='https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=500&q=80'">
                </div>
                <div class="course-info-footer">
                    <div class="course-category">{{ $esp[0] }}</div>
                    <div class="course-title">{{ $esp[1] }}</div>
                </div>
            </div>
        </div>

        {{-- Modal Detallado --}}
        <div class="modal fade" id="courseModal{{ $index }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <div class="row g-0">
                        <div class="col-md-5 d-none d-md-block" style="background: url('{{ $esp[2] }}?auto=format&fit=crop&w=800&q=80') center/cover;">
                            <div class="h-100 min-vh-50 bg-black bg-opacity-25"></div>
                        </div>
                        <div class="col-md-7 p-4 p-lg-5 bg-white text-start">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill fw-bold" style="font-size: 10px;">{{ $esp[0] }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <h3 class="fw-bold text-dark mb-3">{{ $esp[1] }}</h3>
                            <p class="text-muted mb-4 small">Capacitación académica exclusiva diseñada para profesionales que buscan la excelencia en su práctica diaria.</p>
                            
                            <div class="row g-2 mb-4 text-center">
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded-3 border">
                                        <p class="x-small text-muted mb-0 fw-bold">DURACIÓN</p>
                                        <h6 class="mb-0 fw-bold small text-dark">{{ $esp[4] }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-2 rounded-3 border">
                                        <p class="x-small text-muted mb-0 fw-bold">DOCENTE</p>
                                        <h6 class="mb-0 fw-bold small text-dark">{{ $esp[3] }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <p class="x-small text-muted mb-0 fw-bold">VALOR ARANCEL</p>
                                    <h3 class="mb-0 fw-black text-dark">${{ $esp[5] }}</h3>
                                </div>
                                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Inscribirse <i class="bi bi-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Certificaciones (Discretas) --}}
    <div class="section-awards">
        <h6 class="fw-bold mb-3 text-muted text-uppercase ls-1">Mis Logros Académicos</h6>
        <div class="bg-white p-3 rounded-4 shadow-sm border border-light-subtle">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <tbody>
                        <tr>
                            <td class="py-2 fw-bold text-dark"><i class="bi bi-award-fill text-warning me-2"></i> Diplomado en Derecho Admin</td>
                            <td><span class="badge bg-light text-dark border-0 rounded-pill">ADMIN</span></td>
                            <td class="text-end text-success fw-bold">CARGA QR LISTA</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

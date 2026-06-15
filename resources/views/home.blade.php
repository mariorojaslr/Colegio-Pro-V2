@extends('layouts.main')

@section('content')
<div class="container-fluid py-3 min-vh-100 bg-light-subtle">
    {{-- Consola de Simulación Prestigio (Discreta) --}}
    @if(session('impersonator_id'))
    <div class="d-flex justify-content-end mb-3 gap-2 px-2 animate__animated animate__fadeInDown">
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.leave_impersonation') }}" class="btn btn-danger btn-sm border-0 rounded-pill px-3 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-left me-1"></i> SALIR DE SIMULACIÓN
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->role === 'ADMIN_COLEGIO')
        {{-- 1. Identidad Institucional (Minimalista / Soberana) --}}
        <div class="row g-4 mb-3">
            <div class="col-lg-12">
                <div class="p-3 bg-white border-0 shadow-sm overflow-hidden d-flex align-items-center justify-content-between" 
                     style="border-radius: 12px; border-left: 4px solid {{ Auth::user()->school->primary_color ?? '#0f172a' }} !important;">
                    <div>
                        <h6 class="text-muted x-small fw-bold text-uppercase ls-2 mb-0">Gestión Institucional</h6>
                        <h4 class="fw-black mb-0 text-dark" style="font-family: 'Outfit', sans-serif;">{{ Auth::user()->school->name }}</h4>
                    </div>
                    <div>
                        <a href="{{ route('collegiates.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 x-small fw-bold shadow-sm">
                            VER {{ strtoupper(Auth::user()->school->member_plural ?? 'Colegiados') }} <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Tarjetas de Acción (Lujo Silencioso) --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
            <div class="col">
                <a href="{{ route('collegiates.index') }}" class="text-decoration-none h-100 d-block">
                    <div class="bg-white p-3 border-0 h-100 shadow-sm border-start border-4 border-primary" style="border-radius: 12px; transition: transform 0.3s ease;">
                        <span class="text-muted x-small fw-bold text-uppercase ls-1 d-block mb-1">{{ Auth::user()->school->member_plural ?? 'Matriculados' }}</span>
                        <h2 class="fw-black mb-1 text-dark ls-n1">{{ $totalColegiados }}</h2>
                        <div class="x-small fw-bold text-primary opacity-75">Habilitados en padrón</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('admin.billing.index', ['status' => 'overdue']) }}" class="text-decoration-none h-100 d-block">
                    <div class="bg-white p-3 border-0 h-100 shadow-sm border-start border-4 border-danger" style="border-radius: 12px; transition: transform 0.3s ease;">
                        <span class="text-muted x-small fw-bold text-uppercase ls-1 d-block mb-1">Mora Arancelaria</span>
                        <h2 class="fw-black mb-1 text-danger ls-n1">{{ $morososCuotas }}</h2>
                        <div class="x-small fw-bold text-danger opacity-75">Acciones pendientes</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('collegiates.index', ['filter' => 'sin_papeles']) }}" class="text-decoration-none h-100 d-block">
                    <div class="bg-white p-3 border-0 h-100 shadow-sm border-start border-4 border-warning" style="border-radius: 12px;">
                        <span class="text-muted x-small fw-bold text-uppercase ls-1 d-block mb-1">Legajos Pendientes</span>
                        <h2 class="fw-black mb-1 text-warning ls-n1">{{ $morososDocs }}</h2>
                        <div class="x-small fw-bold text-warning opacity-75">Auditoría de cumplimiento</div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('collegiates.index', ['filter' => 'habilitados']) }}" class="text-decoration-none h-100 d-block">
                    <div class="bg-white p-3 border-0 h-100 shadow-sm border-start border-4 border-success" style="border-radius: 12px;">
                        <span class="text-muted x-small fw-bold text-uppercase ls-1 d-block mb-1">Institución Sana</span>
                        <h2 class="fw-black mb-1 text-success ls-n1">{{ $habilitados }}</h2>
                        <div class="x-small fw-bold text-success opacity-75">Cumplimiento total</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-5">
            {{-- Columna Izquierda: Cartelera Académica (Estilo Netflix) --}}
            @if($news->isNotEmpty())
            <div class="col-lg-12">
                <div class="mb-5">
                    <h5 class="fw-bold mb-4 d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-play-circle-fill me-2 text-primary"></i> {{ __('Próximos Cursos y Capacitaciones') }}</span>
                        <span class="small text-muted fw-normal">{{ __('Explorar Todo') }} <i class="bi bi-chevron-right fs-xs"></i></span>
                    </h5>
                    
                    {{-- Contenedor Netflix --}}
                    <div class="netflix-row px-1">
                        @foreach($news as $item)
                        <div class="netflix-card-wrapper" onclick="showCourseDetails({{ json_encode($item) }})">
                            <div class="netflix-card border-0 shadow-sm" style="background-image: url('{{ $item['flyer'] }}');">
                                <div class="netflix-card-overlay">
                                    <div class="content">
                                        <span class="badge bg-primary rounded-pill mb-2" style="font-size: 8px">{{ $item['category'] }}</span>
                                        <h6 class="title fw-bold text-white mb-1">{{ $item['title'] }}</h6>
                                        <p class="date small text-white-50 mb-0"><i class="bi bi-calendar-event me-1"></i> {{ $item['date_short'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Columna Izquierda: Accesos Rápidos (Ahora abajo para dar aire al carrusel) --}}
            <div class="col-lg-8">
                <h5 class="fw-bold mb-4">Gestión de Campo e Institucional</h5>
                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('amenities.index') }}" class="text-decoration-none">
                            <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center text-center">
                                <i class="bi bi-calendar-event fs-1 text-primary mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Quincho y Canchas</h6>
                                <p class="small text-muted mb-0">Reservas Online</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.billing.index') }}" class="text-decoration-none text-center">
                            <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-currency-dollar fs-1 text-primary mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Cobranzas</h6>
                                <p class="small text-muted mb-0">Gestión de Cuotas</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('admin.ethics.index') }}" class="text-decoration-none text-center">
                            <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-shield-check fs-1 text-primary mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Cámara de Ética</h6>
                                <p class="small text-muted mb-0">Tribunal Disciplinario</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('ai.index') }}" class="text-decoration-none text-center">
                            <div class="card-prestige p-4 border-0 bg-white shadow-sm h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-robot fs-1 text-primary mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Asistente AI</h6>
                                <p class="small text-muted mb-0">Gestión Inteligente</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                 <div class="card-prestige p-4 border-0 bg-dark text-white shadow-sm h-100" style="background: linear-gradient(135deg, #020617, #1e293b);">
                    <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-shield-check me-2"></i> Estado de Sistema</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small opacity-75">Servidores</span>
                        <span class="small fw-bold text-success">Online <i class="bi bi-dot"></i></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small opacity-75">Sincronización</span>
                        <span class="small fw-bold">100% OK</span>
                    </div>
                    <hr class="opacity-10 my-3">
                    <p class="small text-muted italic mb-0">Colegio-Pro Ver 1.2.0 - Dashboard Multi-SaaS</p>
                </div>
            </div>
        </div>

        {{-- MODAL DE DETALLES DEL CURSO (ESTILO NETFLIX) --}}
        <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 overflow-hidden shadow-lg" style="border-radius: 30px; background: #0f172a;">
                    <div class="position-relative" id="modalHeaderImage" style="height: 350px; background-size: cover; background-position: center;">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 p-3 bg-dark bg-opacity-50 rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="position-absolute bottom-0 start-0 w-100 p-5 text-white" style="background: linear-gradient(to top, #0f172a, transparent);">
                            <span id="modalCategory" class="badge bg-primary rounded-pill mb-2 px-3"></span>
                            <h2 id="modalTitle" class="display-6 fw-bold mb-0"></h2>
                        </div>
                    </div>
                    <div class="modal-body p-5 text-white">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="d-flex align-items-center gap-1 text-warning">
                                        <i class="bi bi-star-fill"></i>
                                        <span id="modalRating" class="fw-bold"></span>
                                    </div>
                                    <span class="opacity-50 border-start ps-3 border-secondary" id="modalDates"></span>
                                    <span id="modalWeight" class="badge bg-secondary bg-opacity-25 text-white-50 border border-secondary border-opacity-50"></span>
                                </div>
                                <h6 class="fw-bold text-primary mb-2">{{ __('Descripción') }}</h6>
                                <p id="modalDescription" class="opacity-75 lh-base mb-4"></p>
                            </div>
                            <div class="col-md-5">
                                <div class="bg-secondary bg-opacity-10 p-4 rounded-4 border border-secondary border-opacity-20 mb-3">
                                    <div class="mb-3">
                                        <label class="small opacity-50 d-block mb-1">{{ __('Dictado por') }}:</p>
                                        <h6 id="modalLecturer" class="fw-bold mb-0"></h6>
                                    </div>
                                    <div class="mb-0">
                                        <label class="small opacity-50 d-block mb-1">{{ __('Inversión') }}:</p>
                                        <h4 id="modalValue" class="fw-bold text-success mb-0"></h4>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">{{ __('Inscribirse Ahora') }} <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showCourseDetails(item) {
                document.getElementById('modalTitle').innerText = item.title;
                document.getElementById('modalCategory').innerText = item.category;
                document.getElementById('modalRating').innerText = item.rating;
                document.getElementById('modalDates').innerText = item.date_range;
                document.getElementById('modalWeight').innerText = item.academic_weight;
                document.getElementById('modalDescription').innerText = item.description;
                document.getElementById('modalLecturer').innerText = item.lecturer;
                document.getElementById('modalValue').innerText = item.value;
                document.getElementById('modalHeaderImage').style.backgroundImage = `url('${item.flyer}')`;
                
                var myModal = new bootstrap.Modal(document.getElementById('courseModal'));
                myModal.show();
            }
        </script>

    @else
        {{-- DASHBOARD PARA EL COLEGIADO (SIMPLE Y TRADICIONAL) --}}
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card-prestige p-5 mb-4 border-0 overflow-hidden position-relative" 
                     style="background: #020617; border-radius: 40px">
                    <div class="row align-items-center position-relative" style="z-index: 2">
                        <div class="col-md-8 text-white">
                            <h1 class="display-5 fw-bold mb-1 shadow-text" style="font-family: 'Outfit', sans-serif;">Hola, <span class="text-warning">{{ explode(' ', Auth::user()->name)[0] }}</span></h1>
                            <p class="lead opacity-75 mb-0 fw-medium">Su estado en el <span class="text-white fw-bold">{{ Auth::user()->school->name }}</span></p>
                        </div>
                        @if($collegiate && $collegiate->isSanctioned())
                        <div class="col-md-4 text-md-end mt-4 mt-md-0">
                             <div class="alert alert-danger bg-danger bg-opacity-25 border-0 rounded-4 p-4 text-white shadow-sm animate__animated animate__shakeX">
                                 <h6 class="fw-bold mb-1"><i class="bi bi-shield-slash-fill me-2"></i> PERFIL INHABILITADO</h6>
                                 <p class="small opacity-75 mb-0">Su matrícula está suspendida por el Tribunal de Ética.</p>
                             </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card-prestige p-5 border-0 bg-white h-100 shadow-sm" style="border-radius: 40px">
                    <h5 class="fw-bold mb-4">Papeles de <span class="text-primary">Legajo</span></h5>
                    @if($collegiate && $collegiate->is_fully_documented)
                        <div class="alert alert-success rounded-4 d-flex align-items-center gap-3 border-0">
                            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                            <span class="fw-bold small">Documentación completa y aprobada.</span>
                        </div>
                    @else
                        <div class="alert alert-warning rounded-4 d-flex align-items-center gap-3 border-0">
                            <i class="bi bi-exclamation-circle-fill fs-3 text-warning"></i>
                            <span class="fw-bold small">Faltan requisitos por cargar o aprobar.</span>
                        </div>
                        <a href="{{ route('compliance.index') }}" class="btn btn-warning w-100 rounded-pill py-3 fw-bold mt-2">Subir Papeles <i class="bi bi-upload ms-1"></i></a>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="card-prestige p-5 border-0 bg-white h-100 shadow-sm" style="border-radius: 40px">
                    <h5 class="fw-bold mb-4">Pagos de <span class="text-primary">Matrícula</span></h5>
                    @if($collegiate && $collegiate->is_fees_compliant)
                        <div class="alert alert-success rounded-4 d-flex align-items-center gap-3 border-0">
                            <i class="bi bi-wallet2 fs-3 text-success"></i>
                            <span class="fw-bold small">Usted se encuentra al día con sus pagos.</span>
                        </div>
                    @else
                        <div class="alert alert-danger rounded-4 d-flex align-items-center gap-3 border-0">
                            <i class="bi bi-cash-stack fs-3 text-danger"></i>
                            <span class="fw-bold small">Mora detectada. Regularice para habilitarse.</span>
                        </div>
                        <a href="{{ route('payment.index') }}" class="btn btn-dark w-100 rounded-pill py-3 fw-bold mt-2">Pagar Cuota Matricular</a>
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="card-prestige p-5 border-0 bg-white h-100 shadow-sm" style="border-radius: 40px">
                    <h5 class="fw-bold mb-4"><i class="bi bi-file-earmark-text me-2 text-primary"></i> Trámites y <span class="text-primary">Entregables</span></h5>
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <p class="text-muted mb-0 small">Acceda al catálogo de certificados y trámites institucionales disponibles para solicitar online (Ej: Certificado de Ética, Libre Deuda, etc).</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('collegiate.certificates.store') }}" class="btn btn-outline-primary w-100 rounded-pill py-3 fw-bold">Solicitar Trámite <i class="bi bi-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="card-prestige p-5 border-0 bg-white shadow-sm" style="border-radius: 40px">
                    <h5 class="fw-bold mb-4"><i class="bi bi-patch-check me-2 text-primary"></i> Mi Credencial e Habilitación</h5>
                    @if($collegiate && $collegiate->isEnabledForCertificates())
                        <div class="row align-items-center">
                            <div class="col-md-9 mb-4 mb-md-0">
                                <p class="mb-0 text-muted">Su habilitación profesional está vigente y verificada. Puede descargar su certificado oficial con código QR institucional para presentarlo ante obras sociales, ministerios o terceros.</p>
                            </div>
                            <div class="col-md-3 text-md-end">
                                <a href="{{ route('collegiates.certificate', $collegiate) }}" target="_blank" class="btn btn-dark rounded-pill px-4 py-3 fw-bold w-100 shadow-lg">Descargar Certificado <i class="bi bi-download ms-2"></i></a>
                            </div>
                        </div>
                    @else
                        <div class="bg-light p-4 rounded-4 text-center">
                            <p class="m-0 text-muted small fw-bold"><i class="bi bi-lock me-2"></i> Descarga bloqueada hasta regularizar situación (Pagos y Papeles).</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .uppercase { text-transform: uppercase; }
    .shadow-text { text-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    .card-prestige { border-radius: 35px; transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-prestige:hover { transform: translateY(-3px); box-shadow: 0 8px 15px -3px rgba(0,0,0,0.1) !important; }

    /* NETFLIX STYLE */
    .netflix-row {
        display: flex;
        overflow-x: auto;
        gap: 20px;
        padding-bottom: 25px;
        scroll-behavior: smooth;
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
    .netflix-row::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    
    .netflix-card-wrapper {
        flex: 0 0 auto;
        width: 280px;
        cursor: pointer;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .netflix-card-wrapper:hover {
        transform: scale(1.05);
        z-index: 10;
    }
    
    .netflix-card {
        aspect-ratio: 2/3;
        width: 100%;
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .netflix-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2.5rem 1.5rem 1.5rem;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    
    #modalHeaderImage {
        border-bottom: 5px solid var(--bs-primary);
    }
</style>
@endsection

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Consejo de Abogados y Procuradores de La Rioja' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Fuentes elegantes, distinguidas y legibles -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f1d36;   /* Azul Marino Imperial */
            --accent: #c5a880;    /* Bronce / Champagne Premium */
            --accent-gold: #d4af37; /* Oro Fino */
            --light: #faf9f6;     /* Blanco Marfil / Lujo */
            --dark: #0b132b;
            --gray: #64748b;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .playfair {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }

        /* NAVBAR CON EFECTO GLASSMORPHISM DE LUJO */
        .navbar-law {
            background-color: rgba(15, 29, 54, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid var(--accent);
            padding: 1.2rem 0;
            transition: all 0.3s ease;
        }
        .navbar-law .navbar-brand {
            color: #fff !important;
            letter-spacing: 0.5px;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.35rem;
        }
        .navbar-law .navbar-brand span {
            color: var(--accent);
        }
        .navbar-law .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            font-size: 0.95rem;
            margin: 0 0.5rem;
            transition: 0.3s;
        }
        .navbar-law .nav-link:hover {
            color: var(--accent) !important;
            transform: translateY(-2px);
        }
        .btn-law-nav {
            background: linear-gradient(135deg, var(--accent), var(--accent-gold));
            color: #0f1d36 !important;
            font-weight: 700;
            border-radius: 0;
            padding: 12px 30px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(197, 168, 128, 0.25);
            border: none;
        }
        .btn-law-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(197, 168, 128, 0.45);
        }

        /* HERO DINÁMICO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-law {
            height: 90vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            position: relative;
            border-bottom: 3px solid var(--accent);
        }
        .hero-law::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(15, 29, 54, 0.97) 0%, rgba(15, 29, 54, 0.85) 100%);
            z-index: 1;
        }
        .hero-law .container {
            z-index: 2;
            position: relative;
        }
        .hero-law h1 {
            font-size: 4.8rem;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        .hero-law h1 span {
            color: var(--accent);
            position: relative;
            display: inline-block;
        }
        .hero-law p {
            font-size: 1.3rem;
            color: #e2e8f0;
            font-weight: 300;
            max-width: 650px;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }
        
        /* TARJETAS DE VALORES RÁPIDOS (JUS / BONO) */
        .quick-values-container {
            position: absolute;
            bottom: -50px;
            right: 10%;
            z-index: 10;
            display: flex;
            gap: 20px;
        }
        .quick-value-card {
            background: #fff;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-left: 4px solid var(--accent);
            animation: float 6s ease-in-out infinite;
        }
        .quick-value-card:nth-child(2) {
            animation-delay: 1s;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* SECTIONS */
        .section-title {
            font-size: 2.5rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 3rem;
            letter-spacing: -0.5px;
        }
        .section-title span {
            color: var(--accent);
        }

        /* CARDS MODERNAS */
        .card-law {
            background: #fff;
            border: none;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(10, 40, 75, 0.05);
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .card-law::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 4px;
            background: var(--accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .card-law:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(10, 40, 75, 0.1);
        }
        .card-law:hover::before {
            transform: scaleX(1);
        }
        .card-law .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(31, 124, 236, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--accent);
        }
        .card-law h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        /* NEWS REDISEÑADAS */
        .news-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: #fff;
            transition: all 0.3s ease;
        }
        .news-card:hover {
            box-shadow: 0 15px 40px rgba(10, 40, 75, 0.15);
        }
        .news-img {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .news-card:hover .news-img {
            transform: scale(1.05);
        }
        .news-body {
            padding: 2rem;
        }
        .news-badge {
            background: var(--accent);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        /* FOOTER */
        .footer-law {
            background-color: var(--primary);
            color: rgba(255, 255, 255, 0.8);
            padding: 5rem 0 2rem;
            position: relative;
            overflow: hidden;
        }
        .footer-law::before {
            content: '';
            position: absolute;
            top: 0; right: 0; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(31, 124, 236, 0.15) 0%, transparent 70%);
        }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-law fixed-top">
        <div class="container-fluid px-4 px-xl-5">
            <a class="navbar-brand d-flex align-items-center gap-3" href="/">
                <!-- Logo proporcionado por el usuario -->
                <img src="{{ asset('images/tenants/logo-abogados-redondo.png') }}" alt="Logo Consejo Abogados La Rioja" style="height: 55px; border-radius: 50%;">
                <div class="d-none d-sm-block lh-1">
                    <span class="d-block" style="font-size: 1.1rem;">Consejo de Abogados y Procuradores</span>
                    <span class="d-block text-white-50" style="font-size: 0.8rem; font-weight:400;">de la Provincia de La Rioja</span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#lawNav">
                <span class="material-icons text-white">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="lawNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#institucion">El Consejo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servicios">Servicios</a></li>
                    <li class="nav-item"><a class="nav-link" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link" href="#autoridades">Institutos</a></li>
                </ul>
                <div class="ms-auto d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-law-nav">Panel Matriculados</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-law" style="background-image: url('{{ $bgImage }}');">
        <div class="container-fluid px-4 px-xl-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="badge bg-transparent text-uppercase tracking-widest px-0 mb-3 fw-bold d-inline-flex align-items-center gap-2" style="color: var(--accent); font-size: 0.85rem; border-bottom: 2px solid var(--accent);">
                        Consejo de Abogados y Procuradores de La Rioja
                    </div>
                    <h1 class="playfair">Defendiendo el<br>ejercicio <span>profesional.</span></h1>
                    <p>Órgano oficial que rige la matrícula, defiende los derechos de los profesionales del derecho y promueve la excelencia en el ejercicio de la abogacía en toda la provincia.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="#servicios" class="btn btn-law-nav">Explorar Servicios</a>
                        <a href="#contacto" class="btn btn-outline-light rounded-0 px-4 py-3 text-uppercase tracking-wider fw-bold">Contacto Institucional</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarjetas flotantes con valores actualizados en estilo oro/bronce -->
        <div class="quick-values-container d-none d-lg-flex">
            <div class="quick-value-card" style="border-left-color: var(--accent-gold); background: var(--light); border: 1px solid rgba(197, 168, 128, 0.2);">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Valor JUS Actualizado</div>
                <h3 class="playfair mb-0" style="color: var(--primary); font-weight: 700;">$39.580,99</h3>
                <div class="small mt-1" style="color: var(--accent-gold);"><i class="material-icons align-middle" style="font-size:14px;">info</i> Última act: 01/03/26</div>
            </div>
            <div class="quick-value-card" style="border-left-color: var(--accent-gold); background: var(--light); border: 1px solid rgba(197, 168, 128, 0.2);">
                <div class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 1px;">Bono Profesional</div>
                <h3 class="playfair mb-0" style="color: var(--primary); font-weight: 700;">$25.000</h3>
                <div class="text-muted small mt-1"><i class="material-icons align-middle" style="font-size:14px;">info</i> Act: 01/01/25</div>
            </div>
        </div>
    </section>

    <main>
        
        <!-- INSTITUCIONAL -->
        <section id="institucion" class="py-5 mt-5">
            <div class="container-fluid px-4 px-xl-5 pt-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5">
                        <div class="position-relative">
                            <!-- Imagen de la balanza de la justicia sin texto en inglés -->
                            <img src="https://images.unsplash.com/photo-1505664159623-2a1eb110bf52?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Justicia" class="img-fluid rounded-4 shadow-lg" style="object-fit: cover; height: 500px;">
                            <div class="position-absolute bg-white p-4 shadow-lg rounded-4" style="bottom: 30px; right: -40px; max-width: 280px; border-top: 4px solid var(--accent);">
                                <h5 class="outfit-font text-primary mb-2">Comisión Directiva</h5>
                                <p class="text-muted small mb-0">Renovación de Autoridades del Instituto de Derecho Registral para el período 2026–2028.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <span class="badge bg-light text-primary border px-3 py-2 rounded-pill mb-3 fw-bold">Institucional</span>
                        <h2 class="outfit-font mb-4" style="color: var(--primary); font-size: 2.5rem;">Nuestro Compromiso con la <span>Abogacía</span></h2>
                        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                            Representamos a los profesionales del derecho de la provincia, brindando respaldo institucional, formación continua y defensa férrea de nuestras incumbencias laborales.
                        </p>
                        <p class="text-muted" style="line-height: 1.8;">
                            Recientemente manifestamos nuestro <strong>amplio rechazo institucional a la reforma laboral</strong>, actuando en conjunto con entidades de la abogacía argentina para proteger los derechos de nuestros representados.
                        </p>
                        <ul class="list-unstyled mt-4">
                            <li class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                                <div style="width:40px; height:40px; background:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
                                    <i class="material-icons text-white">how_to_reg</i>
                                </div>
                                <span class="fw-bold text-primary">Gobierno de la Matrícula Profesional</span>
                            </li>
                            <li class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                                <div style="width:40px; height:40px; background:var(--accent); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:15px;">
                                    <i class="material-icons text-white">gavel</i>
                                </div>
                                <span class="fw-bold text-primary">Tribunal de Ética y Disciplina</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICIOS -->
        <section id="servicios" class="bg-light py-5 mt-5">
            <div class="container-fluid px-4 px-xl-5 py-5">
                <div class="text-center mb-5">
                    <h2 class="section-title">Servicios al <span>Matriculado</span></h2>
                    <p class="text-muted max-w-700 mx-auto">Ponemos a disposición herramientas digitales y presenciales para facilitar el ejercicio diario de la profesión.</p>
                </div>
                
                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="card-law card-law-magic" data-bs-toggle="modal" data-bs-target="#modalMatricula">
                            <div class="icon-wrapper">
                                <i class="material-icons fs-1">payments</i>
                            </div>
                            <h4 class="outfit-font">Pago de Bonos</h4>
                            <p class="text-muted small">Adquisición y pago de bonos profesionales de manera 100% digital a través del panel de matriculados.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-law card-law-magic" data-bs-toggle="modal" data-bs-target="#modalTramites">
                            <div class="icon-wrapper">
                                <i class="material-icons fs-1">school</i>
                            </div>
                            <h4 class="outfit-font">Institutos de Derecho</h4>
                            <p class="text-muted small">Capacitación continua, talleres y conferencias (ej. Régimen Penal Juvenil, Derecho Registral).</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-law card-law-magic" data-bs-toggle="modal" data-bs-target="#modalAbogados">
                            <div class="icon-wrapper">
                                <i class="material-icons fs-1">contact_page</i>
                            </div>
                            <h4 class="outfit-font">Padrón de Colegiados</h4>
                            <p class="text-muted small">Buscador oficial y actualizado de profesionales habilitados para ejercer la abogacía en La Rioja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOTICIAS (Tomadas de la página real) -->
        <section id="novedades" class="py-5 my-5">
            <div class="container-fluid px-4 px-xl-5">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <div>
                        <span class="text-accent fw-bold text-uppercase tracking-wider small" style="color: var(--accent);">Actualidad</span>
                        <h2 class="outfit-font mb-0" style="color: var(--primary); font-size: 2.5rem;">Últimas <span>Novedades</span></h2>
                    </div>
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 border-2 d-none d-md-block" style="color: var(--primary); border-color: var(--primary);">Ver Historial</a>
                </div>
                
                <div class="row g-4">
                    <!-- Noticia 1 -->
                    <div class="col-md-4">
                        <div class="news-card h-100 d-flex flex-column">
                            <div class="overflow-hidden position-relative">
                                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="news-img w-100" alt="Conferencia">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="news-badge bg-white text-primary">Capacitación</span>
                                </div>
                            </div>
                            <div class="news-body flex-grow-1 bg-white">
                                <h4 class="outfit-font mt-2 mb-3" style="font-size: 1.2rem; font-weight:600;">Conferencia | Régimen Penal Juvenil</h4>
                                <p class="text-muted small mb-4">Importante convocatoria del Consejo Profesional de Abogados en el Salón Auditorio del Tribunal Superior de Justicia, donde se desarrolló el debate sobre el Régimen Penal Juvenil.</p>
                                <a href="#" class="text-decoration-none mt-auto d-flex align-items-center" style="color: var(--accent); font-weight: 600; font-size: 0.9rem;">Leer Artículo <i class="material-icons fs-6 ms-1">arrow_forward</i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Noticia 2 -->
                    <div class="col-md-4">
                        <div class="news-card h-100 d-flex flex-column">
                            <div class="overflow-hidden position-relative">
                                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="news-img w-100" alt="Reforma Laboral">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="news-badge bg-white text-danger">Comunicado Oficial</span>
                                </div>
                            </div>
                            <div class="news-body flex-grow-1 bg-white">
                                <h4 class="outfit-font mt-2 mb-3" style="font-size: 1.2rem; font-weight:600;">Rechazo institucional a la reforma laboral</h4>
                                <p class="text-muted small mb-4">Diversas instituciones y asociaciones representativas de la abogacía argentina han manifestado públicamente su rechazo a las medidas impulsadas por el Ejecutivo Nacional.</p>
                                <a href="#" class="text-decoration-none mt-auto d-flex align-items-center" style="color: var(--accent); font-weight: 600; font-size: 0.9rem;">Leer Artículo <i class="material-icons fs-6 ms-1">arrow_forward</i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Noticia 3 -->
                    <div class="col-md-4">
                        <div class="news-card h-100 d-flex flex-column">
                            <div class="overflow-hidden position-relative">
                                <img src="https://images.unsplash.com/photo-1436450412740-6b988f486c6b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" class="news-img w-100" alt="Institutos">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="news-badge bg-white text-primary">Institucional</span>
                                </div>
                            </div>
                            <div class="news-body flex-grow-1 bg-white">
                                <h4 class="outfit-font mt-2 mb-3" style="font-size: 1.2rem; font-weight:600;">Renovación del Instituto de Derecho Registral</h4>
                                <p class="text-muted small mb-4">El Instituto de Derecho Registral llevó adelante el proceso de renovación de sus autoridades para el período 2026–2028, reafirmando el compromiso con la especialización.</p>
                                <a href="#" class="text-decoration-none mt-auto d-flex align-items-center" style="color: var(--accent); font-weight: 600; font-size: 0.9rem;">Leer Artículo <i class="material-icons fs-6 ms-1">arrow_forward</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4 d-md-none">
                    <a href="{{ route('news.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 border-2 w-100">Ver Historial de Noticias</a>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER -->
    <footer id="contacto" class="footer-law">
        <div class="container-fluid px-4 px-xl-5 position-relative z-2">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="d-flex align-items-center gap-3 mb-4 text-decoration-none" href="/">
                        <img src="{{ asset('images/tenants/logo-abogados-redondo.png') }}" alt="Logo" style="height: 60px; border-radius: 50%;">
                        <span class="text-white h5 mb-0 outfit-font">{{ $school->name ?? 'Consejo de Abogados' }}</span>
                    </a>
                    <p class="small text-white-50">Garantizando el libre ejercicio de la profesión, la defensa del estado de derecho y la administración de justicia en la Provincia de La Rioja.</p>
                    <div class="d-flex gap-2 mt-4">
                        <!-- Redes Sociales indicadas en la web -->
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:35px; height:35px; display:flex; align-items:center; justify-content:center;"><i class="material-icons" style="font-size:16px;">facebook</i></a>
                        <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:35px; height:35px; display:flex; align-items:center; justify-content:center;"><i class="material-icons" style="font-size:16px;">photo_camera</i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4 outfit-font">Contacto Institucional</h5>
                    <ul class="list-unstyled mt-4 text-white-50">
                        <li class="mb-3 d-flex"><i class="material-icons me-3" style="color: var(--accent);">location_on</i> San Martín 118, 1° Piso<br>La Rioja, Capital</li>
                        <li class="mb-3 d-flex"><i class="material-icons me-3" style="color: var(--accent);">phone</i> (0380) 442-1234</li>
                        <li class="mb-3 d-flex"><i class="material-icons me-3" style="color: var(--accent);">email</i> contacto@consejodeabogadoslr.com.ar</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4 outfit-font">Enlaces de Interés</h5>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-2"><a href="#institucion" class="text-white-50 text-decoration-none hover-white">El Consejo</a></li>
                        <li class="mb-2"><a href="#servicios" class="text-white-50 text-decoration-none hover-white">Servicios Digitales</a></li>
                        <li class="mb-2"><a href="https://tsj.gov.ar" target="_blank" class="text-white-50 text-decoration-none hover-white">Tribunal Superior de Justicia</a></li>
                        <li class="mb-2 mt-4"><a href="{{ route('login') }}" class="btn btn-sm btn-accent text-white px-4 py-2 rounded-pill" style="background:var(--accent);">Ingreso Sistema</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; {{ date('Y') }} Consejo Profesional de Abogados y Procuradores de La Rioja.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <p class="small mb-0 text-white-50">Desarrollado por <a href="#" class="text-white text-decoration-none fw-bold">Gente Piola</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chatbot Widget -->
    <div id="chatbot-widget" class="position-fixed" style="bottom: 120px; right: 25px; z-index: 1050; width: 400px; height: 550px; display: none; resize: both; overflow: hidden; min-width: 300px; min-height: 400px; max-width: 90vw; max-height: 90vh; background: transparent;">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; overflow: hidden; display: flex; flex-direction: column;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3" id="chatbot-header" style="cursor: move;">
                <div class="fw-bold d-flex align-items-center">
                    <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" class="me-2 shadow-sm" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; pointer-events: none;">
                    Asistente Virtual
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
            </div>
            <div class="card-body bg-light flex-grow-1" id="chatbot-messages" style="overflow-y: auto;">
                <div class="d-flex mb-3">
                    <div class="bg-white text-dark p-3 rounded-4 shadow-sm" style="max-width: 85%;">
                        Hola 👋 Soy el asistente virtual del {{ $school->name ?? 'Consejo' }}. ¿En qué te puedo ayudar hoy?
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <form id="chatbot-form" class="d-flex gap-2" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatbot-input" class="form-control rounded-pill bg-light border-0 px-3" placeholder="Escribe tu consulta..." required>
                    <button type="submit" class="btn btn-primary rounded-circle" style="width: 40px; height: 40px; border:none; background-color:var(--accent);">
                        <i class="material-icons text-white fs-5" style="line-height:40px; display:block; text-align:center;">send</i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Botón Flotante Chatbot -->
    <button id="chatbot-trigger" class="btn btn-light border border-2 border-primary rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center p-0" style="bottom: 25px; right: 25px; z-index: 1040; width: 95px; height: 95px; background-color: white !important; overflow: hidden; border-color: var(--accent) !important;" onclick="toggleChatbot()">
        <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" style="width: 100%; height: 100%; object-fit: cover;">
    </button>

    <!-- MODALES INTERACTIVOS DE ABOGADOS -->
    <!-- Modal 1: Padrón de Abogados -->
    <div class="modal fade" id="modalAbogados" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0" style="background-color: var(--light);">
                    <h5 class="modal-title fw-bold outfit-font text-dark"><i class="material-icons align-middle me-2" style="color: var(--accent);">people</i> Padrón de Abogados Matriculados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="input-group mb-4 shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="material-icons text-muted">search</i></span>
                        <input type="text" class="form-control border-start-0 ps-0 py-2" id="searchAbogados" placeholder="Buscar por nombre, matrícula o DNI...">
                    </div>
                    <div class="list-group list-group-flush rounded-3 border shadow-sm" id="listAbogados">
                        @if(isset($collegiates))
                            @foreach($collegiates as $colegiado)
                                <div class="list-group-item list-group-item-action p-3 abogado-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold t-name">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                            <div class="small text-muted">
                                                <span class="me-3 t-mat"><i class="material-icons align-middle fs-6 me-1">badge</i> MP: {{ $colegiado->registration_number }}</span>
                                                <span class="t-dni"><i class="material-icons align-middle fs-6 me-1">contact_mail</i> DNI: {{ $colegiado->dni }}</span>
                                            </div>
                                        </div>
                                        @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                            <span class="badge bg-success rounded-pill">Activo</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">{{ $colegiado->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Distribución Departamental en Padrón -->
                    <h5 class="outfit-font text-dark mt-5 mb-4"><i class="material-icons align-middle me-2" style="color: var(--accent);">map</i> Distribución por Departamentos</h5>
                    <div class="accordion accordion-flush border rounded-4 overflow-hidden" id="accordionDeptos">
                        @php
                            $departamentos = ['Capital', 'Chilecito', 'Arauco', 'Chamical', 'Famatina', 'General Belgrano', 'General Juan Facundo Quiroga', 'General Lamadrid', 'General Ocampo', 'General San Martin', 'Independencia', 'Rosario Vera Penaloza', 'San Blas de los Sauces', 'Sanagasta', 'Vinchina', 'Castro Barros', 'Felipe Varela'];
                        @endphp
                        @foreach($departamentos as $index => $depto)
                            @php
                                $deptCollegiates = isset($collegiates) ? $collegiates->where('city', $depto) : collect();
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#depto{{ $index }}" aria-expanded="false" aria-controls="depto{{ $index }}">
                                        {{ $depto }}
                                        <span class="badge ms-2 rounded-pill text-white" style="background-color: var(--accent);">{{ $deptCollegiates->count() }}</span>
                                    </button>
                                </h2>
                                <div id="depto{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}">
                                    <div class="accordion-body bg-light p-0">
                                        @if($deptCollegiates->count() > 0)
                                            <div class="list-group list-group-flush">
                                                @foreach($deptCollegiates as $colegiado)
                                                    <div class="list-group-item bg-transparent p-3 border-bottom">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                 <h6 class="mb-1 fw-bold">{{ $colegiado->last_name }}, {{ $colegiado->first_name }}</h6>
                                                                 <div class="small text-muted">
                                                                     <span class="me-3"><i class="material-icons align-middle fs-6 me-1">badge</i> MP: {{ $colegiado->registration_number }}</span>
                                                                     <span><i class="material-icons align-middle fs-6 me-1">contact_mail</i> DNI: {{ $colegiado->dni }}</span>
                                                                 </div>
                                                            </div>
                                                            @if(strtolower($colegiado->status) == 'active' || strtolower($colegiado->status) == 'activo')
                                                                <span class="badge bg-success rounded-pill">Activo</span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill">{{ $colegiado->status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4 small">
                                                <i class="material-icons fs-4 d-block mb-2" style="color: var(--accent);">info</i> 
                                                No hay profesionales registrados en este departamento.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales Explicativos de Tarjetas de Servicios -->
    <div class="modal fade" id="modalMatricula" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold outfit-font"><i class="material-icons align-middle me-2" style="color: var(--accent);">payments</i> Pago de Bonos y Matrícula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>El pago de bonos profesionales y cuotas mensuales puede realizarse en línea con tarjetas de crédito/débito a través del Portal de Colegiados. Los valores vigentes son:</p>
                    <ul>
                        <li><strong>Valor JUS:</strong> $39.580,99 (Última actualización: 01/03/2026)</li>
                        <li><strong>Bono Profesional Ley 4832:</strong> $25.000 (Actualización: 01/01/2025)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTramites" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold outfit-font"><i class="material-icons align-middle me-2" style="color: var(--accent);">school</i> Institutos de Derecho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>El Consejo de Abogados cuenta con diversos institutos de especialización para el desarrollo y debate profesional. Próximos eventos y comisiones:</p>
                    <ul>
                        <li><strong>Instituto de Derecho Registral:</strong> Reunión de autoridades (Período 2026–2028).</li>
                        <li><strong>Taller Régimen Penal Juvenil:</strong> Debate y análisis normativo.</li>
                        <li><strong>Área Académica:</strong> Charlas y seminarios semanales para noveles abogados.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Chatbot & Drag -->
    <script>
        const chatbotWidget = document.getElementById('chatbot-widget');

        function toggleChatbot() {
            if (chatbotWidget.style.display === 'none' || chatbotWidget.style.display === '') {
                chatbotWidget.style.display = 'block';
            } else {
                chatbotWidget.style.display = 'none';
            }
        }
        
        // Draggable logic
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;

        const header = document.getElementById("chatbot-header");

        header.addEventListener("mousedown", dragStart);
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("mousemove", drag);

        function dragStart(e) {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            if (e.target === header || e.target.parentNode === header) {
                isDragging = true;
            }
        }

        function dragEnd(e) {
            initialX = currentX;
            initialY = currentY;
            isDragging = false;
        }

        function drag(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;
                setTranslate(currentX, currentY, chatbotWidget);
            }
        }

        function setTranslate(xPos, yPos, el) {
            el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatbot-messages');
            
            // Append user message
            messagesDiv.innerHTML += `
                <div class="d-flex mb-3 justify-content-end">
                    <div class="bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 85%;">${message}</div>
                </div>
            `;
            
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Fetch response
            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: message, school_id: '{{ $school->id ?? 1 }}' })
                });

                const data = await response.json();
                
                // Append bot message
                messagesDiv.innerHTML += `
                    <div class="d-flex mb-3">
                        <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 85%;">${data.answer}</div>
                    </div>
                `;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>

    <!-- Buscador en vivo de Abogados -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchAbogados');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const items = document.querySelectorAll('.abogado-item');
                    
                    items.forEach(item => {
                        const name = item.querySelector('.t-name').textContent.toLowerCase();
                        const mat = item.querySelector('.t-mat').textContent.toLowerCase();
                        const dni = item.querySelector('.t-dni').textContent.toLowerCase();
                        
                        if(name.includes(value) || mat.includes(value) || dni.includes(value)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
    <style>
        .hover-white:hover { color: #fff !important; }
        .card-law-magic {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .card-law-magic:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(31, 124, 236, 0.15);
            border-color: var(--accent) !important;
        }
    </style>
</body>
</html>

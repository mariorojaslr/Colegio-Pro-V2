<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consejo Profesional de Trabajo Social La Rioja - Argentina</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --ts-blue: #1E3A8A;
            --ts-red: #DC2626;
            --ts-light: #F3F4F6;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
        }

        .top-bar {
            background-color: var(--ts-light);
            padding: 10px 0;
            border-bottom: 3px solid var(--ts-red);
        }

        .header-custom {
            padding: 20px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .nav-link-custom {
            color: var(--ts-blue);
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 10px;
        }

        .hero-ts {
            background: linear-gradient(135deg, var(--ts-blue) 0%, rgba(30, 58, 138, 0.8) 100%), url('https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            border-bottom: 5px solid var(--ts-red);
        }

        .card-ts {
            border: none;
            border-top: 4px solid var(--ts-blue);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 8px;
        }

        .footer-ts {
            background-color: var(--ts-blue);
            color: white;
            padding: 40px 0;
            border-top: 5px solid var(--ts-red);
        }
    </style>
</head>
<body>

    <!-- TOP BAR (DATOS DE CONTACTO EXACTOS DEL CLIENTE) -->
    <div class="top-bar text-center text-md-start">
        <div class="container d-flex flex-wrap justify-content-center justify-content-md-between align-items-center">
            <div class="small fw-bold" style="color: var(--ts-blue);">
                <span class="material-icons" style="font-size: 14px; vertical-align: middle;">location_on</span>
                San Martín N° 117 - Edificio Federación piso 8° "D"
            </div>
            <div class="small fw-bold" style="color: var(--ts-blue);">
                <span class="material-icons" style="font-size: 14px; vertical-align: middle;">phone</span> 0380-4242904
                <span class="material-icons ms-3" style="font-size: 14px; vertical-align: middle;">email</span> cptslar@yahoo.com.ar | cptslar@gmail.com
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header class="header-custom bg-white sticky-top">
        <div class="container d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <!-- Espacio para el logo que proporcionaron -->
                <div class="rounded-circle border border-2 border-danger d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: #fff; overflow:hidden;">
                    <span class="material-icons text-primary fs-1">groups</span>
                </div>
                <div>
                    <h2 class="m-0 fw-bold" style="color: var(--ts-blue); font-size: 1.5rem;">Consejo Profesional de Trabajo Social</h2>
                    <h3 class="m-0 fw-bold" style="color: var(--ts-red); font-size: 1.2rem;">La Rioja - Argentina</h3>
                </div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-danger fw-bold px-4 rounded-pill">Ingresar al Sistema</a>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero-ts">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Compromiso con la Profesión y la Sociedad</h1>
            <p class="lead mb-0">Órgano rector del ejercicio profesional del Trabajo Social en la provincia de La Rioja.</p>
        </div>
    </section>

    <!-- SECCIONES -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card card-ts p-4 h-100">
                        <h4 class="fw-bold" style="color: var(--ts-blue);">Sobre Nosotros</h4>
                        <p class="text-muted">El Consejo Profesional de Trabajo Social de La Rioja tiene como misión velar por el cumplimiento de las normas éticas de la profesión, defender los derechos de los matriculados y promover la capacitación continua.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-ts p-4 h-100">
                        <h4 class="fw-bold" style="color: var(--ts-blue);">Trámites Frecuentes</h4>
                        <ul class="text-muted">
                            <li>Solicitud de Matriculación</li>
                            <li>Pago de Cuotas y Certificados</li>
                            <li>Denuncias al Tribunal de Ética</li>
                            <li>Inscripción a Capacitaciones</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-ts">
        <div class="container text-center">
            <h5 class="fw-bold mb-3">Consejo Profesional de Trabajo Social La Rioja - Argentina</h5>
            <p class="small mb-1">San Martín N° 117 - Edificio Federación piso 8° "D"</p>
            <p class="small mb-0">Tel: 0380-4242904 | Emails: cptslar@yahoo.com.ar / cptslar@gmail.com</p>
        </div>
    </footer>

</body>
</html>

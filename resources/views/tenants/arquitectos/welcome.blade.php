<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Colegio de Arquitectos' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Fuente moderna y geométrica para arquitectos -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #000000; /* Black */
            --dark: #1f2937; /* Gray 800 */
            --light: #f3f4f6; /* Gray 100 */
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--dark);
            background-color: #fff;
        }

        .hero-arq {
            height: 100vh;
            background-image: linear-gradient(rgba(31, 41, 55, 0.7), rgba(31, 41, 55, 0.7)), url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .title-arq {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #fff;
            font-size: 3.5rem;
        }

        .btn-arq {
            border: 2px solid var(--primary);
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            padding: 15px 40px;
            transition: all 0.3s;
            background: transparent;
        }

        .btn-arq:hover {
            background: var(--primary);
            color: #fff;
        }

        .section-title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 3px;
            position: relative;
            display: inline-block;
            margin-bottom: 3rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: var(--primary);
            bottom: -10px;
            left: 25%;
        }

        .card-arq {
            border: none;
            background: var(--light);
            border-radius: 0;
            transition: transform 0.3s;
        }
        .card-arq:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- NAVBAR MINIMALISTA -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-uppercase" href="/" style="letter-spacing: 2px;">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 40px;">
                @else
                    CA | {{ $school->name ?? 'Colegio' }}
                @endif
            </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-dark rounded-0 px-4 text-uppercase" style="letter-spacing: 1px;">Ingreso Matriculados</a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-arq">
        <div class="container">
            <h1 class="title-arq mb-4">{{ $school->name ?? 'Colegio de Arquitectos' }}</h1>
            <p class="text-white mb-5 fs-4 fw-light">Diseñando el futuro, preservando nuestro patrimonio.</p>
            <a href="#servicios" class="btn-arq text-decoration-none">Nuestros Servicios</a>
        </div>
    </section>

    <!-- INFO -->
    <section id="servicios" class="py-5 bg-white">
        <div class="container py-5 text-center">
            <h2 class="section-title">El Colegio</h2>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card-arq p-5 h-100 text-start">
                        <span class="material-icons fs-1 mb-3 text-warning">architecture</span>
                        <h4 class="fw-bold text-uppercase">Visado de Planos</h4>
                        <p class="text-muted">Gestión digital de expedientes y visados para obras nuevas y remodelaciones.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-arq p-5 h-100 text-start bg-dark text-white">
                        <span class="material-icons fs-1 mb-3 text-warning">gavel</span>
                        <h4 class="fw-bold text-uppercase">Legales y Ética</h4>
                        <p class="text-white-50">Regulación del ejercicio profesional y defensa de las incumbencias.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-arq p-5 h-100 text-start">
                        <span class="material-icons fs-1 mb-3 text-warning">library_books</span>
                        <h4 class="fw-bold text-uppercase">Capacitación</h4>
                        <p class="text-muted">Cursos de actualización, BIM, normativas sustentables y más.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white py-5">
        <div class="container text-center">
            <h5 class="text-uppercase letter-spacing-2 mb-2">{{ $school->name ?? 'CAPLaR - Colegio Arquitectos de La Rioja' }}</h5>
            <p class="text-white-50 mb-4 small">San Nicolas de Bari (O) N° 1.138, La Rioja, Argentina 5300</p>
            <p class="text-white-50 mb-0">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>

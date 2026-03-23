<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Colegio-Pro</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('media/favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --accent: #d4af37;
            --bg-dark: #0f172a;
            --font-main: 'Inter', sans-serif;
            --font-headings: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: #f8fafc;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            display: flex;
        }

        /* SPLIT SCREEN DESIGN */
        .auth-container {
            display: flex;
            width: 100%;
            height: 100vh;
            flex-direction: row-reverse; /* Formulario a la izquierda en registro para variar */
        }

        /* Lado Izquierdo: Branding & Visuals (Solo visible en Desktop) */
        .auth-branding {
            flex: 1;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            color: white;
            position: relative;
        }

        @media (max-width: 991.98px) {
            .auth-branding { display: none; }
        }

        /* Lado Derecho: Formulario Estilizado */
        .auth-form-wrapper {
            flex: 0 0 550px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            box-shadow: 10px 0 30px rgba(0,0,0,0.05); /* Sombra hacia el otro lado */
            z-index: 10;
            overflow-y: auto;
        }

        @media (max-width: 991.98px) {
            .auth-form-wrapper { flex: 1; padding: 40px 30px; }
        }

        .auth-logo {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .auth-logo span {
            font-family: var(--font-headings);
            font-weight: 900;
            font-size: 1.5rem;
            color: var(--bg-dark);
            letter-spacing: -1px;
        }
        
        .auth-logo .accent { color: var(--primary); }

        .auth-header h1 {
            font-family: var(--font-headings);
            font-weight: 800;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
            color: var(--bg-dark);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #64748b;
            font-weight: 400;
            margin-bottom: 30px;
        }

        /* INPUTS ESTILO ROLLS-ROYCE */
        .form-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--bg-dark);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: white;
        }

        .btn-auth {
            background: var(--bg-dark);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            background: #1e293b;
        }

        .auth-footer-links {
            margin-top: 30px;
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
        }

        .auth-footer-links a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        /* BRANDING ELEMENTS */
        .branding-text h2 {
            font-family: var(--font-headings);
            font-weight: 900;
            font-size: 3.5rem;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .branding-text p {
            font-size: 1.15rem;
            font-weight: 300;
            opacity: 0.8;
            max-width: 500px;
        }

        .branding-badge {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            padding: 15px 25px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 15px;
            margin-top: 40px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .badge-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .invalid-feedback {
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Lado Izquierdo: Branding & Visuals (Desktop) -->
        <div class="auth-branding animate__animated animate__fadeIn">
            <div>
                <a href="/" class="auth-logo">
                    <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="40" class="me-3" style="filter: brightness(0) invert(1);">
                    <span class="text-white">COLEGIO<span class="accent text-white-50"> PRO</span></span>
                </a>
            </div>

            <div class="branding-text">
                <h2 class="animate__animated animate__fadeInUp animate__delay-1s">Únase a la Excelencia Institucional</h2>
                <p class="animate__animated animate__fadeInUp animate__delay-2s">Sea parte de la plataforma más robusta para el crecimiento profesional y la autogestión de élite.</p>
                
                <div class="branding-badge animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="badge-icon"><i class="bi bi-rocket-takeoff"></i></div>
                    <div>
                        <div class="fw-bold small">Planes de Carrera</div>
                        <div class="x-small opacity-75">Potenciando su futuro profesional</div>
                    </div>
                </div>
            </div>

            <div>
                <p class="x-small opacity-50 mb-0">© 2026 Colegio-Pro. Experiencia Rolls-Royce en Software.</p>
            </div>
        </div>

        <!-- Lado Derecho: Formulario de Registro -->
        <div class="auth-form-wrapper animate__animated animate__fadeInLeft">
            <div>
                <div class="auth-header">
                    <h1>Crear Cuenta</h1>
                    <p>Inicie su jornada profesional registrándose en nuestra plataforma.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre Completo</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Escriba su nombre y apellido">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Institucional</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email@profesional.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password-confirm" class="form-label">Repetir Contraseña</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="x-small text-muted mb-4" style="font-size: 0.75rem;">
                            Al registrarme, acepto los <a href="#" class="text-primary text-decoration-none fw-bold">Términos del Servicio</a> y la <a href="#" class="text-primary text-decoration-none fw-bold">Política de Privacidad</a>.
                        </p>
                    </div>

                    <button type="submit" class="btn-auth">
                        FINALIZAR REGISTRO <i class="bi bi-check-circle ms-2"></i>
                    </button>
                </form>

                <div class="auth-footer-links">
                    ¿Ya tiene una cuenta? 
                    <a href="{{ route('login') }}">Inicie sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

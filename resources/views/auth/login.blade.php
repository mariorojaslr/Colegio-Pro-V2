<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Colegio-Pro</title>
    
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
        }

        /* Lado Izquierdo: Branding & Visuals (Solo visible en Desktop) */
        .auth-branding {
            flex: 1;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=2070&auto=format&fit=crop');
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
            flex: 0 0 500px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            z-index: 10;
        }

        @media (max-width: 991.98px) {
            .auth-form-wrapper { flex: 1; padding: 40px 30px; }
        }

        .auth-logo {
            margin-bottom: 40px;
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
            font-size: 2rem;
            letter-spacing: -0.5px;
            color: var(--bg-dark);
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #64748b;
            font-weight: 400;
            margin-bottom: 40px;
        }

        /* INPUTS ESTILO ROLLS-ROYCE */
        .form-label {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #f1f5f9;
            border-radius: 12px;
            padding: 14px 18px;
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
            position: relative;
            overflow: hidden;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            background: #1e293b;
        }

        .btn-auth:active { transform: translateY(0); }

        .auth-footer-links {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9rem;
            color: #64748b;
        }

        .auth-footer-links a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-footer-links a:hover { text-decoration: underline; }

        /* BRANDING ELEMENTS */
        .branding-text h2 {
            font-family: var(--font-headings);
            font-weight: 900;
            font-size: 3rem;
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

        /* CUSTOM CHECKBOX */
        .custom-check {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
        }

        .invalid-feedback {
            font-size: 0.75rem;
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
                <h2 class="animate__animated animate__fadeInUp animate__delay-1s">Gestión de Élite para Colegios Profesionales</h2>
                <p class="animate__animated animate__fadeInUp animate__delay-2s">Digitalizamos el prestigio de su institución con herramientas de última generación y diseño sofisticado.</p>
                
                <div class="branding-badge animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="badge-icon"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <div class="fw-bold small">Seguridad Garantizada</div>
                        <div class="x-small opacity-75">Encriptación de grado bancario</div>
                    </div>
                </div>
            </div>

            <div>
                <p class="x-small opacity-50 mb-0">© 2026 Colegio-Pro. Experiencia Rolls-Royce en Software.</p>
            </div>
        </div>

        <!-- Lado Derecho: Formulario -->
        <div class="auth-form-wrapper animate__animated animate__fadeInRight">
            <div>
                <div class="auth-header">
                    <h1>Bienvenido</h1>
                    <p>Por favor, introduzca sus credenciales para acceder a la plataforma.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none x-small fw-bold text-primary mb-2">¿Olvidó su contraseña?</a>
                            @endif
                        </div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label custom-check" for="remember">
                                Mantener mi sesión iniciada
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        INICIAR SESIÓN <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="auth-footer-links">
                    ¿Aún no es cliente? 
                    <a href="{{ route('demo.register') }}">Solicitar Demo</a>
                </div>
                
                {{-- Opcional: Link a Registro si estuviera habilitado libremente --}}
                @if (Route::has('register'))
                <div class="auth-footer-links mt-2">
                    Si eres un colegiado: 
                    <a href="{{ route('register') }}">Regístrate aquí</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

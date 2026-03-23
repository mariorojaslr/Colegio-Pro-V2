<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Colegio-Pro</title>
    
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

        .auth-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .auth-branding {
            flex: 1;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95)), url('https://images.unsplash.com/photo-1589216532372-1c2a367900d9?q=80&w=2071&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            color: white;
        }

        @media (max-width: 991.98px) {
            .auth-branding { display: none; }
        }

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
        }
        
        .auth-header h1 {
            font-family: var(--font-headings);
            font-weight: 800;
            font-size: 2rem;
            color: var(--bg-dark);
            margin-bottom: 10px;
        }

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
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-auth {
            background: var(--bg-dark);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 700;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }

        .auth-footer-links {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #64748b;
        }

        .auth-footer-links a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-branding animate__animated animate__fadeIn">
            <div>
                <a href="/" class="auth-logo">
                    <img src="{{ asset('media/logo.png') }}" alt="Colegio-Pro" height="40" style="filter: brightness(0) invert(1);">
                    <span class="text-white ms-3">COLEGIO<span class="text-white-50"> PRO</span></span>
                </a>
            </div>
            <div>
                <h2 class="fw-black display-5 mb-3">La Seguridad es nuestra Prioridad</h2>
                <p class="opacity-75 fs-5">Protegemos su acceso con los más altos estándares de encriptación y seguridad digital.</p>
            </div>
            <div>
                <p class="x-small opacity-50 mb-0">© 2026 Colegio-Pro. Experiencia Profesional.</p>
            </div>
        </div>

        <div class="auth-form-wrapper animate__animated animate__fadeInRight">
            <div>
                <div class="auth-header text-center">
                    <h1>Recuperar Acceso</h1>
                    <p>Le enviaremos un enlace de restablecimiento a su correo institucional.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success border-0 rounded-4 p-3 animate__animated animate__pulse mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@ejemplo.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-auth">
                        ENVIAR VÍNCULO DE ACCESO <i class="bi bi-envelope-at ms-2"></i>
                    </button>
                </form>

                <div class="auth-footer-links">
                    ¿Recordó su contraseña? 
                    <a href="{{ route('login') }}">Volver al inicio</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

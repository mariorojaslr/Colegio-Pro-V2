@if(isset($currentTenant) && view()->exists('tenants.' . $currentTenant->slug . '.auth.register'))
    @include('tenants.' . $currentTenant->slug . '.auth.register')
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - {{ $currentTenant->name ?? 'Colegio-Pro' }}</title>
    <link rel="icon" href="{{ isset($currentTenant) && $currentTenant->logo ? asset($currentTenant->logo) : asset('favicon.ico') }}">
    <link rel="manifest" href="/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at 50% -20%, #1e293b, #0f172a);
            overflow: hidden;
        }

        /* Micro-Animations (Piripipí) */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes pulseGlow {
            0% { box-shadow: 0 0 15px rgba(255,255,255,0.1); }
            50% { box-shadow: 0 0 25px {{ $currentTenant->primary_color ?? '#3b82f6' }}88; }
            100% { box-shadow: 0 0 15px rgba(255,255,255,0.1); }
        }

        .login-box {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 5rem 2.5rem 2.5rem; /* Increased top padding to avoid overlap */
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            margin-top: 80px; /* Space for the floating logo */
        }

        .login-logo-container {
            background: #ffffff;
            border-radius: 20px; /* Modern squircle shape */
            padding: 15px 25px; /* Flexible padding */
            text-align: center;
            border: 3px solid {{ $currentTenant->primary_color ?? '#3b82f6' }};
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 120px;
            max-width: 280px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 4s ease-in-out infinite, pulseGlow 3s infinite;
            z-index: 10;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        }

        .login-logo-container img {
            max-height: 80px;
            max-width: 100%;
            object-fit: contain;
        }

        .login-logo-container .logo-text {
            color: #000;
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0;
            line-height: 1.1;
        }
        .form-control {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #f8fafc;
            padding: 12px 15px;
            border-radius: 6px;
        }
        .form-control:focus {
            background-color: #0f172a;
            border-color: {{ $currentTenant->primary_color ?? '#3b82f6' }};
            color: #f8fafc;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .form-control::placeholder {
            color: #cbd5e1;
            opacity: 1;
        }
        .btn-primary {
            background-color: #3b82f6;
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 6px;
            margin-top: 1rem;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .text-muted-custom a {
            color: #94a3b8;
            text-decoration: none;
        }
        .text-muted-custom a:hover {
            color: #f8fafc;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-logo-container">
            @if(isset($currentTenant) && $currentTenant->logo)
                <img src="{{ asset($currentTenant->logo) }}" alt="Logo">
            @else
                <h4 class="m-0 text-white fw-bold">{{ $currentTenant->name ?? 'MultiPOS' }}</h4>
            @endif
        </div>

        <h4 class="text-center fw-bold mb-2">Verificación de Identidad</h4>
        <p class="text-center text-secondary small mb-4">Para ingresar, primero debemos validar que te encuentras en nuestro padrón oficial.</p>

        @if(session('error'))
            <div class="alert alert-danger p-2 small text-center rounded-3 border-0 bg-danger bg-opacity-25 text-danger fw-bold">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.verify') }}">
            @csrf

            <div class="mb-3">
                <input id="dni" type="text" class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni') }}" required autofocus placeholder="Número de Documento (DNI sin puntos)">
                @error('dni')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Correo Electrónico">
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Teléfono de Contacto (Ej: 3804123456)">
                @error('phone')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Verificar Identidad
            </button>

            <div class="text-center text-muted-custom mt-4">
                <a href="{{ route('login') }}" class="text-info fw-bold">Ya tengo cuenta, quiero Iniciar Sesión</a>
            </div>
        </form>
    </div>

</body>
</html>
@endif

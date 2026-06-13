@if(isset($currentTenant) && view()->exists('tenants.' . $currentTenant->slug . '.auth.login'))
    @include('tenants.' . $currentTenant->slug . '.auth.login')
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - {{ $currentTenant->name ?? 'Colegio-Pro' }}</title>
    <link rel="manifest" href="/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a; /* Slate 900 */
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
            background: rgba(30, 41, 59, 0.7); /* Slate 800 with transparency for glass effect */
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 3rem 2.5rem 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            margin-top: 40px; /* Space for the floating logo */
        }

        .login-logo-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            border: 2px solid {{ $currentTenant->primary_color ?? '#3b82f6' }};
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 4s ease-in-out infinite, pulseGlow 3s infinite;
            z-index: 10;
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
        .form-label {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
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
        .btn-demo {
            background-color: transparent;
            border: 1px solid #f59e0b;
            color: #f59e0b;
            padding: 10px;
            font-weight: 600;
            width: 100%;
            border-radius: 6px;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-demo:hover {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        .text-muted-custom {
            color: #64748b;
            font-size: 0.85rem;
        }
        .text-muted-custom a {
            color: #94a3b8;
            text-decoration: none;
        }
        .text-muted-custom a:hover {
            color: #f8fafc;
        }
        .form-check-label {
            font-size: 0.85rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-logo-container">
            @if(isset($currentTenant) && $currentTenant->logo)
                <img src="{{ asset($currentTenant->logo) }}" alt="Logo">
            @else
                <p class="logo-text">{{ $currentTenant->name ?? 'MultiPOS' }}</p>
            @endif
        </div>

        <h4 class="text-center fw-bold mb-4">Iniciar sesión</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Contraseña">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                Ingresar
            </button>
            
            <a href="{{ route('demo.fast') }}" class="btn-demo">
                <span class="material-icons" style="font-size: 18px;">auto_awesome</span> ACCESO DEMO (PRUEBA)
            </a>

            <div class="text-center text-muted-custom mt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
                <br><br>
                <a href="{{ route('register') }}" class="text-info fw-bold">No tengo cuenta, quiero Registrararme</a>
            </div>
        </form>
    </div>

</body>
</html>
@endif

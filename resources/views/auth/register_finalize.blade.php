@if(isset($currentTenant) && view()->exists('tenants.' . $currentTenant->slug . '.auth.register_finalize'))
    @include('tenants.' . $currentTenant->slug . '.auth.register_finalize')
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completar Registro - {{ $currentTenant->name ?? 'Colegio-Pro' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            padding: 3rem 2.5rem 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            position: relative;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            margin-top: 40px;
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
        .btn-success {
            background-color: #10b981;
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            border-radius: 6px;
            margin-top: 1rem;
        }
        .btn-success:hover {
            background-color: #059669;
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

        <div class="text-center mb-4">
            <span class="material-icons text-success" style="font-size: 3rem;">check_circle</span>
            <h4 class="fw-bold mt-2">¡Identidad Validada!</h4>
            <p class="text-secondary small">Hola <strong>{{ $collegiate->first_name ?? 'Colegiado' }}</strong>, te hemos encontrado en el padrón. Por favor crea una contraseña para activar tu cuenta digital.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Crea una Contraseña (mín. 8 caracteres)">
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirma tu Contraseña">
            </div>

            <button type="submit" class="btn btn-success">
                Finalizar Registro e Ingresar
            </button>
        </form>
    </div>

</body>
</html>
@endif

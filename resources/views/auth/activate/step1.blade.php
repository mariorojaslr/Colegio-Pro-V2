@if(isset($currentTenant) && view()->exists('tenants.' . $currentTenant->slug . '.auth.activate.step1'))
    @include('tenants.' . $currentTenant->slug . '.auth.activate.step1')
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activar Cuenta - {{ $currentTenant->name ?? 'Colegio-Pro' }}</title>
    <link rel="icon" href="{{ isset($currentTenant) && $currentTenant->logo ? asset($currentTenant->logo) : asset('favicon.ico') }}">
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

        /* Micro-Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        .login-box {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 3.5rem 2.5rem 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid #ffffff;
            position: relative;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-logo-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 15px 25px;
            text-align: center;
            border: 3px solid {{ $currentTenant->primary_color ?? '#3b82f6' }};
            margin: 0 auto 2.5rem;
            min-width: 120px;
            max-width: 280px;
            height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: float 4s ease-in-out infinite;
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
        .btn-primary-custom {
            background-color: {{ $currentTenant->primary_color ?? '#3b82f6' }};
            border-color: {{ $currentTenant->primary_color ?? '#3b82f6' }};
            color: #fff;
            padding: 12px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: #1e40af;
            border-color: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
        .text-muted-custom { color: #94a3b8; }
        .alert-danger { background-color: #7f1d1d; border-color: #991b1b; color: #fecaca; }
        .alert-info { background-color: #1e3a8a; border-color: #1e40af; color: #bfdbfe; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo-container">
            @if(isset($currentTenant) && $currentTenant->logo)
                <img src="{{ asset($currentTenant->logo) }}" alt="Logo">
            @else
                <p class="logo-text">{{ $currentTenant->name ?? 'Colegio-Pro' }}</p>
            @endif
        </div>

        <h4 class="text-center fw-bold mb-2">Activar tu Cuenta</h4>
        <p class="text-center text-muted-custom mb-4 small">¿Es tu primera vez aquí? Para activar tu cuenta y acceder a la plataforma, por favor identifícate ingresando tu <strong>N° de Matrícula</strong> y tu <strong>Apellido</strong>.</p>

        @if(session('error'))
            <div class="alert alert-danger mb-4 p-3 rounded-3 text-center small">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info mb-4 p-3 rounded-3 text-center small">
                {{ session('info') }}
            </div>
        @endif

        <form method="POST" action="{{ route('activate.search') }}">
            @csrf
            <div class="mb-3">
                <label for="matricula" class="form-label text-muted-custom small fw-bold">N° de Matrícula</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">badge</i></span>
                    <input id="matricula" type="text" class="form-control border-start-0 ps-0 @error('matricula') is-invalid @enderror" name="matricula" value="{{ old('matricula') }}" required autofocus placeholder="Ej. 1234">
                </div>
                @error('matricula')
                    <span class="text-danger small mt-1 d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="apellido" class="form-label text-muted-custom small fw-bold">Apellido (Para verificar tu identidad)</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">person_search</i></span>
                    <input id="apellido" type="text" class="form-control border-start-0 ps-0 @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required placeholder="Ej. Perez">
                </div>
                @error('apellido')
                    <span class="text-danger small mt-1 d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary-custom d-flex align-items-center justify-content-center">
                    Buscar mi perfil <i class="material-icons ms-2 fs-5">arrow_forward</i>
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0 text-muted-custom small">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: {{ $currentTenant->primary_color ?? '#3b82f6' }}">Inicia Sesión</a></p>
        </div>
    </div>
</body>
</html>
@endif

@if(isset($currentTenant) && view()->exists('tenants.' . $currentTenant->slug . '.auth.activate.step2'))
    @include('tenants.' . $currentTenant->slug . '.auth.activate.step2')
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completar Registro - {{ $currentTenant->name ?? 'Colegio-Pro' }}</title>
    <link rel="icon" href="{{ isset($currentTenant) && $currentTenant->logo ? asset($currentTenant->logo) : asset('favicon.ico') }}">
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

        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        .login-box {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            border: 1px solid #ffffff;
            animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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
        .user-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }
        .user-avatar {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: {{ $currentTenant->primary_color ?? '#3b82f6' }};
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: bold;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box" style="max-height: 90vh; overflow-y: auto;">
        
        <h4 class="text-center fw-bold mb-2">¡Hola, {{ explode(' ', $collegiate->first_name)[0] }}!</h4>
        <p class="text-center text-muted-custom mb-4 small">Hemos encontrado tu perfil. Completa estos datos para crear tu contraseña de acceso seguro.</p>

        <div class="user-card">
            <div class="user-avatar">
                {{ substr($collegiate->first_name, 0, 1) }}
            </div>
            <div>
                <h6 class="mb-0 fw-bold">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</h6>
                <p class="mb-0 text-muted-custom small">Matrícula: {{ $collegiate->registration_number }}</p>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mb-4 p-3 rounded-3 text-center small">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('activate.register') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label text-muted-custom small fw-bold">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">email</i></span>
                    <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email', $collegiate->email) }}" required placeholder="tu@email.com">
                </div>
                @error('email')
                    <span class="text-danger small mt-1 d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label text-muted-custom small fw-bold">Teléfono Celular</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">phone</i></span>
                    <input id="phone" type="text" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $collegiate->phone) }}" required placeholder="Ej. 11 1234 5678">
                </div>
                @error('phone')
                    <span class="text-danger small mt-1 d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-muted-custom small fw-bold">Crear Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">lock</i></span>
                    <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" required placeholder="Mínimo 8 caracteres">
                </div>
                @error('password')
                    <span class="text-danger small mt-1 d-block">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-muted-custom small fw-bold">Repetir Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary"><i class="material-icons text-muted-custom fs-5">lock_outline</i></span>
                    <input id="password_confirmation" type="password" class="form-control border-start-0 ps-0" name="password_confirmation" required placeholder="Confirma tu contraseña">
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary-custom d-flex align-items-center justify-content-center">
                    Crear mi cuenta y entrar <i class="material-icons ms-2 fs-5">login</i>
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="mb-0 text-muted-custom small"><a href="{{ route('activate.step1') }}" class="text-decoration-none" style="color: {{ $currentTenant->primary_color ?? '#3b82f6' }}">Volver atrás</a></p>
        </div>
    </div>
</body>
</html>
@endif

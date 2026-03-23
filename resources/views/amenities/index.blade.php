@extends('layouts.main')

@section('content')
<div class="container-fluid py-4 min-vh-100 bg-light-subtle">
    <div class="row g-4 mb-5">
        <div class="col-lg-12">
            <div class="card-prestige p-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-md-8 text-white">
                        <h1 class="display-5 fw-bold mb-2 shadow-text" style="font-family: 'Outfit', sans-serif;">Sedes y <span class="text-warning">Beneficios</span></h1>
                        <p class="lead opacity-75 mb-0 fs-5">Infraestructura exclusiva para los miembros del Colegio.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'ADMIN_COLEGIO')
        {{-- VISTA ADMINISTRADOR: CONFIGURACIÓN --}}
        <div class="row g-4">
            @foreach($amenities as $amenity)
            <div class="col-md-4">
                <div class="card-prestige p-4 border-0 {{ $amenity->is_active ? 'bg-white' : 'bg-light grayscale' }} h-100 transition-all">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                            <i class="bi {{ $amenity->icon }} fs-3 text-primary"></i>
                        </div>
                        <form action="{{ route('amenities.toggle', $amenity) }}" method="POST">
                            @csrf
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" onchange="this.form.submit()" {{ $amenity->is_active ? 'checked' : '' }}>
                            </div>
                        </form>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $amenity->name }}</h5>
                    <p class="small text-muted mb-4 opacity-75">{{ $amenity->description }}</p>
                    <div class="d-flex justify-content-between align-items-baseline mt-auto border-top pt-3">
                        <span class="fs-4 fw-bold text-dark">${{ number_format($amenity->base_price, 0, ',', '.') }}</span>
                        @if($amenity->is_seasonal)
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Modo Temporada</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            {{-- Botón agregar nueva amenidad (UI Placeholder) --}}
            <div class="col-md-4">
                <div class="card-prestige p-4 border-dashed border-primary border-opacity-20 d-flex flex-column align-items-center justify-content-center h-100 bg-white" style="cursor: pointer">
                    <i class="bi bi-plus-circle fs-1 text-primary mb-3"></i>
                    <h6 class="fw-bold m-0 text-primary">Agregar Nueva Sede</h6>
                </div>
            </div>
        </div>

    @else
        {{-- VISTA COLEGIADO: RESERVAS --}}
        <div class="row g-4">
            @foreach($amenities->where('is_active', true) as $amenity)
            <div class="col-md-4">
                <div class="card-prestige p-4 border-0 bg-white h-100 transition-all shadow-sm">
                    <div class="bg-dark bg-opacity-5 p-3 rounded-4 d-inline-block mb-3">
                        <i class="bi {{ $amenity->icon }} fs-3 text-dark"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $amenity->name }}</h5>
                    <p class="small text-muted mb-4 opacity-75">{{ $amenity->description }}</p>
                    
                    @if($amenity->has_calendar)
                        <form action="{{ route('amenities.book') }}" method="POST">
                            @csrf
                            <input type="hidden" name="amenity_id" value="{{ $amenity->id }}">
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <input type="date" name="date" class="form-control form-control-sm rounded-pill" required min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-6">
                                    <select name="slot" class="form-select form-select-sm rounded-pill" required>
                                        <option value="">Horario</option>
                                        <option value="Mañana (09:00 - 13:00)">Mañana</option>
                                        <option value="Tarde (14:00 - 18:00)">Tarde</option>
                                        <option value="Noche (19:00 - 23:00)">Noche</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <span class="fs-4 fw-bold text-primary">${{ number_format($amenity->getCurrentPrice(), 0, ',', '.') }}</span>
                                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold">Reservar <i class="bi bi-calendar-plus ms-1"></i></button>
                            </div>
                        </form>
                    @else
                        <div class="mt-auto d-flex justify-content-between align-items-baseline pt-3 border-top">
                            <span class="fs-4 fw-bold text-primary">${{ number_format($amenity->base_price, 0, ',', '.') }}</span>
                            <span class="badge bg-light text-dark border rounded-pill px-3">Uso Libre</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .grayscale { filter: grayscale(1); opacity: 0.6; }
    .card-prestige:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
</style>
@endsection

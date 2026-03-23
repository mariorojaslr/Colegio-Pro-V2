@extends('layouts.main')

@section('content')
<div class="container-fluid py-5 bg-light-subtle min-vh-100">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Header Sección -->
            <div class="card-prestige p-5 mb-5 border-0 overflow-hidden position-relative" 
                 style="background: linear-gradient(135deg, #020617, #0f172a); border-radius: 40px">
                <div class="row align-items-center position-relative" style="z-index: 2">
                    <div class="col-lg-8 text-white">
                        <h1 class="display-4 fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">Gestión de <span class="text-gradient-gold">Suscripción</span></h1>
                        <p class="fs-5 opacity-75 mb-0">Control centralizado de planes, facturación y límites de su institución.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        @if($activeSubscription)
                        <div class="bg-white bg-opacity-10 rounded-pill px-4 py-3 d-inline-block border border-white border-opacity-10">
                            <span class="text-white-50 small fw-bold text-uppercase ls-1 d-block mb-1">Plan Actual</span>
                            <span class="text-white fw-bold fs-4">{{ $activeSubscription->plan->name }}</span>
                        </div>
                        @else
                        <div class="bg-danger bg-opacity-10 rounded-pill px-4 py-3 d-inline-block border border-danger border-opacity-25">
                            <span class="text-danger fw-bold fs-5">Faltante: Sin Plan Activo</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabla de Precios -->
            <div class="row flex-nowrap overflow-auto pb-4 custom-scrollbar g-4 mb-5" style="margin-right: -15px; margin-left: -15px; padding-left: 15px; padding-right: 15px;">
                @foreach($plans as $plan)
                <div class="col-11 col-sm-8 col-md-6 col-lg-3 flex-shrink-0">
                    <div class="card-prestige h-100 p-4 p-xl-5 bg-white border-0 shadow-lg text-center position-relative transition-all hover-up {{ $activeSubscription && $activeSubscription->subscription_plan_id == $plan->id ? 'border-primary border-top' : '' }}" 
                         style="border-radius: 30px; border-top-width: 8px !important;">
                        
                        @if($activeSubscription && $activeSubscription->subscription_plan_id == $plan->id)
                        <span class="badge bg-primary rounded-pill px-3 py-2 position-absolute top-0 start-50 translate-middle shadow-sm">Plan Actual</span>
                        @endif

                        <h4 class="fw-bold mb-3 mb-xl-4 text-truncate" style="color: var(--primary-color)">{{ $plan->name }}</h4>
                        <div class="mb-4 mb-xl-5">
                            <span class="display-6 fw-bold text-dark">${{ number_format($plan->price, 0, ',', '.') }}</span>
                            <span class="text-muted small">/ mes</span>
                        </div>

                        <ul class="list-unstyled d-grid gap-2 mb-4 mb-xl-5 text-start px-2">
                            <li class="small fw-bold d-flex align-items-center"><i class="bi bi-mortarboard text-primary me-2"></i> <span>Hasta {{ number_format($plan->max_users, 0, ',', '.') }} alumnos</span></li>
                            <li class="small fw-bold d-flex align-items-center"><i class="bi bi-hdd-network text-primary me-2"></i> <span>{{ $plan->max_storage }} GB Almacenamiento</span></li>
                            <li class="small fw-bold d-flex align-items-center"><i class="bi bi-broadcast text-info me-2"></i> <span>{{ $plan->max_traffic }} GB Tráfico CDN</span></li>
                            <li class="small fw-bold d-flex align-items-center"><i class="bi bi-play-circle text-danger me-2"></i> <span>{{ $plan->max_streaming }} min Streaming</span></li>
                            @foreach($plan->features as $feature)
                            <li class="small text-muted d-flex align-items-start"><i class="bi bi-check2 text-success me-2 mt-1"></i> <span class="lh-sm">{{ $feature }}</span></li>
                            @endforeach
                        </ul>

                        <div class="mt-auto">
                            <form method="POST" action="{{ route('billing.upgrade') }}">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <button type="submit" class="btn {{ $activeSubscription && $activeSubscription->subscription_plan_id == $plan->id ? 'btn-outline-secondary disabled' : 'btn-prestige' }} w-100 rounded-pill py-3 fw-bold shadow">
                                    {{ $activeSubscription && $activeSubscription->subscription_plan_id == $plan->id ? 'Plan Actual' : 'Seleccionar Plan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row g-5">
                <!-- Sección: Métricas de Consumo Actual -->
                <div class="col-lg-6">
                    <div class="card-prestige bg-white p-5 border-0 shadow-2xl h-100" style="border-radius: 40px">
                        <h4 class="fw-bold mb-5" style="font-family: 'Outfit', sans-serif">Métricas de <span class="text-primary">Consumo</span></h4>
                        
                        <!-- Métrica de consumo: Alumnos activos -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-muted small text-uppercase">Alumnos Activos</span>
                                <span class="fw-bold fs-5">{{ $school->users()->count() }} <span class="text-muted opacity-50">/ {{ $activeSubscription->plan->max_users ?? '??' }}</span></span>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 12px">
                                @php $userPerc = $activeSubscription ? ($school->users()->count() / $activeSubscription->plan->max_users) * 100 : 0; @endphp
                                <div class="progress-bar rounded-pill bg-primary" style="width: {{ $userPerc }}%"></div>
                            </div>
                        </div>

                        <!-- Métrica de consumo: Tráfico CDN -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-muted small text-uppercase">Tráfico Mensual (CDN)</span>
                                <span class="fw-bold fs-5">{{ number_format($school->traffic_used, 1) }} <span class="text-muted opacity-50">/ {{ $activeSubscription->plan->max_traffic ?? '0' }} GB</span></span>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 12px">
                                @php $trafficPerc = ($activeSubscription && $activeSubscription->plan->max_traffic > 0) ? ($school->traffic_used / $activeSubscription->plan->max_traffic) * 100 : 0; @endphp
                                <div class="progress-bar rounded-pill bg-info" style="width: {{ $trafficPerc }}%"></div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-bold text-muted small text-uppercase">Archivos Totales</span>
                                <span class="fw-bold fs-5">{{ $school->total_files }} <span class="text-muted opacity-50">/ {{ number_format($activeSubscription->plan->max_files ?? 0, 0, ',', '.') }}</span></span>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 12px">
                                @php $filesPerc = ($activeSubscription && $activeSubscription->plan->max_files > 0) ? ($school->total_files / $activeSubscription->plan->max_files) * 100 : 0; @endphp
                                <div class="progress-bar rounded-pill bg-success" style="width: {{ $filesPerc }}%"></div>
                            </div>
                        </div>

                        <div class="bg-light p-4 rounded-4 border border-light-subtle d-flex align-items-center mb-4">
                            <i class="bi bi-play-btn-fill text-dark fs-3 me-3"></i>
                            <div class="small fw-bold text-muted">Streaming: {{ $school->streaming_usage }} min consumidos de {{ $activeSubscription->plan->max_streaming ?? 0 }} min mensuales.</div>
                        </div>

                        <div class="bg-primary bg-opacity-10 p-4 rounded-4 border border-primary border-opacity-25 d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-primary fs-3 me-3"></i>
                            <div class="small fw-bold text-muted">Aviso: El consumo excedente de streaming se factura de forma independiente según el tráfico generado en Bunny.net.</div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Pagos -->
                <div class="col-lg-6">
                    <div class="card-prestige bg-white p-5 border-0 shadow-2xl h-100" style="border-radius: 40px">
                        <h4 class="fw-bold mb-5" style="font-family: 'Outfit', sans-serif">Historial de <span class="text-primary">Pagos</span></h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr class="small text-muted text-uppercase fw-bold ls-1">
                                        <th class="border-0 pb-3">Fecha</th>
                                        <th class="border-0 pb-3">Referencia</th>
                                        <th class="border-0 pb-3">Monto</th>
                                        <th class="border-0 pb-3">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                        <td class="small fw-bold text-primary">{{ $payment->transaction_reference }}</td>
                                        <td class="fw-bold">${{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-bold">Pagado</span></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted fw-bold">No se registran pagos previos.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .hover-up:hover { transform: translateY(-10px); }
</style>
@endsection

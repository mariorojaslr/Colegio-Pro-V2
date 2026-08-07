@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 text-center mb-4">
            <h2 class="fw-bold">Estado de Cuenta Societaria</h2>
            <p class="text-secondary">Simulador de Pagos Integrado (Modo Sandbox)</p>
        </div>

        @if(session('success'))
            <div class="col-12">
                <div class="alert border-0 bg-success-soft text-success shadow-sm rounded-4 d-flex align-items-center">
                    <i class="bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">Operación Exitosa</h6>
                        <small>{{ session('success') }}</small>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="col-12">
                <div class="alert alert-danger">{{ session('error') }}</div>
            </div>
        @endif

        @if(isset($annualConcept))
            <div class="col-12 mb-2">
                <div class="card shadow-sm border-0 rounded-4 bg-primary text-white bg-gradient">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi-calendar2-check-fill me-2"></i>Pago Anual Anticipado Disponible</h4>
                            <p class="mb-0 opacity-75">Puede abonar el año completo por adelantado. Monto total equivalente a 10 cuotas: <strong>${{ number_format($annualConcept->default_amount, 0, ',', '.') }}</strong></p>
                        </div>
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#annualPaymentModal">
                            Generar Pago Anual
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Pago Anual -->
            <div class="modal fade" id="annualPaymentModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">
                        <div class="modal-header border-bottom py-3 px-4">
                            <h5 class="modal-title fw-bold text-dark">Generar Pago Anual Anticipado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('payment.annual') }}" method="POST">
                            @csrf
                            <div class="modal-body p-4 text-start">
                                <p class="text-secondary small mb-4">Puede dividir el pago anual en hasta 2 cuotas en las fechas que prefiera (entre enero y marzo). El sistema generará estas cuotas en su estado de cuenta para que proceda a abonarlas.</p>
                                
                                <div class="mb-3">
                                    <label class="form-label text-dark fw-bold small text-uppercase">Fecha Vencimiento Cuota 1 (50%)</label>
                                    <input type="date" name="date_1" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-dark fw-bold small text-uppercase">Fecha Vencimiento Cuota 2 (50%)</label>
                                    <input type="date" name="date_2" class="form-control" value="{{ \Carbon\Carbon::now()->addMonth()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4 text-dark" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Generar Acuerdo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Cuotas Pendientes</h5>
                    <span class="badge bg-warning text-dark rounded-pill">{{ $pendingDues->count() }} pendientes</span>
                </div>
                <div class="card-body p-0">
                    @if($pendingDues->count() > 0)
                        <form action="{{ route('payment.dues') }}" method="POST" id="paymentForm">
                            @csrf
                            <ul class="list-group list-group-flush">
                                @foreach($pendingDues as $due)
                                    <li class="list-group-item p-4">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-3 due-checkbox" type="checkbox" name="dues[]" value="{{ $due->id }}" data-amount="{{ $due->amount }}" id="due_{{ $due->id }}">
                                            <label class="form-check-label d-flex justify-content-between align-items-center w-100 cursor-pointer" for="due_{{ $due->id }}">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">Cuota {{ $due->due_date->format('F Y') }}</h6>
                                                    <small class="text-muted">Vence: {{ $due->due_date->format('d/m/Y') }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fs-5 fw-bold text-dark">${{ number_format($due->amount, 0, ',', '.') }}</span>
                                                    @if($due->status === 'overdue')
                                                        <span class="badge bg-danger ms-2">VENCIDA</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </form>
                    @else
                        <div class="text-center p-5">
                            <i class="bi-emoji-smile fs-1 text-success mb-3 d-block"></i>
                            <h5 class="fw-bold">¡Estás al día!</h5>
                            <p class="text-muted">No tienes cuotas societarias pendientes de pago.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 bg-light mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Resumen de Pago</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Subtotal</span>
                        <span class="fw-bold" id="subtotalAmount">$0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                        <span class="text-secondary">Cargos Mercado Pago</span>
                        <span class="fw-bold">$0</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fs-5 fw-bold text-dark">Total a Pagar</span>
                        <span class="fs-3 fw-bold text-primary" id="totalAmount">$0</span>
                    </div>

                    <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold fs-6 shadow-sm mb-3" onclick="submitPayment()" id="payBtn" disabled>
                        <i class="bi-credit-card-fill me-2"></i> Pagar con Mercado Pago (Sandbox)
                    </button>
                    <p class="text-center x-small text-muted mb-0"><i class="bi-shield-check me-1"></i> Esto es una simulación. El pago se aprobará automáticamente.</p>
                </div>
            </div>

            <!-- Historial -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold">Últimos Pagos</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($paidDues->take(3) as $paid)
                            <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 small fw-bold">Cuota {{ $paid->due_date->format('M Y') }}</h6>
                                    <small class="text-muted x-small">{{ $paid->paid_at->format('d/m/Y') }} • Ref: {{ $paid->payment_reference }}</small>
                                </div>
                                <span class="badge bg-success-soft text-success rounded-pill px-3 py-2 fw-bold">
                                    ${{ number_format($paid->amount, 0, ',', '.') }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item p-4 text-center text-muted small">No hay pagos registrados.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotals() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.due-checkbox:checked');
        checkboxes.forEach(cb => {
            total += parseFloat(cb.dataset.amount);
        });

        const formatted = new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS', maximumFractionDigits: 0 }).format(total);
        document.getElementById('subtotalAmount').innerText = formatted;
        document.getElementById('totalAmount').innerText = formatted;
        
        document.getElementById('payBtn').disabled = total === 0;
    }

    document.querySelectorAll('.due-checkbox').forEach(cb => {
        cb.addEventListener('change', updateTotals);
    });

    function submitPayment() {
        if(confirm('Usted será redirigido a Mercado Pago Sandbox. (Modo Simulación: el pago se aprobará automáticamente)')) {
            document.getElementById('paymentForm').submit();
        }
    }
</script>
@endsection

@extends('layouts.admin')

@section('header', 'Gestión de Facturación Central')

@section('styles')
<style>
    body, .main-content {
        background-color: #0f111a !important;
        color: #e2e8f0 !important;
    }
    
    .metric-box {
        background-color: #1a1e29;
        border-radius: 8px;
        padding: 1.5rem;
        height: 100%;
        border-top: 3px solid #334155;
    }
    .metric-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #f8fafc;
        font-family: 'Outfit', sans-serif;
    }
    .metric-value.text-danger { color: #ef4444 !important; }
    .metric-value.text-warning { color: #eab308 !important; }
    .metric-value.text-success { color: #10b981 !important; }

    /* Accordion Style Multipost */
    .platform-client-list {
        background-color: transparent;
    }
    .platform-client-item {
        background-color: #1a1e29;
        border: 1px solid rgba(255,255,255,0.05);
        margin-bottom: 2px;
    }
    .platform-client-header {
        padding: 1rem 1.5rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .platform-client-header:hover {
        background-color: #1e2330;
    }
    .client-name {
        font-weight: 700;
        font-size: 1rem;
        color: #f8fafc;
        margin-bottom: 0.2rem;
    }
    .client-meta {
        font-size: 0.75rem;
        color: #64748b;
    }
    .status-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-pill.bg-warning { background-color: rgba(234, 179, 8, 0.2) !important; color: #eab308; border: 1px solid rgba(234, 179, 8, 0.3); }
    .status-pill.bg-danger { background-color: rgba(239, 68, 68, 0.2) !important; color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
    .status-pill.bg-success { background-color: rgba(16, 185, 129, 0.2) !important; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }

    .client-total-paid {
        font-size: 0.85rem;
        font-weight: 700;
        color: #f8fafc;
        text-align: right;
    }

    .platform-client-body {
        background-color: #151821;
        padding: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .action-btn {
        background: transparent;
        border: 1px solid #0284c7;
        color: #38bdf8;
        font-size: 0.75rem;
        padding: 0.4rem 1rem;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn:hover {
        background: rgba(2, 132, 199, 0.1);
        color: #7dd3fc;
    }
    .action-btn.btn-warning-outline {
        border-color: #ca8a04;
        color: #facc15;
    }
    .action-btn.btn-warning-outline:hover {
        background: rgba(202, 138, 4, 0.1);
        color: #fde047;
    }

    .history-table {
        width: 100%;
        margin-top: 1rem;
    }
    .history-table th {
        color: #cbd5e1;
        font-size: 0.75rem;
        padding: 0.75rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        font-weight: 600;
    }
    .history-table td {
        padding: 0.75rem;
        font-size: 0.8rem;
        color: #94a3b8;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    .page-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #f8fafc; /* Alto contraste */
        margin-bottom: 2rem;
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')

<h2 class="page-title">Gestión de Facturación Central</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="metric-box">
            <div class="metric-title">Empresas Activas</div>
            <div class="metric-value">{{ $metrics['active_schools'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-box" style="border-top-color: #ef4444;">
            <div class="metric-title">Vencidas / Morosas</div>
            <div class="metric-value text-danger">{{ $metrics['overdue_schools'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-box" style="border-top-color: #eab308;">
            <div class="metric-title">Por Vencer (7 Días)</div>
            <div class="metric-value text-warning">{{ $metrics['expiring_schools'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-box" style="border-top-color: #10b981;">
            <div class="metric-title">Recaudación Total</div>
            <div class="metric-value text-success">${{ number_format($metrics['total_revenue'], 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="card bg-transparent border-0">
    <div class="card-header bg-transparent border-0 px-0 pb-3">
        <h6 class="m-0 text-white fw-bold">Estado de Clientes Plataforma</h6>
    </div>
    <div class="platform-client-list accordion" id="clientsAccordion">
        
        @foreach($clientsStatus as $index => $client)
        <div class="platform-client-item accordion-item border-0 bg-transparent">
            <div class="platform-client-header accordion-header" id="heading{{ $index }}">
                <div class="d-flex justify-content-between align-items-center w-100" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                    <div class="flex-grow-1">
                        <div class="client-name">{{ $client->school->name }}</div>
                        <div class="client-meta">Plan: {{ $client->school->activeSubscription->plan->name ?? 'Básico' }} | Subdominio: {{ $client->school->slug }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-center justify-content-center" style="min-width: 180px;">
                        <span class="status-pill w-100 text-center bg-{{ $client->status_badge }} mb-1" style="display: inline-block;">{{ $client->status_label }}</span>
                        <div class="client-total-paid w-100 text-center">Total Pagado: ${{ number_format($client->total_paid, 0, ',', '.') }}</div>
                    </div>
                    <div class="ms-4 text-muted text-center" style="width: 24px;">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
            </div>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#clientsAccordion">
                <div class="platform-client-body">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <div class="text-primary" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 1px;">Acciones de Gestión</div>
                            <div class="d-flex gap-2">
                                @if($client->is_overdue)
                                <a href="#" class="action-btn btn-warning-outline">Notificar Vencimiento</a>
                                @endif
                                <a href="{{ route('admin.schools.edit', $client->school->id) }}" class="action-btn">Editar Empresa</a>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; margin-bottom: 0.2rem; letter-spacing: 1px;">Última notificación enviada</div>
                            <div class="text-white" style="font-size: 0.8rem;">Nunca enviada</div>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-white" style="font-size: 0.85rem; font-weight: 600;">Historial de Pagos</h6>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Plan</th>
                                    <th>Método</th>
                                    <th>Monto</th>
                                    <th>Comprobante</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($client->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $payment->description ?? 'Mensualidad' }}</td>
                                    <td>{{ ucfirst($payment->payment_method ?? 'transferencia') }}</td>
                                    <td class="text-white fw-bold">${{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>-</td>
                                    <td><span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Aprobado</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No se registran pagos históricos para esta empresa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

@endsection

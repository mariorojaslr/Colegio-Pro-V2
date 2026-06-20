<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1e293b; margin: 0; padding: 40px; line-height: 1.5; }
        .header { border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 40px; }
        .logo { font-size: 28px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-title { font-size: 36px; font-weight: bold; color: #2563eb; text-align: right; margin-top: -40px; }
        .row { width: 100%; display: table; table-layout: fixed; margin-bottom: 30px; }
        .col { display: table-cell; vertical-align: top; }
        .label { font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; margin-bottom: 5px; }
        .data { font-size: 14px; color: #0f172a; }
        .table-items { width: 100%; border-collapse: collapse; margin-top: 50px; }
        .table-items th { background: #f8fafc; text-align: left; padding: 15px; border-bottom: 2px solid #e2e8f0; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .table-items td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .total-box { margin-top: 40px; background: #0f172a; color: white; padding: 30px; border-radius: 10px; text-align: right; width: 40%; margin-left: auto; }
        .total-label { font-size: 12px; margin-bottom: 5px; opacity: 0.8; }
        .total-amount { font-size: 32px; font-weight: 900; }
        .footer { margin-top: 100px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">COLEGIO-PRO Plataforma</div>
        <div class="invoice-title">FACTURA</div>
    </div>

    <div class="row">
        <div class="col">
            <div class="label">Emitido por:</div>
            <div class="data fw-bold">ANTIGRAVITY SYSTEMS SPA</div>
            <div class="data">Santiago, Chile</div>
            <div class="data">contacto@colegio-pro.cl</div>
        </div>
        <div class="col" style="text-align: right;">
            <div class="label">Nro de Factura:</div>
            <div class="data fw-bold">#{{ $invoice->invoice_number }}</div>
            <div class="label" style="margin-top: 15px;">Fecha de Emisión:</div>
            <div class="data">{{ $invoice->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="row" style="background: #f8fafc; padding: 20px; border-radius: 10px;">
        <div class="col">
            <div class="label">Cliente / Institución:</div>
            <div class="data fw-bold">{{ $invoice->school->name }}</div>
            <div class="data">Slug: {{ $invoice->school->slug }}.colegio-pro.cl</div>
        </div>
        <div class="col" style="text-align: right;">
            <div class="label">Estado del Pago:</div>
            <div class="data">
                <span style="background: {{ $invoice->status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $invoice->status == 'paid' ? '#166534' : '#991b1b' }}; padding: 5px 12px; border-radius: 50px; font-weight: bold; font-size: 11px; text-transform: uppercase;">
                    {{ $invoice->status == 'paid' ? 'PAGADO' : 'PENDIENTE' }}
                </span>
            </div>
        </div>
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th width="70%">Concepto de Servicio</th>
                <th width="10%">Cant.</th>
                <th width="20%" style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: bold;">Suscripción Mensual - Plan {{ $invoice->school->activeSubscription->plan->name ?? 'Plataforma' }}</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 5px;">
                        Incluye hasta {{ $invoice->school->activeSubscription->plan->max_users }} asociados activos y 
                        {{ $invoice->school->activeSubscription->plan->max_storage }}GB de almacenamiento Cloud.
                    </div>
                </td>
                <td>1</td>
                <td style="text-align: right;">${{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-label">TOTAL A PAGAR (CLP)</div>
        <div class="total-amount">${{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
    </div>

    <div class="footer">
        Esta es una factura generada automáticamente por la plataforma COLEGIO-PRO. PROHIBIDA LA ALTERACIÓN.
        <br>Para soporte contable, diríjase a billing@colegio-pro.cl
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libro de Actas Digital - {{ $school->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.5;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1E3A8A;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .institution-title {
            font-size: 16px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
            margin: 0;
        }
        .document-title {
            font-size: 13px;
            font-weight: bold;
            color: #555555;
            margin: 5px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 10px;
            color: #666666;
        }
        .intro-text {
            margin-bottom: 20px;
            text-align: justify;
            font-size: 11px;
        }
        .table-actas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-actas th {
            background-color: #1E3A8A;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #1E3A8A;
            font-size: 10px;
            text-transform: uppercase;
        }
        .table-actas td {
            padding: 8px;
            border: 1px solid #dddddd;
            font-size: 10px;
            vertical-align: top;
        }
        .table-actas tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-active {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .badge-lifted {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }
        .signatures-section {
            margin-top: 50px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signatures-title {
            font-weight: bold;
            border-bottom: 1px solid #cccccc;
            padding-bottom: 5px;
            margin-bottom: 30px;
            text-transform: uppercase;
            font-size: 10px;
            color: #555555;
        }
        .signature-box {
            width: 30%;
            float: left;
            text-align: center;
            margin-right: 5%;
        }
        .signature-box-last {
            width: 30%;
            float: left;
            text-align: center;
        }
        .signature-line {
            border-top: 1px dashed #333333;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 9px;
            font-weight: bold;
        }
        .signature-role {
            font-size: 8px;
            color: #666666;
        }
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: center;
            font-size: 8px;
            color: #999999;
            border-top: 1px solid #eeeeee;
            padding-top: 5px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="institution-title">{{ $school->name }}</h1>
        <h2 class="document-title">Libro de Actas y Resoluciones Disciplinarias</h2>
    </div>

    <div class="meta-info">
        <strong>Fecha de Emisión:</strong> {{ date('d/m/Y H:i') }}<br>
        <strong>Jurisdicción:</strong> Provincia de La Rioja, Argentina
    </div>

    <div class="intro-text">
        El presente documento constituye el registro digital consolidado de las actas de fallo, dictámenes y resoluciones disciplinarias emitidas por el **Tribunal de Ética y Disciplina Profesional** de esta institución. Se compilan de manera oficial todas las sanciones aplicadas a colegiados que han incurrido en violaciones del Código de Ética de la institución, detallando sus periodos de vigencia y estados correspondientes.
    </div>

    <h3 style="border-bottom: 1px solid #1E3A8A; padding-bottom: 3px; color: #1E3A8A; font-size: 11px; text-transform: uppercase;">Registro Histórico de Fallos</h3>

    <table class="table-actas">
        <thead>
            <tr>
                <th style="width: 10%;">Acta</th>
                <th style="width: 12%;">Fecha Fallo</th>
                <th style="width: 23%;">Colegiado</th>
                <th style="width: 35%;">Causa / Infracción</th>
                <th style="width: 10%;">Vigencia</th>
                <th style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @php $actIndex = 1; @endphp
            @forelse($sanctions as $s)
                <tr>
                    <td style="font-weight: bold; color: #1E3A8A;">ACTA-TS-{{ str_pad($actIndex++, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }}</td>
                    <td style="font-weight: bold;">{{ $s->collegiate->last_name }}, {{ $s->collegiate->first_name }}</td>
                    <td>
                        {{ $s->reason }}
                        @if($s->status === 'lifted' && $s->lifted_reason)
                            <br><span style="font-size: 8px; color: #666666; font-style: italic;">Motivo Levantamiento: {{ $s->lifted_reason }}</span>
                        @endif
                    </td>
                    <td>{{ $s->type === 'temporary' ? 'Temporal' : 'Permanente' }}</td>
                    <td>
                        @if($s->status === 'active')
                            <span class="badge badge-active">Activa</span>
                        @else
                            <span class="badge badge-lifted">Levantada</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999999; padding: 20px;">No existen sanciones éticas registradas en los libros de la institución.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($commissionMembers->count() > 0)
        <div class="signatures-section">
            <div class="signatures-title">Autoridades Firmantes del Tribunal de Ética</div>
            <div class="clearfix">
                @foreach($commissionMembers as $idx => $member)
                    @php
                        $isLast = ($idx === $commissionMembers->count() - 1);
                        $boxClass = $isLast ? 'signature-box-last' : 'signature-box';
                    @endphp
                    <div class="{{ $boxClass }}">
                        <div class="signature-line">
                            {{ $member->name }}
                        </div>
                        <div class="signature-role">
                            {{ $member->role }}
                        </div>
                        <div class="signature-role" style="font-size: 7px; color: #999999; margin-top: 2px;">
                            Documento Firmado Digitalmente
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="footer">
        {{ $school->name }} - Sistema de Gestión de Colegios Profesionales "Colegio Pro" &copy; {{ date('Y') }}
    </div>

</body>
</html>

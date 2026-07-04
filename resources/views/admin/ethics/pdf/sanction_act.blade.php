<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Resolución Disciplinaria - {{ $sanction->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.6;
            margin: 25px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #991b1b;
            padding-bottom: 15px;
            margin-bottom: 30px;
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
            color: #991b1b;
            margin: 5px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10px;
        }
        .meta-table td {
            padding: 6px 10px;
            border: 1px solid #dddddd;
        }
        .meta-label {
            font-weight: bold;
            background-color: #f5f5f5;
            color: #555555;
            width: 25%;
            text-transform: uppercase;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1E3A8A;
            border-bottom: 1px solid #1E3A8A;
            padding-bottom: 4px;
            margin-top: 25px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .text-justify {
            text-align: justify;
        }
        .signatures-section {
            margin-top: 60px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signatures-title {
            font-weight: bold;
            border-bottom: 1px solid #cccccc;
            padding-bottom: 5px;
            margin-bottom: 40px;
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
        <h2 class="document-title">Acta de Resolución de Fallo Disciplinario</h2>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Acta Número</td>
            <td style="font-weight: bold; color: #991b1b;">ACTA-DISC-{{ str_pad($sanction->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td class="meta-label">Fecha de Registro</td>
            <td>{{ \Carbon\Carbon::parse($sanc->created_at ?? $sanction->start_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Colegiado Sancionado</td>
            <td style="font-weight: bold;">{{ $sanction->collegiate->last_name }}, {{ $sanction->collegiate->first_name }}</td>
            <td class="meta-label">Matrícula Profesional</td>
            <td style="font-weight: bold;">M.P. Nº {{ $sanction->collegiate->registration_number }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tipo de Sanción</td>
            <td>{{ $sanction->type === 'temporary' ? 'Inhabilitación Temporal' : 'Expulsión Permanente' }}</td>
            <td class="meta-label">Estado Actual</td>
            <td style="font-weight: bold;">
                @if($sanction->status === 'active')
                    <span style="color: #991b1b;">ACTIVA (Inhabilitado)</span>
                @else
                    <span style="color: #166534;">LEVANTADA (Habilitado)</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-label">Vigencia Sanción</td>
            <td>Desde: {{ \Carbon\Carbon::parse($sanction->start_date)->format('d/m/Y') }}</td>
            <td class="meta-label">Fecha Finalización</td>
            <td>{{ $sanction->end_date ? \Carbon\Carbon::parse($sanction->end_date)->format('d/m/Y') : 'N/A (Permanente)' }}</td>
        </tr>
    </table>

    <div class="section-title">Vistos y Considerandos</div>
    <div class="text-justify">
        Que este Tribunal de Ética y Disciplina Profesional del Colegio, en ejercicio de las atribuciones que le otorga la Ley Provincial de Regulación Profesional, ha evaluado las actuaciones y denuncias correspondientes a la conducta profesional de la colegiada referenciada.
        <br><br>
        Que se ha verificado el incumplimiento de las normativas vigentes sobre la ética en la práctica del Trabajo Social, específicamente en lo relativo a:
        <strong style="color: #991b1b;">{{ $sanction->reason }}</strong>.
        <br><br>
        Que se han evaluado los argumentos de defensa y los elementos probatorios presentados durante el proceso de investigación ética por el cuerpo de veedores.
    </div>

    <div class="section-title">Resolución del Tribunal</div>
    <div class="text-justify" style="font-weight: bold; background-color: #f9f9f9; padding: 10px; border-left: 3px solid #991b1b;">
        Artículo 1º: Aplicar la sanción de {{ $sanction->type === 'temporary' ? 'Inhabilitación Temporal' : 'Expulsión Permanente' }} de la matrícula profesional al colegiado {{ $sanction->collegiate->first_name }} {{ $sanction->collegiate->last_name }}, bajo el registro de la matrícula profesional M.P. Nº {{ $sanction->collegiate->registration_number }}.
        <br><br>
        Artículo 2º: Establecer la vigencia del fallo disciplinario a partir del día {{ \Carbon\Carbon::parse($sanction->start_date)->format('d/m/Y') }} @if($sanction->end_date) hasta el día {{ \Carbon\Carbon::parse($sanction->end_date)->format('d/m/Y') }} @endif.
        @if($sanction->status === 'lifted')
            <br><br>
            Artículo 3º: Registrar que con fecha {{ \Carbon\Carbon::parse($sanction->lifted_at)->format('d/m/Y') }} la presente sanción ha sido formalmente LEVANTADA por las autoridades correspondientes debido a: "{{ $sanction->lifted_reason }}", quedando restituido el colegiado a su estado de habilitación para el ejercicio legal de la profesión.
        @endif
    </div>

    @if($commissionMembers->count() > 0)
        <div class="signatures-section">
            <div class="signatures-title">Cuerpo de Veedores Firmantes (Tribunal de Ética)</div>
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
        {{ $school->name }} - Registro Oficial del Tribunal de Ética Profesional &copy; {{ date('Y') }}
    </div>

</body>
</html>

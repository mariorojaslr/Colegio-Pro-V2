<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificados - {{ $certificate_type->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            line-height: 1.5;
            background-color: #ffffff;
        }
        .page-container {
            page-break-after: always;
            border: 8px double #1E3A8A;
            padding: 40px;
            text-align: center;
            box-sizing: border-box;
        }
        .page-container:last-child {
            page-break-after: avoid;
        }
        .school-title {
            font-size: 24px;
            font-weight: bold;
            color: #1E3A8A;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .cert-title {
            font-size: 20px;
            font-weight: bold;
            color: #555555;
            letter-spacing: 2px;
            margin-bottom: 40px;
            text-transform: uppercase;
        }
        .collegiate-name {
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            margin: 30px 0;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 10px;
            display: inline-block;
        }
        .meta-info {
            font-size: 14px;
            color: #555555;
            margin-bottom: 40px;
        }
        .date-info {
            margin-top: 50px;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>
<body>

    @foreach($collegiates as $collegiate)
        <div class="page-container">
            <div class="school-title">{{ $school->name }}</div>
            <div class="cert-title">Certificado de {{ $certificate_type->name }}</div>
            
            <p style="font-size: 16px; font-style: italic;">Por la presente se certifica y hace constar que el/la profesional:</p>
            
            <div class="collegiate-name">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</div>
            
            <div class="meta-info">
                Con Documento Nacional de Identidad Nº <strong>{{ $collegiate->dni }}</strong>, se encuentra registrado bajo la Matrícula Profesional Número <strong>{{ $collegiate->registration_number }}</strong>, cumpliendo con los requisitos regulatorios de la institución.
            </div>
            
            <div class="date-info">
                Expedido en la provincia de La Rioja, Argentina, a los {{ date('d/m/Y') }}.
                @if($certificate_type->validity_days)
                    <br>Vencimiento: {{ \Carbon\Carbon::parse(now())->addDays($certificate_type->validity_days)->format('d/m/Y') }}.
                @endif
            </div>
        </div>
    @endforeach

</body>
</html>

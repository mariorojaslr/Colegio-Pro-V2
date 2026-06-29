<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            height: 100%;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            max-height: 120px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #1a1a1a;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 60px;
            text-align: justify;
        }
        .name {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            margin-top: 50px;
        }
        .qr-code {
            position: absolute;
            bottom: 40px;
            right: 40px;
            text-align: center;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
        .qr-code p {
            font-size: 10px;
            margin-top: 5px;
            color: #666;
        }
        .signatures {
            margin-top: 100px;
            width: 100%;
            display: table;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            width: 80%;
            border-bottom: 1px solid #000;
            margin: 0 auto 5px auto;
        }
        .signature-name {
            font-size: 14px;
            font-weight: bold;
        }
        .signature-role {
            font-size: 12px;
            color: #666;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: -1;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($school->logo)
            <img src="{{ public_path($school->logo) }}" class="watermark" alt="Marca de agua">
        @endif
        
        <div class="header">
            @if($school->logo)
                <img src="{{ public_path($school->logo) }}" class="logo" alt="Logo">
            @endif
            <div class="school-name">{{ $school->name }}</div>
            <div style="font-size: 12px; color: #666;">C.U.I.T.: {{ $school->cuit ?? 'No registrado' }} | {{ $school->address }}</div>
        </div>

        <div class="title">
            {{ strtoupper($certificate->type->name) }}
        </div>

        <div class="content">
            @if($certificate->type->template_content)
                @php
                    $content = $certificate->type->template_content;
                    $content = str_replace('@{{nombre}}', $collegiate->first_name . ' ' . $collegiate->last_name, $content);
                    $content = str_replace('@{{dni}}', $collegiate->dni ?? 'No registrado', $content);
                    $content = str_replace('@{{matricula}}', $collegiate->registration_number ?? 'EN TRÁMITE', $content);
                    $content = str_replace('@{{fecha_emision}}', \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d \d\e F \d\e Y'), $content);
                    $content = str_replace('@{{valido_hasta}}', $certificate->expires_at ? \Carbon\Carbon::parse($certificate->expires_at)->format('d/m/Y') : 'Ilimitada', $content);
                @endphp
                {!! $content !!}
            @else
                Por la presente, el Consejo Directivo del <strong>{{ $school->name }}</strong> certifica y hace constar que el/la profesional:
                
                <div class="name">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</div>
                
                @if($collegiate->dni)
                    con documento de identidad Nº <strong>{{ $collegiate->dni }}</strong>, 
                @endif
                se encuentra debidamente registrado/a en esta Institución bajo la matrícula profesional número <strong>{{ $collegiate->registration_number ?? 'EN TRÁMITE' }}</strong>.

                <p style="margin-top: 20px;">
                    @if($certificate->type->requires_no_sanctions && $collegiate->is_ethics_compliant)
                        Asimismo, se certifica que a la fecha de expedición, el/la mencionado/a profesional <strong>no registra sanciones éticas ni disciplinarias</strong> vigentes que inhabiliten el ejercicio de su profesión.
                    @endif
                </p>
                
                <p style="margin-top: 20px;">
                    @if($certificate->type->requires_clearance && $collegiate->is_fees_compliant)
                        De igual manera, se deja constancia de que se encuentra <strong>al día con el cumplimiento de sus obligaciones arancelarias</strong> e institucionales.
                    @endif
                </p>

                <p style="margin-top: 30px;">
                    Se expide el presente certificado a solicitud del interesado/a en la ciudad de La Rioja, a los {{ \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d \d\i\a\s d\e F \d\e Y') }}.
                </p>
                
                @if($certificate->expires_at)
                <p style="font-size: 12px; font-weight: bold; margin-top: 20px;">
                    Válido hasta: {{ \Carbon\Carbon::parse($certificate->expires_at)->format('d/m/Y') }}
                </p>
                @endif
            @endif
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Secretario/a General</div>
                <div class="signature-role">{{ $school->name }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Presidente/a</div>
                <div class="signature-role">{{ $school->name }}</div>
            </div>
        </div>

        @if($certificate->type->has_qr ?? true)
        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(200)->generate(route('validation.show', $certificate->uuid))) }}" alt="QR Code">
            <p>Escanear para validar<br><strong>CÓDIGO: {{ $certificate->code }}</strong></p>
        </div>
        @endif
    </div>
</body>
</html>

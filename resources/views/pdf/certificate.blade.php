<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Oficial - {{ $certificate->type->name ?? 'Certificado de Ética Profesional' }}</title>
    <style>
        /* Configuración de página A4 Horizontal (Landscape) sin márgenes exteriores para marco completo */
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            background-color: #ffffff;
        }
        /* Marco diplomático institucional */
        .outer-frame {
            width: 100%;
            height: 178mm;
            border: 4px solid {{ $school->primary_color ?? '#0f172a' }};
            padding: 6px;
            box-sizing: border-box;
            position: relative;
        }
        .inner-frame {
            width: 100%;
            height: 100%;
            border: 1.5px solid #cbd5e1;
            padding: 22px 36px;
            box-sizing: border-box;
            position: relative;
        }
        /* Marca de agua central sutil */
        .watermark {
            position: absolute;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
            max-width: 340px;
            max-height: 340px;
        }
        /* Cabecera institucional */
        .header-table {
            width: 100%;
            border-bottom: 2px solid {{ $school->primary_color ?? '#0f172a' }};
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .logo {
            max-width: 82px;
            max-height: 82px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: {{ $school->primary_color ?? '#0f172a' }};
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .school-sub {
            font-size: 11px;
            color: #64748b;
        }
        .cert-badge {
            display: inline-block;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            color: #334155;
            border-radius: 4px;
            text-transform: uppercase;
        }
        /* Título del Certificado */
        .title-box {
            text-align: center;
            margin: 14px 0 18px 0;
        }
        .title {
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 2px;
            margin: 0;
        }
        .subtitle-line {
            width: 120px;
            height: 3px;
            background-color: {{ $school->primary_color ?? '#1e3a8a' }};
            margin: 6px auto 0 auto;
        }
        /* Cuerpo legal del certificado */
        .content {
            font-size: 14.5px;
            line-height: 1.65;
            text-align: justify;
            color: #1e293b;
            margin: 0 10px 16px 10px;
        }
        .professional-card {
            background-color: #f8fafc;
            border-left: 4px solid {{ $school->primary_color ?? '#1e3a8a' }};
            border-top: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 20px;
            margin: 14px 0;
            text-align: center;
        }
        .name {
            font-size: 21px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .credentials-row {
            font-size: 13.5px;
            color: #334155;
        }
        .credentials-badge {
            display: inline-block;
            margin: 0 12px;
            font-weight: bold;
            color: #0f172a;
        }
        /* Pie con Firmas y QR en tabla de 3 columnas */
        .footer-table {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
        }
        .signature-cell {
            width: 35%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 10px;
        }
        .qr-cell {
            width: 30%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            width: 82%;
            border-bottom: 1.5px solid #0f172a;
            margin: 0 auto 6px auto;
        }
        .signature-name {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-role {
            font-size: 11px;
            color: #64748b;
        }
        .qr-box {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 6px;
            background: #ffffff;
            border-radius: 6px;
        }
        .qr-box img {
            width: 88px;
            height: 88px;
            display: block;
            margin: 0 auto;
        }
        .qr-caption {
            font-size: 9.5px;
            color: #475569;
            margin-top: 4px;
            line-height: 1.25;
        }
        .validity-bar {
            margin-top: 10px;
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
            font-size: 10.5px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        // Preparamos el logo en base64 para que DomPDF lo renderice siempre sin fallos de ruta
        $logoBase64 = null;
        if (!empty($school->logo) && file_exists(public_path($school->logo))) {
            $logoPath = public_path($school->logo);
            $logoExt = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoExt . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Obtenemos autoridades firmantes (Presidente y Secretaría) si existen en el padrón de autoridades
        $presidente = \App\Models\BoardMember::where('school_id', $school->id)
            ->where('department', 'Comisión Directiva')
            ->where('role', 'like', '%Presidente%')
            ->where('role', 'not like', '%Vice%')
            ->first();

        $secretaria = \App\Models\BoardMember::where('school_id', $school->id)
            ->where('department', 'Comisión Directiva')
            ->where('role', 'like', '%Secretari%')
            ->first();

        $presidenteEtica = \App\Models\BoardMember::where('school_id', $school->id)
            ->where('department', 'like', '%Ética%')
            ->where('role', 'like', '%Presidente%')
            ->first();

        $isEthicsCert = str_contains(mb_strtolower($certificate->type->name ?? ''), 'ética') || str_contains(mb_strtolower($certificate->type->name ?? ''), 'etica');
        $fechaEmisionTexto = \Carbon\Carbon::parse($certificate->issued_at ?? now())->locale('es')->translatedFormat('d \d\e F \d\e Y');
        $validoHastaTexto = $certificate->expires_at ? \Carbon\Carbon::parse($certificate->expires_at)->format('d/m/Y') : 'Ilimitada';
    @endphp

    <div class="outer-frame">
        <div class="inner-frame">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="watermark" alt="Marca de agua">
            @endif

            {{-- Cabecera con Logo, Nombre del Colegio y Código de Verificación --}}
            <table class="header-table">
                <tr>
                    <td style="width: 15%; text-align: left; vertical-align: middle;">
                        @if($logoBase64)
                            <img src="{{ $logoBase64 }}" class="logo" alt="Logo Institucional">
                        @endif
                    </td>
                    <td style="width: 60%; text-align: left; vertical-align: middle; padding-left: 10px;">
                        <div class="school-name">{{ $school->name }}</div>
                        <div class="school-sub">
                            Personería Jurídica y Jurisdicción Oficial — Provincia de La Rioja, República Argentina
                            @if($school->cuit) | C.U.I.T.: {{ $school->cuit }} @endif
                            @if($school->address) | {{ $school->address }} @endif
                        </div>
                    </td>
                    <td style="width: 25%; text-align: right; vertical-align: middle;">
                        <div class="cert-badge">
                            DOCUMENTO OFICIAL<br>
                            <span style="color: #0f172a;">CÓD: {{ $certificate->code ?? 'OFICIAL' }}</span>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Título Principal --}}
            <div class="title-box">
                <h1 class="title">
                    {{ $isEthicsCert ? 'CERTIFICADO DE ÉTICA PROFESIONAL' : strtoupper($certificate->type->name ?? 'CERTIFICADO OFICIAL') }}
                </h1>
                <div class="subtitle-line"></div>
            </div>

            {{-- Cuerpo Legal --}}
            <div class="content">
                @if(!empty($certificate->type->template_content))
                    @php
                        $content = $certificate->type->template_content;
                        $fullName = trim($collegiate->first_name . ' ' . $collegiate->last_name);
                        $dniFormatted = $collegiate->dni ?: 'Sin especificar';
                        $matFormatted = $collegiate->registration_number ?: 'En trámite';

                        // Soporta tanto @{{variable}} como {{variable}}
                        $replacements = [
                            '@{{nombre}}' => $fullName,
                            '{{nombre}}' => $fullName,
                            '@{{dni}}' => $dniFormatted,
                            '{{dni}}' => $dniFormatted,
                            '@{{matricula}}' => $matFormatted,
                            '{{matricula}}' => $matFormatted,
                            '@{{fecha_emision}}' => $fechaEmisionTexto,
                            '{{fecha_emision}}' => $fechaEmisionTexto,
                            '@{{valido_hasta}}' => $validoHastaTexto,
                            '{{valido_hasta}}' => $validoHastaTexto,
                        ];
                        $content = strtr($content, $replacements);
                    @endphp
                    {!! $content !!}
                @else
                    Por la presente, la Comisión Directiva y el Tribunal de Ética y Disciplina del <strong>{{ $school->name }}</strong> certifican y hacen constar que el/la profesional:

                    <div class="professional-card">
                        <div class="name">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</div>
                        <div class="credentials-row">
                            <span class="credentials-badge">D.N.I. N°: {{ $collegiate->dni ?: 'Registrado en Legajo' }}</span>
                            |
                            <span class="credentials-badge">MATRÍCULA PROFESIONAL N°: {{ $collegiate->registration_number }}</span>
                        </div>
                    </div>

                    se encuentra debidamente inscripto/a y con matrícula <strong>ACTIVA Y HABILITADA</strong> para el ejercicio profesional de la Terapia Ocupacional en todo el ámbito de la Provincia de La Rioja.
                    <br><br>
                    Asimismo, se deja expresa constancia de que a la fecha de expedición del presente documento, el/la profesional mencionado/a <strong>NO REGISTRA SANCIONES ÉTICAS NI DISCIPLINARIAS</strong> firmes ni causas inhabilitantes en trámite ante el Tribunal de Ética y Disciplina de esta Institución, encontrándose en pleno goce de sus derechos colegiales y con sus obligaciones arancelarias regulares.
                    <br><br>
                    A solicitud del/de la interesado/a y para ser presentado ante las autoridades, obras sociales, instituciones de salud o entidades que así lo requieran, se expide, sella y firma digitalmente el presente certificado en la Ciudad de La Rioja, a los <strong>{{ $fechaEmisionTexto }}</strong>.
                @endif
            </div>

            {{-- Pie de firmas y Código QR de Validación en Tiempo Real --}}
            <table class="footer-table">
                <tr>
                    <td class="signature-cell">
                        <div style="height: 40px;"></div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $isEthicsCert && $presidenteEtica ? $presidenteEtica->name : ($secretaria ? $secretaria->name : 'Secretaría General') }}</div>
                        <div class="signature-role">{{ $isEthicsCert && $presidenteEtica ? 'Presidente Tribunal de Ética' : 'Secretaría - Comisión Directiva' }}<br>{{ $school->name }}</div>
                    </td>

                    <td class="qr-cell">
                        @if($certificate->type->has_qr ?? true)
                            <div class="qr-box">
                                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(180)->margin(1)->generate(route('validation.show', $certificate->uuid))) }}" alt="QR de Validación Oficial">
                            </div>
                            <div class="qr-caption">
                                <strong>VALIDACIÓN QR EN TIEMPO REAL</strong><br>
                                Escanee con su celular para verificar autenticidad<br>
                                Código Único: <strong>{{ $certificate->code }}</strong>
                            </div>
                        @endif
                    </td>

                    <td class="signature-cell">
                        <div style="height: 40px;"></div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $presidente ? $presidente->name : 'Presidencia' }}</div>
                        <div class="signature-role">Presidente - Comisión Directiva<br>{{ $school->name }}</div>
                    </td>
                </tr>
            </table>

            <div class="validity-bar">
                Documento electrónico oficial emitido por <strong>{{ $school->name }}</strong> con firma y sello digital verificable mediante código QR.
                @if($certificate->expires_at)
                    | <strong>Validez oficial hasta el: {{ $validoHastaTexto }}</strong> (30 días desde su emisión).
                @endif
            </div>
        </div>
    </div>
</body>
</html>

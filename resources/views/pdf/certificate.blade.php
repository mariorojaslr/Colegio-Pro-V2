<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Oficial - {{ $certificate->type->name ?? 'Certificado de Ética Profesional' }}</title>
    <style>
        /* Página A4 Vertical con márgenes seguros; el certificado ocupa exactamente MEDIA HOJA A4 superior */
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            background-color: #ffffff;
            width: 100%;
        }
        /* Contenedor de Media Hoja A4 (aprox 132mm de alto sobre 297mm totales), garantizando 1 sola página sin desborde */
        .half-page-wrapper {
            width: 100%;
            page-break-inside: avoid;
        }
        /* Tabla exterior para el marco principal (DomPDF respeta el 100% del ancho sin pasarse a la derecha cuando es table) */
        .outer-frame-table {
            width: 100%;
            border-collapse: collapse;
            border: 3px solid {{ $school->primary_color ?? '#1e3a8a' }};
            background-color: #ffffff;
        }
        .outer-frame-cell {
            padding: 5px;
        }
        .inner-frame-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }
        .inner-frame-cell {
            padding: 14px 18px 12px 18px;
            position: relative;
        }
        /* Marca de agua central suave */
        .watermark {
            position: absolute;
            top: 46%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            z-index: -1;
            max-width: 210px;
            max-height: 210px;
        }
        /* Cabecera institucional */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid {{ $school->primary_color ?? '#1e3a8a' }};
            margin-bottom: 10px;
        }
        .header-table td {
            padding-bottom: 8px;
            vertical-align: middle;
        }
        .logo {
            max-width: 58px;
            max-height: 58px;
            display: block;
        }
        .school-name {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: {{ $school->primary_color ?? '#1e3a8a' }};
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .school-sub {
            font-size: 9.5px;
            color: #475569;
            line-height: 1.3;
        }
        .cert-badge {
            display: inline-block;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
            font-size: 8.5px;
            font-weight: bold;
            color: #334155;
            border-radius: 4px;
            text-transform: uppercase;
            text-align: right;
            line-height: 1.35;
        }
        /* Título del Certificado */
        .title-box {
            text-align: center;
            margin: 6px 0 10px 0;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 1.5px;
            margin: 0;
        }
        .subtitle-line {
            width: 90px;
            height: 2.5px;
            background-color: {{ $school->primary_color ?? '#1e3a8a' }};
            margin: 4px auto 0 auto;
        }
        /* Texto introductorio y cuerpo legal */
        .intro-text {
            font-size: 11px;
            color: #334155;
            text-align: center;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        /* Bloque central organizado con los datos del profesional (Ficha Oficial) */
        .data-card-table {
            width: 96%;
            margin: 0 auto 10px auto;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid {{ $school->primary_color ?? '#1e3a8a' }};
        }
        .data-card-table td {
            padding: 7px 12px;
            vertical-align: middle;
        }
        .prof-label {
            font-size: 8.5px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .prof-name {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.3px;
        }
        .prof-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .status-pill {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
            padding: 2px 7px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        /* Párrafo legal conciso y sin desborde */
        .legal-body {
            font-size: 10.8px;
            line-height: 1.48;
            text-align: justify;
            color: #1e293b;
            width: 96%;
            margin: 0 auto 8px auto;
        }
        /* Tabla de Firmas (con espacio para firma escaneada arriba) y QR central */
        .footer-table {
            width: 98%;
            margin: 6px auto 0 auto;
            border-collapse: collapse;
        }
        .signature-cell {
            width: 36%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 6px;
        }
        .qr-cell {
            width: 28%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 4px;
        }
        /* Espacio reservado sobre la línea para firma escaneada o firma ológrafa manual */
        .signature-space {
            height: 44px;
            width: 100%;
            text-align: center;
            vertical-align: bottom;
            margin-bottom: 2px;
        }
        .signature-img {
            max-height: 42px;
            max-width: 140px;
            display: inline-block;
        }
        .signature-line {
            width: 88%;
            border-bottom: 1.2px solid #0f172a;
            margin: 0 auto 4px auto;
        }
        .signature-name {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-role {
            font-size: 9px;
            color: #64748b;
            line-height: 1.2;
        }
        .qr-box {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 4px;
            background: #ffffff;
            border-radius: 4px;
        }
        .qr-box img {
            width: 68px;
            height: 68px;
            display: block;
            margin: 0 auto;
        }
        .qr-caption {
            font-size: 8px;
            color: #475569;
            margin-top: 3px;
            line-height: 1.2;
        }
        .validity-bar {
            margin-top: 8px;
            padding-top: 5px;
            border-top: 1px dashed #cbd5e1;
            font-size: 8.8px;
            color: #64748b;
            text-align: center;
        }
        .cut-line {
            margin-top: 10px;
            border-bottom: 1px dashed #94a3b8;
            text-align: right;
            font-size: 8px;
            color: #94a3b8;
            padding-bottom: 2px;
        }
    </style>
</head>
<body>
    @php
        // Convertimos el logo a base64 para que DomPDF lo incruste siempre sin fallas
        $logoBase64 = null;
        if (!empty($school->logo) && file_exists(public_path($school->logo))) {
            $logoPath = public_path($school->logo);
            $logoExt = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoExt . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Autoridades firmantes
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
        $leftSigner = ($isEthicsCert && $presidenteEtica) ? $presidenteEtica : $secretaria;
        $rightSigner = $presidente;

        // Función auxiliar para convertir la firma escaneada de cada autoridad a base64 si está cargada
        $getSignatureBase64 = function ($member) {
            if ($member && !empty($member->signature_path) && file_exists(public_path($member->signature_path))) {
                $path = public_path($member->signature_path);
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                return 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($path));
            }
            return null;
        };

        $leftSigBase64 = $getSignatureBase64($leftSigner);
        $rightSigBase64 = $getSignatureBase64($rightSigner);

        $fechaEmisionTexto = \Carbon\Carbon::parse($certificate->issued_at ?? now())->locale('es')->translatedFormat('d \d\e F \d\e Y');
        $fechaEmisionCorta = \Carbon\Carbon::parse($certificate->issued_at ?? now())->format('d/m/Y');
        $validoHastaTexto = $certificate->expires_at ? \Carbon\Carbon::parse($certificate->expires_at)->format('d/m/Y') : 'Ilimitada';
    @endphp

    <div class="half-page-wrapper">
        <table class="outer-frame-table">
            <tr>
                <td class="outer-frame-cell">
                    <table class="inner-frame-table">
                        <tr>
                            <td class="inner-frame-cell">
                                @if($logoBase64)
                                    <img src="{{ $logoBase64 }}" class="watermark" alt="Marca de agua">
                                @endif

                                {{-- 1. CABECERA INSTITUCIONAL --}}
                                <table class="header-table">
                                    <tr>
                                        <td style="width: 12%; text-align: left;">
                                            @if($logoBase64)
                                                <img src="{{ $logoBase64 }}" class="logo" alt="Logo">
                                            @endif
                                        </td>
                                        <td style="width: 63%; text-align: left; padding-left: 8px;">
                                            <div class="school-name">{{ $school->name }}</div>
                                            <div class="school-sub">
                                                Personería Jurídica y Jurisdicción Oficial — Provincia de La Rioja, República Argentina
                                                @if($school->cuit) <br>C.U.I.T.: {{ $school->cuit }} @endif
                                                @if($school->address) | Sede: {{ $school->address }} @endif
                                            </div>
                                        </td>
                                        <td style="width: 25%; text-align: right;">
                                            <div class="cert-badge">
                                                CERTIFICADO OFICIAL<br>
                                                <span style="color: #0f172a;">CÓD: {{ $certificate->code ?? 'OFICIAL' }}</span><br>
                                                <span style="font-weight: normal; color: #64748b;">Fecha: {{ $fechaEmisionCorta }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                {{-- 2. TÍTULO DEL CERTIFICADO --}}
                                <div class="title-box">
                                    <h1 class="title">
                                        {{ $isEthicsCert ? 'CERTIFICADO DE ÉTICA PROFESIONAL' : strtoupper($certificate->type->name ?? 'CERTIFICADO OFICIAL') }}
                                    </h1>
                                    <div class="subtitle-line"></div>
                                </div>

                                {{-- 3. CENTRO DE INFORMACIÓN ESTRUCTURADO (FICHA DEL PROFESIONAL) --}}
                                <div class="intro-text">
                                    La Comisión Directiva y el Tribunal de Ética y Disciplina de <strong>{{ $school->name }}</strong> certifican que el/la profesional:
                                </div>

                                <table class="data-card-table">
                                    <tr>
                                        <td colspan="3" style="border-bottom: 1px solid #e2e8f0; text-align: center; padding: 8px 12px;">
                                            <span class="prof-label">Apellido y Nombre del Profesional</span>
                                            <span class="prof-name">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 34%; border-right: 1px solid #e2e8f0; text-align: center;">
                                            <span class="prof-label">Documento de Identidad</span>
                                            <span class="prof-value">D.N.I. {{ $collegiate->dni ?: 'En Legajo' }}</span>
                                        </td>
                                        <td style="width: 33%; border-right: 1px solid #e2e8f0; text-align: center;">
                                            <span class="prof-label">N° de Matrícula Profesional</span>
                                            <span class="prof-value">MATRÍCULA N° {{ $collegiate->registration_number }}</span>
                                        </td>
                                        <td style="width: 33%; text-align: center;">
                                            <span class="prof-label">Estado Institucional</span>
                                            <span class="status-pill">ACTIVO / SIN SANCIONES</span>
                                        </td>
                                    </tr>
                                </table>

                                {{-- 4. CUERPO LEGAL CONCISO Y BIEN ENCUADRADO --}}
                                <div class="legal-body">
                                    Se encuentra legalmente inscripto/a y con matrícula <strong>ACTIVA Y HABILITADA</strong> para el ejercicio profesional en todo el ámbito de la Provincia de La Rioja, dejando expresa constancia de que a la fecha <strong>NO REGISTRA SANCIONES ÉTICAS NI DISCIPLINARIAS</strong> firmes ni causas inhabilitantes en trámite ante el Tribunal de Ética y Disciplina de esta Institución, encontrándose en pleno goce de sus derechos colegiales. Se expide el presente certificado a solicitud del/de la interesado/a para ser presentado ante las autoridades, obras sociales o entidades que lo requieran, en la Ciudad de La Rioja, a los <strong>{{ $fechaEmisionTexto }}</strong>.
                                </div>

                                {{-- 5. PIE CON ESPACIO PARA FIRMA ESCANEADA ARRIBA, LÍNEA DE FIRMA Y CÓDIGO QR EN LA MISMA HOJA --}}
                                <table class="footer-table">
                                    <tr>
                                        <td class="signature-cell">
                                            <div class="signature-space">
                                                @if($leftSigBase64)
                                                    <img src="{{ $leftSigBase64 }}" class="signature-img" alt="Firma Autoridad">
                                                @endif
                                            </div>
                                            <div class="signature-line"></div>
                                            <div class="signature-name">{{ $leftSigner ? $leftSigner->name : 'Lic. Pablo Barrionuevo' }}</div>
                                            <div class="signature-role">
                                                {{ $isEthicsCert ? 'Presidente Tribunal de Ética' : 'Secretaría General' }}<br>
                                                {{ $school->name }}
                                            </div>
                                        </td>

                                        <td class="qr-cell">
                                            @if($certificate->type->has_qr ?? true)
                                                <div class="qr-box">
                                                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(150)->margin(1)->generate(route('validation.show', $certificate->uuid))) }}" alt="QR Validación">
                                                </div>
                                                <div class="qr-caption">
                                                    <strong>VALIDACIÓN QR OFICIAL</strong><br>
                                                    Escanee para verificar validez
                                                </div>
                                            @endif
                                        </td>

                                        <td class="signature-cell">
                                            <div class="signature-space">
                                                @if($rightSigBase64)
                                                    <img src="{{ $rightSigBase64 }}" class="signature-img" alt="Firma Presidencia">
                                                @endif
                                            </div>
                                            <div class="signature-line"></div>
                                            <div class="signature-name">{{ $rightSigner ? $rightSigner->name : 'Lic. Silvia Arce' }}</div>
                                            <div class="signature-role">
                                                Presidente - Comisión Directiva<br>
                                                {{ $school->name }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                {{-- 6. BARRA DE VALIDEZ --}}
                                <div class="validity-bar">
                                    Documento oficial emitido por <strong>{{ $school->name }}</strong> verificable por código QR (<strong>{{ $certificate->code }}</strong>).
                                    @if($certificate->expires_at)
                                        | <strong>Válido hasta: {{ $validoHastaTexto }}</strong> (30 días corridos).
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="cut-line">
            ✂ Formato Oficial Compacto (Media Hoja A4) — Listo para imprimir o presentar digitalmente
        </div>
    </div>
</body>
</html>

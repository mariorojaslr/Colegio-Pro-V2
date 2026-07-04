<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $certificate_type->name }}</title>
    <style>
        @page {
            size: {{ $certificate_type->page_size }} {{ $certificate_type->page_orientation }};
            margin: 0;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
        }

        .page-container {
            position: relative;
            width: 100%;
            height: 100%;
            page-break-after: always;
            box-sizing: border-box;
        }

        .page-container:last-child {
            page-break-after: avoid;
        }

        .background-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .draggable-field {
            position: absolute;
            z-index: 10;
            box-sizing: border-box;
            /* Evitamos que el texto se corte, salvo el cuerpo que tiene max-width */
            white-space: nowrap;
        }
        
        .cuerpo-field {
            position: absolute;
            z-index: 10;
            box-sizing: border-box;
            white-space: normal;
            width: 80%;
            max-width: 80%;
        }
        
        .qr-container {
            position: absolute;
            z-index: 10;
            box-sizing: border-box;
            background-color: #ffffff;
            padding: 2px;
            border: 1px solid #eeeeee;
        }
        
        .qr-container img {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        .firma-field {
            position: absolute;
            z-index: 10;
            box-sizing: border-box;
            text-align: center;
            line-height: 1.3;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    @php
        $bgPath = public_path($certificate_type->background_path);
        $bgBase64 = '';
        if (file_exists($bgPath)) {
            $bgData = file_get_contents($bgPath);
            $bgBase64 = 'data:image/' . pathinfo($bgPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($bgData);
        }
        
        // Configuraciones de diseño consolidadas
        $defaultSettings = [
            'titulo' => ['x' => 50, 'y' => 15, 'font_size' => 28, 'font_weight' => 'bold', 'text_align' => 'center', 'color' => '#1e3a8a', 'text' => 'CERTIFICADO', 'visible' => true],
            'cuerpo' => ['x' => 50, 'y' => 40, 'font_size' => 15, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#333333', 'visible' => true],
            'qr' => ['x' => 84, 'y' => 80, 'width' => 80, 'height' => 80, 'visible' => true],
            'firmas' => []
        ];
        
        $settings = array_merge($defaultSettings, $certificate_type->design_settings ?? []);
    @endphp

    @foreach($collegiates as $collegiate)
        <div class="page-container">
            <!-- Imagen de Fondo -->
            @if($bgBase64)
                <img class="background-img" src="{{ $bgBase64 }}" alt="Fondo Certificado">
            @endif

            <!-- Título de Cabecera -->
            @if($settings['titulo']['visible'] ?? true)
                @php
                    $tAlign = $settings['titulo']['text_align'] ?? 'center';
                    $tStyle = "left: " . $settings['titulo']['x'] . "%; top: " . $settings['titulo']['y'] . "%;";
                    if ($tAlign === 'center') {
                        $tStyle .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($tAlign === 'right') {
                        $tStyle .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $tStyle .= " font-size: " . ($settings['titulo']['font_size'] ?? 28) . "px;";
                    $tStyle .= " font-weight: " . ($settings['titulo']['font_weight'] ?? 'bold') . ";";
                    $tStyle .= " color: " . ($settings['titulo']['color'] ?? '#1e3a8a') . ";";
                    $tStyle .= " text-align: " . $tAlign . ";";
                @endphp
                <div class="draggable-field" style="{{ $tStyle }}">
                    {{ $settings['titulo']['text'] ?? 'CERTIFICADO' }}
                </div>
            @endif

            <!-- Cuerpo del Certificado (Párrafo Libre con Variables) -->
            @if($settings['cuerpo']['visible'] ?? true)
                @php
                    $cAlign = $settings['cuerpo']['text_align'] ?? 'center';
                    $cStyle = "left: " . $settings['cuerpo']['x'] . "%; top: " . $settings['cuerpo']['y'] . "%;";
                    if ($cAlign === 'center') {
                        $cStyle .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($cAlign === 'right') {
                        $cStyle .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $cStyle .= " font-size: " . ($settings['cuerpo']['font_size'] ?? 15) . "px;";
                    $cStyle .= " font-weight: " . ($settings['cuerpo']['font_weight'] ?? 'normal') . ";";
                    $cStyle .= " color: " . ($settings['cuerpo']['color'] ?? '#333333') . ";";
                    $cStyle .= " text-align: " . $cAlign . ";";
                    
                    // Reemplazo de variables dinámicas en PHP
                    $cuerpoTexto = $certificate_type->template_content ?: "Por la presente, el Consejo Directivo certifica y hace constar que el/la profesional:\n\n{{nombre}}\n\nCon documento de identidad Nº {{dni}}, se encuentra debidamente registrado/a en esta Institución bajo la matrícula profesional número {{matricula}}.\n\nSe expide el presente certificado a solicitud del interesado/a, a los {{fecha_emision}}.";
                    
                    $cuerpoTexto = str_replace('{{nombre}}', $collegiate->first_name . ' ' . $collegiate->last_name, $cuerpoTexto);
                    $cuerpoTexto = str_replace('{{dni}}', $collegiate->dni, $cuerpoTexto);
                    $cuerpoTexto = str_replace('{{matricula}}', $collegiate->registration_number, $cuerpoTexto);
                    $cuerpoTexto = str_replace('{{fecha_emision}}', \Carbon\Carbon::parse(now())->format('d/m/Y'), $cuerpoTexto);
                    $cuerpoTexto = str_replace('{{valido_hasta}}', $certificate_type->validity_days ? \Carbon\Carbon::parse(now())->addDays($certificate_type->validity_days)->format('d/m/Y') : 'Ilimitado', $cuerpoTexto);
                @endphp
                <div class="cuerpo-field" style="{{ $cStyle }}">
                    {!! nl2br(e($cuerpoTexto)) !!}
                </div>
            @endif

            <!-- Código QR -->
            @if(($settings['qr']['visible'] ?? true) && $certificate_type->has_qr)
                @php
                    $qrWidth = $settings['qr']['width'] ?? 80;
                    $qrHeight = $settings['qr']['height'] ?? 80;
                    $qrStyle = "left: " . $settings['qr']['x'] . "%; top: " . $settings['qr']['y'] . "%; width: " . $qrWidth . "px; height: " . $qrHeight . "px;";
                    
                    $validationUrl = route('validation.show', $collegiate->uuid ?? \Illuminate\Support\Str::uuid());
                    $qrBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(150)->margin(1)->generate($validationUrl));
                @endphp
                <div class="qr-container" style="{{ $qrStyle }}">
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                </div>
            @endif

            <!-- Firmas de Autoridades Seleccionadas -->
            @foreach($certificate_type->signatories as $signatory)
                @php
                    $fConfig = $settings['firmas'][$signatory->id] ?? null;
                @endphp
                @if($fConfig && ($fConfig['visible'] ?? true))
                    @php
                        $fStyle = "left: " . $fConfig['x'] . "%; top: " . $fConfig['y'] . "%;";
                        // Centrar la firma con respecto a su punto de inserción para DomPDF
                        $fStyle .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                        $fStyle .= " font-size: " . ($fConfig['font_size'] ?? 11) . "px;";
                        $fStyle .= " color: " . ($fConfig['color'] ?? '#047857') . ";";
                    @endphp
                    <div class="firma-field" style="{{ $fStyle }}">
                        <strong>{{ $signatory->name }}</strong><br>
                        <span style="font-size: 8px; color: #666666;">{{ $signatory->role }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach

</body>
</html>

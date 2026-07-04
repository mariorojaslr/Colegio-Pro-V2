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
            white-space: nowrap;
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
        
        // Configuraciones de diseño
        $defaultSettings = [
            'nombre' => ['x' => 50, 'y' => 42, 'font_size' => 24, 'font_weight' => 'bold', 'text_align' => 'center', 'color' => '#1e3a8a', 'visible' => true],
            'dni' => ['x' => 33, 'y' => 55, 'font_size' => 14, 'font_weight' => 'normal', 'text_align' => 'left', 'color' => '#334155', 'visible' => true],
            'matricula' => ['x' => 67, 'y' => 55, 'font_size' => 14, 'font_weight' => 'bold', 'text_align' => 'left', 'color' => '#1e3a8a', 'visible' => true],
            'fecha_emision' => ['x' => 50, 'y' => 70, 'font_size' => 12, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#64748b', 'visible' => true],
            'valido_hasta' => ['x' => 50, 'y' => 76, 'font_size' => 12, 'font_weight' => 'normal', 'text_align' => 'center', 'color' => '#64748b', 'visible' => true],
            'qr' => ['x' => 84, 'y' => 80, 'width' => 80, 'height' => 80, 'visible' => true]
        ];
        
        $settings = array_merge($defaultSettings, $certificate_type->design_settings ?? []);
    @endphp

    @foreach($collegiates as $collegiate)
        <div class="page-container">
            <!-- Imagen de Fondo -->
            @if($bgBase64)
                <img class="background-img" src="{{ $bgBase64 }}" alt="Fondo Certificado">
            @endif

            <!-- Variable Nombre -->
            @if($settings['nombre']['visible'] ?? true)
                @php
                    $align = $settings['nombre']['text_align'] ?? 'center';
                    // Calcular offset para centrado aproximado en DomPDF (ya que absolute usa la esquina izquierda del elemento)
                    // Para text_align center, usamos left: X% y transform translate(-50%)
                    $styleStr = "left: " . $settings['nombre']['x'] . "%; top: " . $settings['nombre']['y'] . "%;";
                    if ($align === 'center') {
                        $styleStr .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($align === 'right') {
                        $styleStr .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $styleStr .= " font-size: " . ($settings['nombre']['font_size'] ?? 24) . "px;";
                    $styleStr .= " font-weight: " . ($settings['nombre']['font_weight'] ?? 'bold') . ";";
                    $styleStr .= " color: " . ($settings['nombre']['color'] ?? '#1e3a8a') . ";";
                @endphp
                <div class="draggable-field" style="{{ $styleStr }}">
                    {{ $collegiate->first_name }} {{ $collegiate->last_name }}
                </div>
            @endif

            <!-- Variable DNI -->
            @if($settings['dni']['visible'] ?? true)
                @php
                    $align = $settings['dni']['text_align'] ?? 'left';
                    $styleStr = "left: " . $settings['dni']['x'] . "%; top: " . $settings['dni']['y'] . "%;";
                    if ($align === 'center') {
                        $styleStr .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($align === 'right') {
                        $styleStr .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $styleStr .= " font-size: " . ($settings['dni']['font_size'] ?? 14) . "px;";
                    $styleStr .= " font-weight: " . ($settings['dni']['font_weight'] ?? 'normal') . ";";
                    $styleStr .= " color: " . ($settings['dni']['color'] ?? '#334155') . ";";
                @endphp
                <div class="draggable-field" style="{{ $styleStr }}">
                    DNI: {{ $collegiate->dni }}
                </div>
            @endif

            <!-- Variable Matrícula -->
            @if($settings['matricula']['visible'] ?? true)
                @php
                    $align = $settings['matricula']['text_align'] ?? 'left';
                    $styleStr = "left: " . $settings['matricula']['x'] . "%; top: " . $settings['matricula']['y'] . "%;";
                    if ($align === 'center') {
                        $styleStr .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($align === 'right') {
                        $styleStr .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $styleStr .= " font-size: " . ($settings['matricula']['font_size'] ?? 14) . "px;";
                    $styleStr .= " font-weight: " . ($settings['matricula']['font_weight'] ?? 'bold') . ";";
                    $styleStr .= " color: " . ($settings['matricula']['color'] ?? '#1e3a8a') . ";";
                @endphp
                <div class="draggable-field" style="{{ $styleStr }}">
                    M.P. Nº {{ $collegiate->registration_number }}
                </div>
            @endif

            <!-- Variable Fecha Emisión -->
            @if($settings['fecha_emision']['visible'] ?? true)
                @php
                    $align = $settings['fecha_emision']['text_align'] ?? 'center';
                    $styleStr = "left: " . $settings['fecha_emision']['x'] . "%; top: " . $settings['fecha_emision']['y'] . "%;";
                    if ($align === 'center') {
                        $styleStr .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($align === 'right') {
                        $styleStr .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $styleStr .= " font-size: " . ($settings['fecha_emision']['font_size'] ?? 12) . "px;";
                    $styleStr .= " font-weight: " . ($settings['fecha_emision']['font_weight'] ?? 'normal') . ";";
                    $styleStr .= " color: " . ($settings['fecha_emision']['color'] ?? '#64748b') . ";";
                @endphp
                <div class="draggable-field" style="{{ $styleStr }}">
                    {{ \Carbon\Carbon::parse(now())->format('d/m/Y') }}
                </div>
            @endif

            <!-- Variable Válido Hasta -->
            @if(($settings['valido_hasta']['visible'] ?? true) && $certificate_type->validity_days)
                @php
                    $align = $settings['valido_hasta']['text_align'] ?? 'center';
                    $styleStr = "left: " . $settings['valido_hasta']['x'] . "%; top: " . $settings['valido_hasta']['y'] . "%;";
                    if ($align === 'center') {
                        $styleStr .= " transform: translate(-50%, 0); -webkit-transform: translate(-50%, 0);";
                    } elseif ($align === 'right') {
                        $styleStr .= " transform: translate(-100%, 0); -webkit-transform: translate(-100%, 0);";
                    }
                    $styleStr .= " font-size: " . ($settings['valido_hasta']['font_size'] ?? 12) . "px;";
                    $styleStr .= " font-weight: " . ($settings['valido_hasta']['font_weight'] ?? 'normal') . ";";
                    $styleStr .= " color: " . ($settings['valido_hasta']['color'] ?? '#64748b') . ";";
                @endphp
                <div class="draggable-field" style="{{ $styleStr }}">
                    Vence el: {{ \Carbon\Carbon::parse(now())->addDays($certificate_type->validity_days)->format('d/m/Y') }}
                </div>
            @endif

            <!-- Bloque Código QR -->
            @if(($settings['qr']['visible'] ?? true) && $certificate_type->has_qr)
                @php
                    $qrWidth = $settings['qr']['width'] ?? 80;
                    $qrHeight = $settings['qr']['height'] ?? 80;
                    $qrStyle = "left: " . $settings['qr']['x'] . "%; top: " . $settings['qr']['y'] . "%; width: " . $qrWidth . "px; height: " . $qrHeight . "px;";
                    
                    // Generación del código QR en base64 para DomPDF
                    $validationUrl = route('validation.show', $collegiate->uuid ?? \Illuminate\Support\Str::uuid());
                    $qrBase64 = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(150)->margin(1)->generate($validationUrl));
                @endphp
                <div class="qr-container" style="{{ $qrStyle }}">
                    <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code">
                </div>
            @endif
        </div>
    @endforeach

</body>
</html>

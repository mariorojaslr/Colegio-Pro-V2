<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $collegiate->registration_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f0f0; font-family: 'Outfit', sans-serif; }
        .certificate-container {
            width: 210mm;
            height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 50px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border: 15px solid #222;
        }
        .outer-border {
            border: 2px solid #ddd;
            height: 100%;
            padding: 20mm;
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 8rem;
            font-weight: 900;
            color: rgba(0,0,0,0.03);
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
        }
        .content-wrap { position: relative; z-index: 10; text-align: center; }
        .school-name { font-family: 'Playfair Display', serif; font-size: 2.2rem; margin-bottom: 2rem; color: #222; }
        .cert-title { letter-spacing: 5px; text-transform: uppercase; font-weight: bold; color: #555; margin-bottom: 3rem; border-bottom: 2px solid #eee; display: inline-block; padding-bottom: 10px; }
        .cert-body { font-size: 1.3rem; line-height: 2; color: #444; margin-bottom: 4rem; text-align: justify; }
        .highlight { font-weight: bold; color: #000; border-bottom: 1px dashed #ccc; }
        .signature-wrap { margin-top: 5rem; }
        .qr-code { width: 120px; height: 120px; background: #eee; margin: 0 auto 10px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: #aaa; }
        
        @media print {
            body { background: white; margin: 0; }
            .certificate-container { margin: 0; box-shadow: none; border: 15px solid #222 !important; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print text-center py-4">
        <button onclick="window.print()" class="btn btn-dark rounded-pill px-5 py-2 fw-bold shadow">
            <i class="bi bi-printer me-2"></i> IMPRIMIR DOCUMENTO
        </button>
        <p class="mt-2 small text-muted">Asegúrese de activar "Gráficos de fondo" en las opciones de impresión.</p>
    </div>

    <div class="certificate-container shadow-2xl">
        <div class="outer-border">
            <div class="watermark">HABILITADO</div>
            
            <div class="content-wrap">
                <div class="mb-4">
                    {{-- Logo del Colegio --}}
                    <h5 class="fw-bold text-uppercase ls-1 text-primary">{{ $collegiate->school->name }}</h5>
                </div>

                <h1 class="school-name">Consejo Profesional de Ley y Ética</h1>
                
                <div class="cert-title">Certificado de Habilitación</div>

                <div class="cert-body">
                    Hacemos constar de manera formal y oficial que el profesional 
                    <span class="highlight text-uppercase">{{ $collegiate->first_name }} {{ $collegiate->last_name }}</span>, 
                    con DNI <span class="highlight">{{ $collegiate->dni }}</span> y Matrícula Profesional N° 
                    <span class="highlight">{{ $collegiate->registration_number }}</span>, se encuentra en la fecha 
                    <span class="highlight">{{ now()->format('d/m/Y') }}</span> con su estado de revista <span class="text-success fw-bold">VIGENTE Y HABILITADO</span> 
                    para el libre ejercicio de su profesión en toda la jurisdicción correspondiente a este Consejo Profesional.
                    <br><br>
                    El presente certificado valida que el matriculado no posee sanciones éticas vigentes, se encuentra al día con sus obligaciones
                    financieras y ha cumplimentado con la totalidad de la documentación requerida por la normativa institucional.
                </div>

                <div class="row mt-5 align-items-end">
                    <div class="col-4 text-center">
                        <div class="qr-code">
                             [ QR VALIDACIÓN ]
                        </div>
                        <small class="text-muted" style="font-size: 10px">ID Verificación: {{ strtoupper(substr(md5($collegiate->id), 0, 10)) }}</small>
                    </div>
                    <div class="col-4 text-center">
                        <div style="border-bottom: 2px solid #333; width: 80%; margin: 0 auto 10px"></div>
                        <p class="small fw-bold mb-0">Secretaría Administrativa</p>
                        <p class="small text-muted">{{ $collegiate->school->name }}</p>
                    </div>
                    <div class="col-4 text-center">
                        <div style="border-bottom: 2px solid #333; width: 80%; margin: 0 auto 10px"></div>
                        <p class="small fw-bold mb-0">Presidencia del Consejo</p>
                        <p class="small text-muted">Gestión Pro 2026-2030</p>
                    </div>
                </div>

                <div class="mt-5 pt-5">
                    <p class="text-muted" style="font-size: 11px">
                        * Este documento tiene una validez de 30 días corridos a partir de su emisión. <br>
                        Verifique la autenticidad escaneando el código QR o ingresando al portal oficial del Colegio.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-activar diálogo de impresión tras carga
        window.onload = function() {
            // window.print();
        };
    </script>

</body>
</html>

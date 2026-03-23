<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #0f172a;
        }
        .container {
            width: 100%;
            height: 100%;
            padding: 50px;
            box-sizing: border-box;
            border: 20px solid #0f172a;
            position: relative;
        }
        .gold-border {
            position: absolute;
            top: 10px; left: 10px; right: 10px; bottom: 10px;
            border: 2px solid #d4af37;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .logo {
            height: 80px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 50px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 10px;
            color: #1e293b;
        }
        .subtitle {
            font-size: 18px;
            font-weight: bold;
            color: #64748b;
            letter-spacing: 2px;
            margin-bottom: 50px;
        }
        .content {
            text-align: center;
            line-height: 1.6;
        }
        .certifies {
            font-size: 20px;
            font-style: italic;
            margin-bottom: 30px;
            color: #475569;
        }
        .user-name {
            font-size: 45px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 30px;
            border-bottom: 2px solid #f1f5f9;
            display: inline-block;
            padding-bottom: 5px;
        }
        .description {
            font-size: 18px;
            margin-bottom: 40px;
            max-width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .footer {
            margin-top: 80px;
            text-align: center;
        }
        .signature-box {
            display: inline-block;
            width: 250px;
            border-top: 1px solid #94a3b8;
            padding-top: 10px;
            margin: 0 50px;
        }
        .signature-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-title {
            font-size: 11px;
            color: #64748b;
        }
        .verification {
            position: absolute;
            bottom: 60px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            left: 0;
        }
        .code-box {
            background: #f8fafc;
            padding: 10px 20px;
            border-radius: 5px;
            font-family: monospace;
            display: inline-block;
            margin-top: 10px;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="gold-border"></div>
        
        <div class="header">
            <div class="title">Certificado Académico</div>
            <div class="subtitle">COLEGIO-PRO | ESCUELA VIRTUAL</div>
        </div>

        <div class="content">
            <div class="certifies">Por la presente se certifica que</div>
            <div class="user-name">{{ strtoupper($certificate->user->name) }}</div>
            
            <div class="description">
                ha completado satisfactoriamente y aprobado la evaluación final del curso internacional de especialización titulado: <br><br>
                <span class="course-name">"{{ $certificate->lesson->title }}"</span>
            </div>

            <div class="certifies" style="margin-top: 20px;">
                Carga horaria: {{ $certificate->lesson->duration }} • Fecha de emisión: {{ $certificate->issued_at->format('d/m/Y') }}
            </div>
        </div>

        <div class="footer">
            <div class="signature-box">
                <div class="signature-name">Dirección Académica</div>
                <div class="signature-title">Colegio-Pro V2</div>
            </div>
            <div class="signature-box">
                <div class="signature-name">Secretaría General</div>
                <div class="signature-title">Validación Institucional</div>
            </div>
        </div>

        <div class="verification">
            <div>Para verificar la autenticidad de este certificado, escanee el código QR o ingrese este código en nuestra web:</div>
            <div class="code-box">VERIFICACIÓN ID: {{ $certificate->code }}</div>
        </div>
    </div>
</body>
</html>

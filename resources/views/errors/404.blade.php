<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada | Colegio-Pro</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@700;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #0f172a;
            --accent: #2563eb;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: var(--primary);
        }

        .error-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }

        .error-code {
            font-family: 'Outfit', sans-serif;
            font-size: 10rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            opacity: 0.1;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .error-icon {
            font-size: 5rem;
            color: var(--accent);
            margin-bottom: 30px;
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 2.5rem;
            margin-bottom: 15px;
            letter-spacing: -1px;
        }

        p {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        .btn-back {
            background-color: var(--primary);
            color: white;
            border-radius: 100px;
            padding: 15px 40px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.3);
            color: white;
        }

        .brand-footer {
            margin-top: 60px;
            opacity: 0.5;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <div class="error-code">404</div>

    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-geo-alt-fill"></i>
        </div>
        <h1>Página No Encontrada</h1>
        <p>Parece que el recurso que busca ha sido movido o ya no se encuentra disponible en nuestros servidores.</p>
        
        <div class="mt-5">
            <a href="/" class="btn-back">VOLVER AL INICIO</a>
        </div>

        <div class="brand-footer">COLEGIO-PRO 2026</div>
    </div>

</body>
</html>

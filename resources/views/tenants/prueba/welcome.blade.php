<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $school->name ?? 'Plataforma Demo' }}</title>
    
    <link rel="icon" type="image/png" href="{{ isset($school) && $school->logo ? asset($school->logo) : asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Fuente Tech/Clean -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --brand-main: #10b981; /* Esmeralda */
            --brand-dark: #0f172a; /* Slate 900 */
            --brand-light: #f8fafc; /* Slate 50 */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #334155;
            background-color: var(--brand-light);
        }

        h1, h2, h3, .grotesk {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* NAVBAR */
        .navbar-tech {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
        }

        /* HERO */
        @php
            $bgImage = isset($slider) && $slider->items->count() > 0 ? $slider->items->first()->image_url : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80';
        @endphp
        .hero-tech {
            padding: 150px 0 100px;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.1), transparent), 
                        url('{{ $bgImage }}');
            background-blend-mode: overlay;
            background-size: cover;
            background-position: center;
        }

        .btn-tech {
            background-color: var(--brand-main);
            color: #fff;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        .btn-tech:hover {
            background-color: #059669;
            color: #fff;
            transform: translateY(-2px);
        }

        /* CARDS */
        .tech-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 30px;
            transition: 0.3s;
            height: 100%;
        }
        .tech-card:hover {
            border-color: var(--brand-main);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .node-tech {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            text-align: center;
            width: 220px;
        }
        .node-tech img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
        }
    
        #chatbot-trigger {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        #chatbot-trigger:hover {
            transform: scale(1.15) rotate(-5deg);
            box-shadow: 0 15px 25px rgba(0,0,0,0.2) !important;
        }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-tech fixed-top">
        <div class="container-fluid px-4 px-xl-5 d-flex align-items-center justify-content-between py-2">
            <a class="navbar-brand grotesk fw-bold d-flex align-items-center gap-2" href="/" style="color: var(--brand-dark);">
                @if(isset($school) && $school->logo)
                    <img src="{{ asset($school->logo) }}" alt="Logo" style="height: 80px;">
                @else
                    <span class="material-icons" style="color: var(--brand-main);">cloud_done</span>
                @endif
                {{ $school->name ?? 'Demo Plataforma' }}
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#demoNav">
                <span class="material-icons" style="color: var(--brand-dark);">menu</span>
            </button>

            <div class="collapse navbar-collapse" id="demoNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#quienes-somos">Características</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#novedades">Novedades</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#autoridades">Autoridades</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-bold" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn-tech">
                        Acceder <span class="material-icons" style="font-size: 1.2rem;">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @php
        $sliderItems = isset($slider) && $slider->items->count() > 0 ? $slider->items : collect([]);
    @endphp

    @if($sliderItems->count() > 0)
        <!-- SLIDER ACTIVO: Muestra solo las imágenes (Tapa todo) -->
        <section class="p-0 position-relative" style="height: 100vh; overflow: hidden; background-color: #000;">
            <div id="heroCarouselDemo" class="carousel slide carousel-fade w-100 h-100" data-bs-ride="carousel">
                <div class="carousel-inner h-100">
                    @foreach($sliderItems as $index => $item)
                        <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}" data-bs-interval="5000">
                            @php
                                $imgSrc = Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/'.$item->image_url);
                            @endphp
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" class="d-block w-100 h-100">
                                    <img src="{{ $imgSrc }}" class="d-block w-100 h-100" style="object-fit: cover; object-position: center;" alt="{{ $item->title ?? 'Slider' }}">
                                </a>
                            @else
                                <img src="{{ $imgSrc }}" class="d-block w-100 h-100" style="object-fit: cover; object-position: center;" alt="{{ $item->title ?? 'Slider' }}">
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($sliderItems->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarouselDemo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarouselDemo" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: rgba(0,0,0,0.5); border-radius: 50%; padding: 20px;"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
                @endif
            </div>
        </section>
    @else
        <!-- SIN SLIDER: Muestra el diseño tradicional azul con texto -->
        <section class="hero-tech">
            <div class="container-fluid px-4 px-xl-5 text-center">
                <span class="badge bg-light text-success border border-success mb-3 px-3 py-2 rounded-pill shadow-sm">Plataforma V2 Activa</span>
                <h1 class="display-4 fw-bold mb-4" style="color: #ffffff; text-shadow: 0 4px 15px rgba(0,0,0,0.9);">Tu organización,<br>en la nube.</h1>
                <p class="fs-5 mb-5 mx-auto" style="max-width: 600px; color: #f8fafc; text-shadow: 0 2px 8px rgba(0,0,0,0.9);">
                    Gestión inteligente para colegios profesionales. Matrículas, cobros, noticias y portal de colegiados en un solo lugar.
                </p>
            </div>
        </section>
    @endif

    <main class="container-fluid px-4 px-xl-5 py-5">
        
        <!-- STATS & SERVICES -->
        <div id="quienes-somos" class="row g-4 mb-5 pb-5 border-bottom pt-5">
            <div class="col-12 mb-5 text-center">
                <div class="row g-3 bg-white p-4 rounded-4 shadow-sm border">
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 grotesk" style="color: var(--brand-main);">+{{ $school->collegiates()->count() ?? 0 }}</h2>
                        <p class="text-muted small mt-2 fw-bold">Profesionales Matriculados</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 grotesk" style="color: var(--brand-main);">{{ \Carbon\Carbon::parse('1990-12-20')->age }}</h2>
                        <p class="text-muted small mt-2 fw-bold">Años de Trayectoria</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 grotesk" style="color: var(--brand-main);">18</h2>
                        <p class="text-muted small mt-2 fw-bold">Departamentos de La Rioja</p>
                    </div>
                    <div class="col-6 col-md-3">
                        <h2 class="display-5 fw-bold mb-0 grotesk" style="color: var(--brand-main);">1</h2>
                        <p class="text-muted small mt-2 fw-bold">Convenios Vigentes</p>
                    </div>
                </div>
            </div>
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">speed</span>
                    <h4 class="grotesk fw-bold">Gestión Ágil</h4>
                    <p class="text-muted small">Automatizá la revisión de legajos y el pago de cuotas mensuales de forma simple.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">verified_user</span>
                    <h4 class="grotesk fw-bold">Seguridad Total</h4>
                    <p class="text-muted small">Validación de certificados por código QR y perfiles con auditoría completa.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tech-card text-center">
                    <span class="material-icons mb-3" style="font-size: 3rem; color: var(--brand-main);">devices</span>
                    <h4 class="grotesk fw-bold">Multi-dispositivo</h4>
                    <p class="text-muted small">Tus matriculados pueden acceder desde cualquier lugar con un diseño responsivo y PWA.</p>
                </div>
            </div>
        </div>

        <!-- NOTICIAS -->
        <div id="novedades" class="mb-5 pb-5 border-bottom pt-5">
            <h2 class="grotesk fw-bold mb-4" style="color: var(--brand-dark);">Últimos Updates</h2>
            @if(isset($latestNews) && $latestNews->count() > 0)
            <div class="row g-4">
                @foreach($latestNews as $news)
                <div class="col-md-4">
                    <div class="tech-card p-0 overflow-hidden">
                        @if($news->image_path)
                            <img src="{{ asset($news->image_path) }}" class="w-100" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="w-100 d-flex align-items-center justify-content-center" style="height: 180px; background: linear-gradient(135deg, var(--brand-main, #10b981) 0%, rgba(16, 185, 129, 0.8) 100%); position: relative; overflow: hidden;">
                                <div style="position: absolute; width: 150%; height: 150%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%); top: -25%; left: -25%;"></div>
                                @if(isset($school) && $school->logo)
                                    <img src="{{ asset($school->logo) }}" alt="Logo" style="max-height: 90px; opacity: 0.25; filter: grayscale(100%) brightness(200%); position: relative; z-index: 1;">
                                @else
                                    <span class="material-icons text-white" style="font-size: 5rem; opacity: 0.15; position: relative; z-index: 1;">article</span>
                                @endif
                            </div>
                        @endif
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <small class="text-muted fw-bold d-block mb-2">{{ $news->published_at->format('d M, Y') }}</small>
                            <h5 class="fw-bold" style="line-height: 1.4;">{{ $news->title }}</h5>
                            <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit(strip_tags($news->content), 100) }}</p>
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none fw-bold mt-auto d-inline-block" style="color: var(--brand-main);">Ver detalles &rarr;</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-light border text-center p-4">
                <p class="mb-0 text-muted">No hay actualizaciones en el sistema de noticias.</p>
            </div>
            @endif
        </div>

        <!-- AUTORIDADES -->
        <div id="autoridades" class="mb-5 pt-5">
            <h2 class="grotesk fw-bold mb-4 text-center" style="color: var(--brand-dark);">Equipo Demo</h2>
            @if(isset($boardMembers) && $boardMembers->count() > 0)
                @foreach($boardMembers as $department => $members)
                    <h5 class="text-center text-muted mb-4 mt-5">{{ $department }}</h5>
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        @foreach($members as $m)
                        <div class="node-tech">
                            @php
                                $mName = $m->collegiate ? $m->collegiate->first_name . ' ' . $m->collegiate->last_name : $m->name;
                                $mImageUrl = $m->collegiate && $m->collegiate->avatar_url ? $m->collegiate->avatar_url : $m->image_path;
                            @endphp
                            <img src="{{ $mImageUrl }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mName) }}&background=10b981&color=fff'">
                            <h6 class="fw-bold mb-1">{{ $mName }}</h6>
                            <small class="text-muted">{{ $m->role }}</small>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="alert alert-light border text-center p-4">
                    <p class="mb-0 text-muted">Aún no hay autoridades configuradas para esta instancia.</p>
                </div>
            @endif
        </div>
        </div>

        <!-- CONTACTO -->
        <div id="contacto" class="mb-5 pt-5 border-top">
            <h2 class="grotesk fw-bold mb-4 text-center" style="color: var(--brand-dark);">Contacto</h2>
            <div class="row g-4">
                <div class="col-lg-5">
                    <ul class="list-unstyled">
                        <li class="d-flex mb-4 align-items-center">
                            <span class="material-icons me-3 fs-3" style="color: var(--brand-main);">location_on</span>
                            <div>
                                <strong class="d-block grotesk">Dirección</strong>
                                <span class="text-muted">{{ $school->address ?? 'San Martin 123' }}</span>
                            </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <span class="material-icons me-3 fs-3" style="color: var(--brand-main);">phone</span>
                            <div>
                                <strong class="d-block grotesk">Teléfono</strong>
                                <span class="text-muted">{{ $school->phone ?? '(011) 456-7890' }}</span>
                            </div>
                        </li>
                        <li class="d-flex mb-4 align-items-center">
                            <span class="material-icons me-3 fs-3" style="color: var(--brand-main);">email</span>
                            <div>
                                <strong class="d-block grotesk">Mail</strong>
                                <span class="text-muted">{{ $school->email ?? 'info@demo.com' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-7">
                    @php
                        $mapQuery = null;
                        if(isset($school) && $school->latitude && $school->longitude) {
                            $mapQuery = $school->latitude . ',' . $school->longitude;
                        } elseif (isset($school) && $school->plus_code) {
                            $mapQuery = $school->plus_code . ' ' . $school->address;
                        } elseif (isset($school) && $school->address) {
                            $mapQuery = $school->address;
                        }
                    @endphp

                    @if(isset($school) && $school->map_embed_code)
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 300px; border: 1px solid #e2e8f0;">
                            {!! $school->map_embed_code !!}
                        </div>
                    @elseif($mapQuery)
                        <div class="rounded-4 overflow-hidden shadow-sm h-100" style="min-height: 300px; border: 1px solid #e2e8f0;">
                            <iframe width="100%" height="100%" style="border:0; min-height: 300px;" loading="lazy" allowfullscreen 
                                src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=17&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                    @else
                        <div class="rounded-4 bg-light d-flex align-items-center justify-content-center h-100 shadow-sm border" style="min-height: 300px;">
                            <div class="text-center">
                                <span class="material-icons text-muted mb-2" style="font-size: 3rem;">map</span>
                                <p class="text-muted mb-0">Mapa no configurado</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <footer class="py-5 border-top" style="background-color: #fff;">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Graficar Software de Mario Rojas. Todos los derechos reservados.</p>
        </div>
    </footer>


    <!-- Chatbot Widget -->
    <div id="chatbot-widget" class="position-fixed" style="bottom: 120px; right: 25px; z-index: 1050; width: 400px; height: 550px; display: none; resize: both; overflow: hidden; min-width: 300px; min-height: 400px; max-width: 90vw; max-height: 90vh; background: transparent;">
        <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; overflow: hidden; display: flex; flex-direction: column;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3" id="chatbot-header" style="cursor: move;">
                <div class="fw-bold d-flex align-items-center">
                    <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" class="me-2 shadow-sm" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; pointer-events: none;">
                    Asistente Virtual
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
            </div>
            <div class="card-body bg-light flex-grow-1" id="chatbot-messages" style="overflow-y: auto;">
                <div class="d-flex mb-3">
                    <div class="bg-white text-dark p-3 rounded-4 shadow-sm" style="max-width: 85%;">
                        Hola 👋 Soy el asistente virtual del {{ $school->name ?? 'Colegio' }}. ¿En qué te puedo ayudar hoy?
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <form id="chatbot-form" class="d-flex gap-2" onsubmit="sendChatMessage(event)">
                    <input type="text" id="chatbot-input" class="form-control rounded-pill bg-light border-0 px-3" placeholder="Escribe tu consulta..." required>
                    <button type="submit" class="btn btn-primary rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bi bi-send"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <button id="chatbot-trigger" class="btn btn-light border border-2 border-primary rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center p-0" style="bottom: 25px; right: 25px; z-index: 1040; width: 95px; height: 95px; background-color: white !important; overflow: hidden;" onclick="toggleChatbot()">
        <img src="{{ asset('media/bot_icon.png') }}" alt="Bot" style="width: 100%; height: 100%; object-fit: cover;">
    </button>

    <script>
        const chatbotWidget = document.getElementById('chatbot-widget');

        function toggleChatbot() {
            if (chatbotWidget.style.display === 'none' || chatbotWidget.style.display === '') {
                chatbotWidget.style.display = 'block';
            } else {
                chatbotWidget.style.display = 'none';
            }
        }
        
        // Draggable logic
        let isDragging = false;
        let currentX;
        let currentY;
        let initialX;
        let initialY;
        let xOffset = 0;
        let yOffset = 0;

        const header = document.getElementById("chatbot-header");

        header.addEventListener("mousedown", dragStart);
        document.addEventListener("mouseup", dragEnd);
        document.addEventListener("mousemove", drag);

        function dragStart(e) {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
            if (e.target === header || e.target.parentNode === header) {
                isDragging = true;
            }
        }

        function dragEnd(e) {
            initialX = currentX;
            initialY = currentY;
            isDragging = false;
        }

        function drag(e) {
            if (isDragging) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
                xOffset = currentX;
                yOffset = currentY;
                setTranslate(currentX, currentY, chatbotWidget);
            }
        }

        function setTranslate(xPos, yPos, el) {
            el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
        }

        async function sendChatMessage(e) {
            e.preventDefault();
            const input = document.getElementById('chatbot-input');
            const message = input.value.trim();
            if (!message) return;

            const messagesDiv = document.getElementById('chatbot-messages');
            
            // Append user message
            messagesDiv.innerHTML += `
                <div class="d-flex mb-3 justify-content-end">
                    <div class="bg-primary text-white p-3 rounded-4 shadow-sm" style="max-width: 85%;">${message}</div>
                </div>
            `;
            
            input.value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            // Fetch response
            try {
                const response = await fetch('{{ route("chatbot.ask") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: message, school_id: '{{ $school->id ?? 1 }}' })
                });

                const data = await response.json();
                
                // Append bot message
                messagesDiv.innerHTML += `
                    <div class="d-flex mb-3">
                        <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 85%;">${data.answer}</div>
                    </div>
                `;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
</body>
</html>

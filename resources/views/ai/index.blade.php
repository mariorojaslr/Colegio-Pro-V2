@extends('layouts.main')

@section('title', 'Lili - Tu Asistente Personal | Colegio-Pro')

@section('content')
<div class="container-fluid py-4" style="height: calc(100vh - 120px)">
    <div class="row h-100 g-4">
        <!-- Sidebar de Prompts Rápidos -->
        <div class="col-lg-3 d-none d-lg-block h-100">
            <div class="glass-card h-100 p-4 border-0 shadow-sm overflow-auto">
                <div class="text-center mb-4">
                    <img src="{{ asset('media/lili_avatar.png') }}" class="rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;" alt="Lili">
                    <h5 class="fw-bold mt-2 mb-0" style="color: #db2777">Lili</h5>
                    <p class="small text-muted mb-0">Tu Asistente Personal</p>
                </div>
                
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-stars me-1 text-warning"></i> Acciones Rápidas</h6>
                <div class="list-group list-group-flush gap-2">
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Quiero subir mi título universitario para el legajo.">
                        <h6 class="fw-bold mb-1 small"><i class="bi bi-camera text-primary me-2"></i> Subir Documento</h6>
                        <p class="small text-muted mb-0">Abre la cámara para escanear y subir.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Redactame un informe de evolución para el alumno Pepito.">
                        <h6 class="fw-bold mb-1 small"><i class="bi bi-file-earmark-text text-primary me-2"></i> Redactar Informe</h6>
                        <p class="small text-muted mb-0">Creación automática de informes.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Generá el informe de todos los pacientes de este mes y mandáselos a mi supervisor.">
                        <h6 class="fw-bold mb-1 small"><i class="bi bi-envelope-paper text-primary me-2"></i> Cierre de Mes</h6>
                        <p class="small text-muted mb-0">Envíos masivos automatizados.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="¿Cuánto debo de matrícula? Quiero pagar mi cuota.">
                        <h6 class="fw-bold mb-1 small"><i class="bi bi-cash-coin text-primary me-2"></i> Estado de Cuenta</h6>
                        <p class="small text-muted mb-0">Consulta de saldos y pagos.</p>
                    </button>
                </div>
            </div>
        </div>

        <!-- Ventana de Chat -->
        <div class="col-lg-9 h-100 d-flex flex-column">
            <div class="glass-card flex-grow-1 p-4 border-0 shadow-sm mb-3 d-flex flex-column" id="chatWindow" style="overflow-y: auto; border-radius: 30px">
                <div class="text-center my-auto p-5" id="emptyState">
                    <img src="{{ asset('media/lili_avatar.png') }}" class="rounded-circle shadow-lg mb-4" style="width: 120px; height: 120px; object-fit: cover;" alt="Lili">
                    <h3 class="fw-bold" style="font-family: 'Outfit', sans-serif; color: #db2777">¡Hola! Soy Lili.</h3>
                    <p class="text-muted">Soy tu secretaria y asistente personal. Puedo ayudarte a redactar notas, generar reportes, verificar tu deuda o subir archivos de tu legajo. ¡Pídeme lo que necesites!</p>
                </div>
                <div id="messagesContainer" class="d-none">
                    <!-- Mensajes dinámicos aquí -->
                </div>
            </div>

            <!-- Input de Chat -->
            <div class="glass-card p-3 border-0 shadow-lg" style="border-radius: 25px">
                <form id="aiForm" class="d-flex gap-2">
                    @csrf
                    <button type="button" class="btn btn-light rounded-circle shadow-sm" style="width: 55px; height: 55px" onclick="alert('Dictado por voz en desarrollo')">
                        <i class="bi bi-mic fs-5"></i>
                    </button>
                    <input type="text" id="userInput" class="form-control rounded-pill border-0 bg-light px-4 shadow-none py-3" 
                           placeholder="Dime qué necesitas que haga por ti..." autocomplete="off">
                    <button type="submit" class="btn text-white rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" 
                            style="width: 55px; height: 55px; background: #db2777;" id="sendBtn">
                        <i class="bi bi-send-fill fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Oculto para uso de deep link de cámara -->
<input type="file" id="cameraInput" accept="image/*" capture="environment" style="display:none;" onchange="alert('Foto capturada! Simulando subida...')">

<style>
    #chatWindow::-webkit-scrollbar { width: 6px; }
    #chatWindow::-webkit-scrollbar-track { background: transparent; }
    #chatWindow::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .message-bubble { max-width: 80%; padding: 15px 20px; border-radius: 20px; margin-bottom: 20px; animation: slideUp 0.3s ease-out; }
    .ai-bubble { background: #fdf2f8; border: 1px solid #fbcfe8; color: #831843; align-self: flex-start; }
    .user-bubble { background: #1e293b; color: white; align-self: flex-end; }
    .deep-action-card { background: white; border: 1px solid #e2e8f0; border-radius: 15px; padding: 15px; margin-top: 10px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    const aiForm = document.getElementById('aiForm');
    const userInput = document.getElementById('userInput');
    const messagesContainer = document.getElementById('messagesContainer');
    const emptyState = document.getElementById('emptyState');
    const chatWindow = document.getElementById('chatWindow');
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');

    suggestionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            userInput.value = btn.getAttribute('data-prompt');
            userInput.focus();
        });
    });

    aiForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = userInput.value.trim();
        if(!text) return;

        // Limpiar input y ocular estado vacío
        userInput.value = '';
        emptyState.classList.add('d-none');
        messagesContainer.classList.remove('d-none');
        messagesContainer.classList.add('d-flex', 'flex-column');

        // Añadir mensaje de usuario
        addMessage(text, 'user');
        
        // Mock de carga de IA (mientras conectamos Gemini)
        const loadingId = addMessage('Analizando tu pedido...', 'ai', true);
        
        try {
            const response = await fetch('{{ route("ai.query") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: text })
            });
            
            const data = await response.json();
            updateMessage(loadingId, data.response);
            
            // Handle Deep Actions
            if(data.action_type && data.action_type !== 'none') {
                handleDeepAction(data.action_type, loadingId);
            }

        } catch (error) {
            updateMessage(loadingId, 'Lo siento, ha ocurrido un error al conectar con mi cerebro.');
        }
    });

    function addMessage(content, type, isLoading = false) {
        const div = document.createElement('div');
        div.className = `message-bubble ${type}-bubble shadow-sm`;
        div.id = `msg-${Date.now()}`;
        
        if(type === 'ai') {
            div.innerHTML = `<div class="d-flex gap-3"><img src="{{ asset('media/lili_avatar.png') }}" class="rounded-circle" style="width:30px;height:30px"><div>${content}</div></div>`;
        } else {
            div.innerHTML = content;
        }

        messagesContainer.appendChild(div);
        chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
        return div.id;
    }

    function updateMessage(id, content) {
        const msg = document.getElementById(id);
        if(msg) {
            msg.innerHTML = `<div class="d-flex gap-3"><img src="{{ asset('media/lili_avatar.png') }}" class="rounded-circle" style="width:30px;height:30px"><div>${content}</div></div>`;
        }
        chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
    }

    function handleDeepAction(actionType, msgId) {
        const msgDiv = document.getElementById(msgId);
        let actionHtml = '';

        if(actionType === 'upload_document') {
            document.getElementById('cameraInput').click(); // Abre la cámara nativa
            actionHtml = `
            <div class="deep-action-card mt-3">
                <h6 class="fw-bold text-dark"><i class="bi bi-camera me-2 text-primary"></i> Cámara Abierta</h6>
                <p class="small text-muted mb-0">Enfoca tu documento y toma la foto. Yo me encargo del resto.</p>
            </div>`;
        } 
        else if (actionType === 'draft_document') {
            actionHtml = `
            <div class="deep-action-card mt-3">
                <h6 class="fw-bold text-dark"><i class="bi bi-file-earmark-text me-2 text-primary"></i> Documento Redactado</h6>
                <div class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="alert('Copiado al portapapeles!')"><i class="bi bi-clipboard me-1"></i> Copiar</button>
                    <button class="btn btn-sm btn-primary"><i class="bi bi-download me-1"></i> Descargar PDF</button>
                </div>
            </div>`;
        }
        else if (actionType === 'batch_email_reports') {
            actionHtml = `
            <div class="deep-action-card mt-3">
                <h6 class="fw-bold text-dark"><i class="bi bi-envelope-check me-2 text-success"></i> Tarea Batch Ejecutándose</h6>
                <div class="progress mt-2" style="height: 10px;">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%"></div>
                </div>
                <p class="small text-muted mt-2 mb-0">Generando informes cruzados y preparando el envío. Te avisaré cuando termine.</p>
            </div>`;
        }

        if(actionHtml) {
            msgDiv.querySelector('div > div').innerHTML += actionHtml;
        }
    }
</script>
@endsection

@extends('layouts.main')

@section('title', 'Asistente IA | Colegio-Pro')

@section('content')
<div class="container-fluid py-4" style="height: calc(100vh - 120px)">
    <div class="row h-100 g-4">
        <!-- Sidebar de Prompts Rápidos -->
        <div class="col-lg-3 d-none d-lg-block h-100">
            <div class="glass-card h-100 p-4 border-0 shadow-sm overflow-auto">
                <h5 class="fw-bold mb-4" style="color: var(--primary-color)"><i class="bi bi-robot me-2"></i> Sugerencias IA</h5>
                <div class="list-group list-group-flush gap-2">
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Necesito redactar una nota oficial para presentar en el Colegio de Profesional sobre...">
                        <h6 class="fw-bold mb-1 small">Nota Oficial</h6>
                        <p class="small text-muted mb-0">Estructura formal para el colegio.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="¿Cuál es el procedimiento técnico recomendado para...?">
                        <h6 class="fw-bold mb-1 small">Investigar Técnica</h6>
                        <p class="small text-muted mb-0">Consultas sobre normativas o técnicas.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Ayúdame a armar un formulario de relevamiento para...">
                        <h6 class="fw-bold mb-1 small">Armar Formulario</h6>
                        <p class="small text-muted mb-0">Guía para relevamiento de datos.</p>
                    </button>
                    <button class="list-group-item list-group-item-action border-0 rounded-4 p-3 bg-light-subtle suggestion-btn" data-prompt="Diseña una base para una nota de honorarios profesionales por...">
                        <h6 class="fw-bold mb-1 small">Base para Honorarios</h6>
                        <p class="small text-muted mb-0">Plantilla para cobro de servicios.</p>
                    </button>
                </div>
                
                <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-4 text-center">
                    <p class="small text-muted mb-0">Potenciado por</p>
                    <h6 class="fw-bold text-primary mb-0">Google Gemini</h6>
                </div>
            </div>
        </div>

        <!-- Ventana de Chat -->
        <div class="col-lg-9 h-100 d-flex flex-column">
            <div class="glass-card flex-grow-1 p-4 border-0 shadow-sm mb-3 d-flex flex-column" id="chatWindow" style="overflow-y: auto; border-radius: 30px">
                <div class="text-center my-auto p-5" id="emptyState">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-primary mx-auto mb-4" style="width: 100px; height: 100px">
                        <i class="bi bi-stars fs-1"></i>
                    </div>
                    <h3 class="fw-bold" style="font-family: 'Outfit', sans-serif;">¿En qué puedo ayudarte hoy?</h3>
                    <p class="text-muted">Pregúntame sobre cualquier procedimiento, normativa o redactemos juntos una nota profesional.</p>
                </div>
                <div id="messagesContainer" class="d-none">
                    <!-- Mensajes dinámicos aquí -->
                </div>
            </div>

            <!-- Input de Chat -->
            <div class="glass-card p-3 border-0 shadow-lg" style="border-radius: 25px">
                <form id="aiForm" class="d-flex gap-2">
                    @csrf
                    <input type="text" id="userInput" class="form-control rounded-pill border-0 bg-light px-4 shadow-none py-3" 
                           placeholder="Escribe tu consulta aquí..." autocomplete="off">
                    <button type="submit" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" 
                            style="width: 55px; height: 55px" id="sendBtn">
                        <i class="bi bi-send-fill fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #chatWindow::-webkit-scrollbar { width: 6px; }
    #chatWindow::-webkit-scrollbar-track { background: transparent; }
    #chatWindow::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .message-bubble { max-width: 80%; padding: 15px 20px; border-radius: 20px; margin-bottom: 20px; animation: slideUp 0.3s ease-out; }
    .ai-bubble { background: #f8fafc; border: 1px solid #e2e8f0; color: #1e293b; align-self: flex-start; }
    .user-bubble { background: var(--primary-color); color: white; align-self: flex-end; }
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
        const loadingId = addMessage('Pensando...', 'ai', true);
        
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
        } catch (error) {
            updateMessage(loadingId, 'Lo siento, ha ocurrido un error al conectar con Gemini.');
        }
    });

    function addMessage(content, type, isLoading = false) {
        const div = document.createElement('div');
        div.className = `message-bubble ${type}-bubble shadow-sm`;
        div.id = `msg-${Date.now()}`;
        div.innerHTML = content;
        messagesContainer.appendChild(div);
        chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
        return div.id;
    }

    function updateMessage(id, content) {
        const msg = document.getElementById(id);
        if(msg) msg.innerHTML = content;
        chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
    }
</script>
@endsection

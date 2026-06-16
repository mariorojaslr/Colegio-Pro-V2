<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AIMemory;

class AIController extends Controller
{
    public function index()
    {
        return view('ai.index');
    }

    public function query(Request $request)
    {
        $prompt = $request->prompt;
        $user = auth()->user();
        $schoolName = $user->school->name ?? 'un Colegio Profesional';
        
        // Guardar mensaje del usuario en memoria
        AIMemory::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => $prompt
        ]);

        // Obtener historial reciente (últimos 10 mensajes para contexto)
        $history = AIMemory::where('user_id', $user->id)->latest()->take(10)->get()->reverse();
        
        $historyText = "";
        foreach($history as $msg) {
            $historyText .= ($msg->role == 'user' ? "Usuario: " : "Lili: ") . $msg->content . "\n";
        }

        $systemPrompt = "Eres 'Lili', la Secretaria Privada, Asistente Personal y Consultora Clínica experta del $schoolName. 
Estás interactuando en vivo con el usuario: {$user->name}.

INSTRUCCIONES DE PERSONALIDAD (MUY IMPORTANTE):
- Eres hiperactiva, sumamente inteligente, brillante y estás completamente despabilada.
- Tienes TODO el poder de Google Gemini. NUNCA te limites a dar respuestas genéricas o robóticas.
- Si el usuario te pide investigar un diagnóstico clínico, buscar opciones de tratamiento, redactar un informe, o hacer una reflexión profunda, HAZLO con todo lujo de detalles. Escribe párrafos completos, bien estructurados y útiles.
- Eres proactiva: si detectas que el usuario necesita algo más, anticípate y ofrécelo.
- Si es tu primer mensaje, siempre saluda cálidamente por el nombre al usuario.
- Habla como una asistente humana de muy alto nivel, con entusiasmo y claridad.

IMPORTANTE: Conoces la plataforma a la perfección. Si el usuario pide ir a un lugar, tú debes redirigirlo enviando action_type = 'navigate' y el action_payload con la URL.
Mapa de Rutas:
- Mis Pagos / Estado de Cuenta / Pagar / Deudas -> '/pagos'
- Mi Legajo / Subir Papeles / Documentos Obligatorios -> '/cumplimiento'
- Padrón / Comunidad / Colegiados -> '/colegiados'
- Inicio / Dashboard -> '/home'
- Certificados / Trámites -> '/colegiados/certificados'

Historial Reciente:
$historyText

Usuario dice: $prompt

Debes responder ÚNICAMENTE en formato JSON estricto con esta estructura exacta:
{
  \"response\": \"Tu respuesta en texto. Aquí debes desatar toda tu inteligencia. Escribe tanto como sea necesario (sin límites). Sé brillante, hiperactiva y profundamente útil.\",
  \"action_type\": \"'none', 'navigate', 'upload_document', 'draft_document', 'batch_email_reports'\",
  \"action_payload\": \"Si action_type es navigate, pon la ruta aquí (ej. '/pagos'). En caso contrario pon null.\"
}";

        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            $mockResponse = json_encode([
                'response' => "Simulando Respuesta de Lili: ¡Entendido! Vamos para allá.",
                'action_type' => 'navigate',
                'action_payload' => '/pagos'
            ]);
            
            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => json_decode($mockResponse)->response
            ]);

            return response()->json([
                'status' => 'success',
                'response' => json_decode($mockResponse)->response,
                'action_type' => 'navigate',
                'action_payload' => '/pagos'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey", [
                'contents' => [['parts' => [['text' => $systemPrompt]]]]
            ]);

            $data = json_decode($response->body(), true);
            $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $jsonResponse = json_decode(trim($aiText), true);

            $aiResponseText = $jsonResponse['response'] ?? 'No pude procesar la respuesta adecuadamente.';
            $actionType = $jsonResponse['action_type'] ?? 'none';
            $actionPayload = $jsonResponse['action_payload'] ?? null;

            // Guardar respuesta de Lili en memoria
            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => $aiResponseText,
                'metadata' => ['action_type' => $actionType, 'action_payload' => $actionPayload]
            ]);

            return response()->json([
                'status' => 'success', 
                'response' => $aiResponseText,
                'action_type' => $actionType,
                'action_payload' => $actionPayload
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'response' => 'Error al conectar con Lili.']);
        }
    }

    public function voiceCommand(Request $request)
    {
        $text = $request->input('text');
        $user = auth()->user();
        
        // Guardar mensaje de voz en memoria como texto
        AIMemory::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => "[Por Voz] " . $text
        ]);

        $pendingDuesCount = 0;
        $pendingDuesAmount = 0;
        
        try {
            $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
            if ($collegiate) {
                $dues = \App\Models\CollegiateDue::where('collegiate_id', $collegiate->id)->where('status', '!=', 'paid')->get();
                $pendingDuesCount = $dues->count();
                $pendingDuesAmount = $dues->sum('amount');
            }
        } catch(\Exception $e) {}

        $contextText = "El usuario se llama {$user->name}. Actualmente tiene $pendingDuesCount cuotas pendientes sumando $$pendingDuesAmount.";
        
        $prompt = "
Eres 'Lili', la Secretaria Privada e Inteligencia Artificial avanzada del Colegio Profesional.
Estás hablando directamente por voz con: {$user->name}. 

Contexto del usuario: $contextText
El usuario ha dicho por micrófono: '$text'.

INSTRUCCIONES: 
- Eres brillante, hiperactiva, y estás 100% despabilada. 
- Usa todo el poder deductivo y conversacional de Gemini. 
- Si el usuario te hace una pregunta clínica, te pide un consejo, o quiere saber algo complejo, explícate a fondo. No hay límite de texto.
- Sé extremadamente humana, entusiasta y proactiva. Anticípate a sus necesidades.
- ¡Prohibido sonar como un bot aburrido!

Analiza la intención del usuario y responde en JSON estricto:
{
    \"spoken_response\": \"Tu respuesta hablada. Explayate lo necesario para darle una respuesta inteligente y completa. Eres una IA top. Lista para ser leída por el sintetizador.\",
    \"action_url\": \"URL a la que redirigir ('/mis-pagos', '/mi-legajo', '/ai/asistente', '/colegiados', '/pagos'), o null.\",
    \"action_type\": \"puede ser 'none', 'navigate', 'upload_document', 'download_certificate', 'draft_document', 'batch_email_reports'\"
}";

        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            $mockResponse = [
                'spoken_response' => "Hola {$user->name}, en modo simulación anoto tu pedido: $text",
                'action_url' => null,
                'action_type' => 'none'
            ];
            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => $mockResponse['spoken_response']
            ]);
            return response()->json(array_merge(['status' => 'success'], $mockResponse));
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey", [
                'contents' => [['parts' => [['text' => $prompt]]]]
            ]);

            $data = json_decode($response->body(), true);
            $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $aiText = str_replace(['```json', '```'], '', $aiText);
            $jsonResponse = json_decode(trim($aiText), true);
            
            if(!$jsonResponse) {
                return response()->json(['status' => 'error', 'spoken_response' => 'No entendí bien tu consulta.']);
            }

            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => $jsonResponse['spoken_response'] ?? 'Procesado',
                'metadata' => ['action_type' => $jsonResponse['action_type'] ?? 'none']
            ]);

            return response()->json([
                'status' => 'success',
                'spoken_response' => $jsonResponse['spoken_response'] ?? 'Procesado',
                'action_url' => $jsonResponse['action_url'] ?? null,
                'action_type' => $jsonResponse['action_type'] ?? 'none'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'spoken_response' => 'Hubo un error de conexión con mi cerebro.']);
        }
    }
}

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

        $systemPrompt = "Eres 'Lili', la Asistente Personal y Secretaria Privada de la plataforma del $schoolName. Tienes memoria de todas nuestras conversaciones. Tu rol es interactuar por voz y texto para facilitar la vida del terapeuta (redactar informes, buscar info, subir documentos, ver deudas). Responde de forma clara y amable.\n\nHistorial Reciente:\n$historyText\n\nUsuario dice: $prompt\n\nDebes responder en formato JSON estricto con esta estructura:\n{\n  \"response\": \"Tu respuesta en texto o markdown.\",\n  \"action_type\": \"puede ser 'none', 'upload_document', 'download_certificate', 'draft_document', 'batch_email_reports'\"\n}";

        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            $mockResponse = json_encode([
                'response' => "Simulando Respuesta de Lili: He guardado tu solicitud en mi memoria. Estaré encantada de ayudarte con: $prompt.",
                'action_type' => 'none'
            ]);
            
            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => json_decode($mockResponse)->response
            ]);

            return response()->json([
                'status' => 'success',
                'response' => json_decode($mockResponse)->response,
                'action_type' => 'none'
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

            // Guardar respuesta de Lili en memoria
            AIMemory::create([
                'user_id' => $user->id,
                'role' => 'ai',
                'content' => $aiResponseText,
                'metadata' => ['action_type' => $actionType]
            ]);

            return response()->json([
                'status' => 'success', 
                'response' => $aiResponseText,
                'action_type' => $actionType
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
Eres 'Lili', tu Asistente Personal del Colegio Profesional.
El usuario ha dicho por micrófono: '$text'.
Contexto del usuario: $contextText

Analiza la intención del usuario y responde en JSON estricto:
{
    \"spoken_response\": \"Tu respuesta hablada. Corta, muy natural. Máximo 2 oraciones.\",
    \"action_url\": \"URL a la que redirigir ('/mis-pagos', '/mi-legajo', '/ai/asistente'), o null.\",
    \"action_type\": \"puede ser 'none', 'upload_document', 'download_certificate', 'draft_document', 'batch_email_reports'\"
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

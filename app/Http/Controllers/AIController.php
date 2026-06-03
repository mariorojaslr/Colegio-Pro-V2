<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function index()
    {
        return view('ai.index');
    }

    public function query(Request $request)
    {
        $prompt = $request->prompt;
        
        // Contexto Institucional
        $user = auth()->user();
        $schoolName = $user->school->name ?? 'un Colegio Profesional';
        
        $systemPrompt = "Actúa como un asistente experto para el $schoolName. El usuario es un colegiado profesional. ";
        $fullPrompt = $systemPrompt . $prompt;

        // Por ahora, simulamos Gemini si no hay API Key
        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'status' => 'mock',
                'response' => "Simulando Respuesta (Configura GEMINI_API_KEY en .env): Con gusto te ayudaré con tu consulta sobre: $prompt. Estaré encantado de redactar ese documento para ti."
            ]);
        }

        try {
            // Llamada real a Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->body(), true);
            $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No pude procesar la respuesta.';

            return response()->json(['status' => 'success', 'response' => $aiResponse]);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'response' => 'Error al conectar con el servidor de IA.']);
        }
    }

    public function voiceCommand(Request $request)
    {
        $text = $request->input('text');
        $user = auth()->user();
        
        $pendingDuesCount = 0;
        $pendingDuesAmount = 0;
        
        try {
            $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
            if ($collegiate) {
                // Asumiendo que pendingDues es una relación o lo calculamos manual
                $dues = \App\Models\CollegiateDue::where('collegiate_id', $collegiate->id)->where('status', '!=', 'paid')->get();
                $pendingDuesCount = $dues->count();
                $pendingDuesAmount = $dues->sum('amount');
            }
        } catch(\Exception $e) {}

        $contextText = "El usuario se llama {$user->name}. ";
        $contextText .= "Actualmente tiene $pendingDuesCount cuotas societarias pendientes de pago, sumando un total de $$pendingDuesAmount. ";
        
        $prompt = "
Eres 'Carina', la asistente inteligente de voz del Colegio Profesional.
El usuario ha dicho lo siguiente por micrófono: '$text'.
Contexto actual del usuario en la base de datos: $contextText

Tu objetivo es analizar la intención del usuario y responder en formato JSON estricto.
Debes devolver un JSON con esta estructura exacta:
{
    \"spoken_response\": \"Tu respuesta amigable, directa y muy natural (como si hablaras) para ser leída por un motor de voz. No uses asteriscos ni markdown. Máximo 2 oraciones cortas.\",
    \"action_url\": \"La URL a la que debemos redirigir al usuario, si aplica (ej. '/mis-pagos', '/mi-legajo', '/mis-clases'). Si no hay acción de navegación clara, devuelve null.\"
}

Responde ÚNICAMENTE con el objeto JSON válido. Nada más.
";

        $apiKey = env('GEMINI_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'status' => 'success',
                'spoken_response' => "Hola {$user->name}, en modo simulación veo que tienes {$pendingDuesCount} deudas. Te llevo a tus pagos.",
                'action_url' => "/mis-pagos"
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->body(), true);
            $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            // Limpiamos posibles backticks de markdown que envíe el LLM
            $aiText = str_replace(['```json', '```'], '', $aiText);
            
            $jsonResponse = json_decode(trim($aiText), true);
            
            if(!$jsonResponse) {
                return response()->json([
                    'status' => 'success',
                    'spoken_response' => 'No entendí bien tu consulta, ¿podrías repetirla?',
                    'action_url' => null
                ]);
            }

            return response()->json([
                'status' => 'success',
                'spoken_response' => $jsonResponse['spoken_response'] ?? 'Procesado',
                'action_url' => $jsonResponse['action_url'] ?? null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'spoken_response' => 'Hubo un error de conexión con mi servidor cerebral.',
                'action_url' => null
            ]);
        }
    }
}

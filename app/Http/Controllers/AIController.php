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
}

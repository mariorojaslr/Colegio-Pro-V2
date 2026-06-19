<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotKnowledge;
use App\Models\School;

class ChatbotController extends Controller
{
    /**
     * Responde a una consulta del usuario
     */
    public function ask(Request $request)
    {
        $question = strtolower($request->input('question'));
        $schoolId = $request->input('school_id');

        if (!$question || !$schoolId) {
            return response()->json(['answer' => 'Por favor, hazme una pregunta válida.']);
        }

        // 1. Buscar coincidencias en palabras clave (búsqueda simple)
        // Obtenemos los conocimientos aprendidos para esa escuela
        $knowledges = ChatbotKnowledge::where('school_id', $schoolId)
                        ->where('status', 'learned')
                        ->get();

        $bestMatch = null;
        $highestScore = 0;

        foreach ($knowledges as $knowledge) {
            $score = 0;
            $keywords = explode(',', strtolower($knowledge->keywords));
            
            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (!empty($keyword) && str_contains($question, $keyword)) {
                    $score++;
                }
            }

            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMatch = $knowledge;
            }
        }

        if ($bestMatch && $highestScore > 0) {
            return response()->json(['answer' => $bestMatch->answer]);
        }

        // 2. Si no hay coincidencia, guardamos la pregunta como pendiente
        ChatbotKnowledge::create([
            'school_id' => $schoolId,
            'question'  => $request->input('question'),
            'status'    => 'pending'
        ]);

        return response()->json([
            'answer' => 'Disculpa, aún no tengo la respuesta a esa pregunta. ¡Pero la he anotado! Nuestro equipo la responderá pronto para que la aprenda.'
        ]);
    }
}

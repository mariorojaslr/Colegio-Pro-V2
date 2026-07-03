<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotKnowledge;

class ChatbotKnowledgeController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $knowledges = ChatbotKnowledge::where('school_id', $schoolId)->orderBy('created_at', 'desc')->get();
        return view('admin.chatbot.index', compact('knowledges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'keywords' => 'required|string',
            'answer'   => 'required|string',
        ]);

        ChatbotKnowledge::create([
            'school_id' => auth()->user()->school_id,
            'question'  => $request->question,
            'keywords'  => strtolower($request->keywords),
            'answer'    => $request->answer,
            'status'    => 'learned'
        ]);

        return redirect()->back()->with('success', 'Conocimiento agregado exitosamente al Chatbot.');
    }

    public function update(Request $request, ChatbotKnowledge $knowledge)
    {
        $request->validate([
            'keywords' => 'required|string',
            'answer'   => 'required|string',
        ]);

        $knowledge->update([
            'keywords' => strtolower($request->keywords),
            'answer'   => $request->answer,
            'status'   => 'learned'
        ]);

        return redirect()->back()->with('success', 'Conocimiento actualizado exitosamente.');
    }

    public function destroy(ChatbotKnowledge $knowledge)
    {
        $knowledge->delete();
        return redirect()->back()->with('success', 'Conocimiento eliminado.');
    }

    public function banIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|string',
            'knowledge_id' => 'nullable|integer',
        ]);

        $ip = $request->ip_address;

        // No banee localhost
        if ($ip !== '127.0.0.1' && $ip !== '::1' && !empty($ip)) {
            \App\Models\BannedIp::firstOrCreate([
                'ip_address' => $ip
            ], [
                'reason' => 'Baneado desde el panel de conocimiento del chatbot por comportamiento/actitudes inapropiadas.',
                'school_id' => auth()->user()->school_id
            ]);
        }

        // Eliminar la consulta del chatbot
        if ($request->knowledge_id) {
            ChatbotKnowledge::where('id', $request->knowledge_id)->delete();
        }

        return redirect()->back()->with('success', "IP {$ip} bloqueada de forma permanente y consulta eliminada.");
    }
}

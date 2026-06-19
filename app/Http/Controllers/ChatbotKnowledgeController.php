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
}

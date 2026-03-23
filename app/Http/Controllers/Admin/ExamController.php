<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Option;

class ExamController extends Controller
{
    /**
     * Listado global de exámenes académicos.
     */
    public function index()
    {
        $exams = Exam::with('lesson')->latest()->get();
        return view('admin.exams.index', compact('exams'));
    }

    /**
     * Formulario para vincular un examen a una clase.
     */
    public function create()
    {
        // Solo clases que no tengan examen asignado todavía
        $lessons = Lesson::whereDoesntHave('exam')->get();
        return view('admin.exams.create', compact('lessons'));
    }

    /**
     * Crear la cabecera del examen.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $exam = Exam::create($validated);

        return redirect()->route('admin.exams.edit', $exam->id)->with('success', 'Cabecera de examen creada. Ahora añada las preguntas.');
    }

    /**
     * Panel de edición dinámico para preguntas y opciones.
     */
    public function edit(Exam $exam)
    {
        $exam->load('questions.options');
        return view('admin.exams.edit', compact('exam'));
    }

    /**
     * Actualiza la configuración base del examen.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $exam->update($validated);

        return redirect()->back()->with('success', 'Configuración de examen actualizada.');
    }

    /**
     * Elimina el examen completo.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Examen eliminado.');
    }

    /**
     * Almacena una pregunta con sus opciones (llamada dinámica o form).
     */
    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer', // Index del array
        ]);

        $question = $exam->questions()->create([
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        foreach ($request->options as $index => $text) {
            $question->options()->create([
                'option_text' => $text,
                'is_correct' => ($index == $request->correct_option),
            ]);
        }

        return redirect()->back()->with('success', 'Pregunta añadida correctamente.');
    }

    /**
     * Elimina una pregunta específica.
     */
    public function destroyQuestion(Question $question)
    {
        $question->delete();
        return redirect()->back()->with('success', 'Pregunta eliminada.');
    }
}

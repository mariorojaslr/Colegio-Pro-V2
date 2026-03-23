<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Certificate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Muestra la interfaz para realizar el examen.
     */
    public function take(Exam $exam)
    {
        $exam->load('questions.options');
        
        // Verificar si el usuario ya aprobó este examen
        $previousResult = ExamResult::where('user_id', Auth::id())
            ->where('exam_id', $exam->id)
            ->where('status', 'passed')
            ->first();

        if ($previousResult) {
            return redirect()->route('student.lessons.show', $exam->lesson_id)
                ->with('status', 'Ya has aprobado este examen anteriormente.');
        }

        return view('student.exams.take', compact('exam'));
    }

    /**
     * Procesa las respuestas del examen y calcula el puntaje.
     */
    public function submit(Request $request, Exam $exam)
    {
        $exam->load('questions.options');
        $totalPoints = $exam->questions->sum('points');
        $earnedPoints = 0;

        if ($totalPoints === 0) {
            return redirect()->route('student.lessons.index')->with('error', 'El examen no tiene preguntas configuradas.');
        }

        foreach ($exam->questions as $question) {
            $submittedOptionId = $request->input("q-{$question->id}");
            $correctOption = $question->options->where('is_correct', true)->first();

            if ($submittedOptionId && $correctOption && $submittedOptionId == $correctOption->id) {
                $earnedPoints += $question->points;
            }
        }

        $percentage = round(($earnedPoints / $totalPoints) * 100);
        $status = ($percentage >= $exam->passing_score) ? 'passed' : 'failed';

        // Actualizar o crear resultado.
        $existing = ExamResult::where('user_id', Auth::id())->where('exam_id', $exam->id)->first();
        
        if ($existing) {
            $existing->update([
                'score' => $percentage,
                'status' => $status,
                'attempts' => $existing->attempts + 1,
            ]);
        } else {
            ExamResult::create([
                'user_id' => Auth::id(),
                'exam_id' => $exam->id,
                'score' => $percentage,
                'status' => $status,
                'attempts' => 1,
            ]);
        }

        // Generar certificado automático si aprobó y no existe ya uno
        if ($status == 'passed') {
            Certificate::firstOrCreate(
                ['user_id' => Auth::id(), 'lesson_id' => $exam->lesson_id],
                [
                    'exam_id' => $exam->id,
                    'code' => 'PRO-ACAD-' . strtoupper(Str::random(10)),
                    'issued_at' => now(),
                ]
            );
        }

        return view('student.exams.result', compact('exam', 'percentage', 'status'));
    }
}

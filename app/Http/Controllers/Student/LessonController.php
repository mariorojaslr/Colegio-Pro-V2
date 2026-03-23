<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Listado de clases disponibles para el alumno.
     */
    public function index()
    {
        $user = Auth::user();
        
        // El alumno solo ve las clases de SU institución que estén publicadas
        $lessons = Lesson::where('school_id', $user->school_id)
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('student.lessons.index', compact('lessons'));
    }

    /**
     * Ver una clase específica y su reproductor de video.
     */
    public function show(Lesson $lesson)
    {
        $user = Auth::user();

        // Seguridad: El alumno debe pertenecer a la misma escuela que la clase
        if ($lesson->school_id !== $user->school_id) {
            abort(403, 'No tienes permiso para ver este contenido.');
        }

        if (!$lesson->is_published) {
            abort(404, 'La clase no se encuentra disponible.');
        }

        return view('student.lessons.show', compact('lesson'));
    }
}

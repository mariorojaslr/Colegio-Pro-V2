<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Listado de clases disponibles (Público y Privado).
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si no hay usuario (acceso público), mostramos la escuela de demo (id:1)
        $schoolId = $user ? $user->school_id : 1;

        $lessons = Lesson::where('school_id', $schoolId)
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('student.academy.index', compact('lessons'));
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

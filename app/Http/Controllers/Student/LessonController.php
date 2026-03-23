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
        
        // Si es OWNER o no tiene colegio (o es invitado), buscamos el primero para la vitrina pública
        $schoolId = ($user && $user->school_id) ? $user->school_id : (\App\Models\School::first()?->id ?? 1);

        $lessons = Lesson::where('school_id', $schoolId)
            ->where('is_published', true)
            ->latest()
            ->get();

        $enrolledLessons = $user ? $user->enrolledLessons->pluck('id')->toArray() : [];
        $certificates = $user ? $user->certificates->keyBy('lesson_id') : collect();
        
        return view('student.academy.index', compact('lessons', 'enrolledLessons', 'certificates'));
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

        // Si no está inscrito, lo redirigimos a la academia
        if (!$user->enrolledLessons->contains($lesson->id)) {
            return redirect()->route('student.lessons.index')->with('error', 'Debes inscribirte para ver este contenido.');
        }

        return view('student.lessons.show', compact('lesson'));
    }

    /**
     * Inscripción a una clase específica.
     */
    public function enroll(Lesson $lesson)
    {
        $user = Auth::user();

        if ($lesson->school_id !== $user->school_id) {
            abort(403);
        }

        // Simulación: Inscribir directamente (Checkout Placeholder)
        $user->enrolledLessons()->syncWithoutDetaching([$lesson->id => [
            'status' => 'enrolled',
            'paid_amount' => $lesson->price
        ]]);

        return redirect()->route('student.lessons.index')->with('success', "Te has inscrito correctamente en: {$lesson->title}. ¡Bienvenido!");
    }
}

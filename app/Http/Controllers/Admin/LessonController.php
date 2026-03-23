<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\School;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::with('school')->latest()->get();
        return view('admin.academy.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        return view('admin.academy.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'lecturer' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:255',
            'benefit' => 'nullable|string',
            'bunny_video_id' => 'nullable|string',
            'is_published' => 'nullable',
            'is_live' => 'nullable',
            'live_url' => 'nullable|url',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['is_live'] = $request->has('is_live');

        Lesson::create($validated);

        return redirect()->route('admin.academy.index')->with('success', 'Curso creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $academy)
    {
        $schools = School::all();
        $lesson = $academy;
        return view('admin.academy.edit', compact('lesson', 'schools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $academy)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'lecturer' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'start_date' => 'nullable|string|max:255',
            'benefit' => 'nullable|string',
            'bunny_video_id' => 'nullable|string',
            'is_published' => 'nullable',
            'is_live' => 'nullable',
            'live_url' => 'nullable|url',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['is_live'] = $request->has('is_live');

        $academy->update($validated);

        return redirect()->route('admin.academy.index')->with('success', 'Curso actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $academy)
    {
        $academy->delete();
        return redirect()->route('admin.academy.index')->with('success', 'Curso eliminado correctamente.');
    }
}

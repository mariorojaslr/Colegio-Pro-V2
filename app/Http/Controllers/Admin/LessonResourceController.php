<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\LessonResource;
use App\Services\BunnyService;
use Illuminate\Support\Str;

class LessonResourceController extends Controller
{
    protected $bunny;

    public function __construct(BunnyService $bunny)
    {
        $this->bunny = $bunny;
    }

    /**
     * Sube un recurso (PDF, PPT, etc) a Bunny.net y lo vincula a la clase.
     */
    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:pdf,slides,word,excel,link',
            'file' => 'required_if:type,pdf,slides,word,excel|file|max:20480', // 20MB
            'external_url' => 'required_if:type,link|nullable|url',
        ]);

        if ($request->type == 'link') {
            $url = $request->external_url;
        } else {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            // Ruta: academy/resources/{lesson_id}/{slug}_{time}.ext
            $remoteName = "academy/resources/{$lesson->id}/" . Str::slug($request->title) . "_" . time() . ".{$ext}";
            
            $result = $this->bunny->uploadFile($file->getPathname(), $remoteName);
            
            if (!$result['success']) {
                return back()->with('error', 'Error al subir el archivo a la nube. Detalle: ' . $result['error']);
            }
            $url = $result['url'];
        }

        $lesson->resources()->create([
            'title' => $request->title,
            'type' => $request->type,
            'file_url' => $url,
        ]);

        return back()->with('success', 'Recurso añadido correctamente.');
    }

    /**
     * Elimina un recurso.
     */
    public function destroy(LessonResource $resource)
    {
        // Podríamos eliminar de Bunny si quisiéramos, pero por ahora solo el registro
        $resource->delete();
        return back()->with('success', 'Recurso eliminado.');
    }
}

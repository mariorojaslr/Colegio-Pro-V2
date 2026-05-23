<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\NewsArticle;
use Illuminate\Support\Str;

class NewsArticleController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $articles = NewsArticle::where('school_id', $schoolId)->latest()->paginate(10);
        return view('admin.news.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.news.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        $article = new NewsArticle($request->all());
        $article->school_id = auth()->user()->school_id;
        $article->author_id = auth()->id();
        $article->slug = Str::slug($request->title) . '-' . time();
        
        if ($request->status == 'published') {
            $article->published_at = now();
        }

        // Simulación de carga de imagen para la demo, pero listo para BunnyCDN o Storage
        if ($request->has('featured_image_url')) {
            $article->featured_image_url = $request->featured_image_url;
        }

        $article->save();

        return redirect()->route('admin.news.index')->with('success', 'Noticia publicada exitosamente.');
    }

    public function edit(NewsArticle $newsArticle)
    {
        // En un sistema real verificaríamos $newsArticle->school_id == auth()->user()->school_id
        return view('admin.news.form', compact('newsArticle'));
    }

    public function update(Request $request, NewsArticle $newsArticle)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        $newsArticle->fill($request->all());
        $newsArticle->slug = Str::slug($request->title) . '-' . time();

        if ($request->status == 'published' && !$newsArticle->published_at) {
            $newsArticle->published_at = now();
        }

        $newsArticle->save();

        return redirect()->route('admin.news.index')->with('success', 'Noticia actualizada.');
    }

    public function destroy(NewsArticle $newsArticle)
    {
        $newsArticle->delete();
        return redirect()->route('admin.news.index')->with('success', 'Noticia eliminada.');
    }
}

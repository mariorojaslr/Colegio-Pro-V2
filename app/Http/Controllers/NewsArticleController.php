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

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news', 'public');
            $article->featured_image_url = 'storage/' . $path;
        }

        $article->save();

        return redirect()->route('admin.news.index')->with('success', 'Noticia publicada exitosamente.');
    }

    public function edit(NewsArticle $news)
    {
        // En un sistema real verificaríamos $news->school_id == auth()->user()->school_id
        return view('admin.news.form', ['newsArticle' => $news]);
    }

    public function update(Request $request, NewsArticle $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,archived',
        ]);

        $news->fill($request->all());
        $news->slug = Str::slug($request->title) . '-' . time();

        if ($request->status == 'published' && !$news->published_at) {
            $news->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news', 'public');
            $news->featured_image_url = 'storage/' . $path;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'Noticia actualizada.');
    }

    public function destroy(NewsArticle $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Noticia eliminada.');
    }
}

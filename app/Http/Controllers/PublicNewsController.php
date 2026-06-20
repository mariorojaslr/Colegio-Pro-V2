<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\NewsArticle;
use App\Models\School;

class PublicNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsArticle::with('author')->where('status', 'published');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(9);

        return view('news.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = NewsArticle::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('news.show', compact('article'));
    }
}

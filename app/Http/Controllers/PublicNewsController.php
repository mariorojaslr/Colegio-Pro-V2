<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\NewsArticle;
use App\Models\School;

class PublicNewsController extends Controller
{
    public function index()
    {
        // En una app multitenant real pública, quizás buscaríamos por dominio.
        // Aquí mostraremos las noticias publicadas.
        $articles = NewsArticle::with('author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

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

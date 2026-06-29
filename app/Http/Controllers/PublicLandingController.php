<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Slider;
use App\Models\Page;

class PublicLandingController extends Controller
{
    public function index()
    {
        // Obtener el colegio inyectado por el Middleware (Subdominio) o fallback a Cotolar/Primero
        $school = app('tenant') ?? \App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first(); 
        
        $mainMenu = Menu::where('school_id', $school->id ?? 1)
                        ->where('location', 'header')
                        ->where('is_active', true)
                        ->with(['items' => function($q) {
                            $q->where('is_active', true)->with('children', 'page');
                        }])
                        ->first();

        $slider = \App\Models\Slider::where('school_id', $school->id ?? 1)
                        ->where('is_active', true)
                        ->with(['items' => function($q) {
                            $q->active()->orderBy('order', 'asc');
                        }])
                        ->first();

        $plans = \App\Models\SubscriptionPlan::all();

        $boardMembers = \App\Models\BoardMember::with('collegiate')
                                               ->where('school_id', $school->id ?? 1)
                                               ->orderBy('order')
                                               ->get()
                                               ->groupBy('department');

        $schoolId = $school->id ?? 1;

        // Auto-fix for remote database
        $existingNews = \App\Models\NewsArticle::where('school_id', $schoolId)->get();
        $photos = [
            'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ];
        
        $i = 0;
        foreach($existingNews as $n) {
            if (empty($n->featured_image_url) || strpos($n->featured_image_url, 'http') === false) {
                $n->featured_image_url = $photos[$i % count($photos)];
                $n->save();
            }
            $i++;
        }

        while (\App\Models\NewsArticle::where('school_id', $schoolId)->count() < 3) {
            \App\Models\NewsArticle::create([
                'school_id' => $schoolId,
                'author_id' => \App\Models\User::first()->id ?? 1,
                'title' => 'Noticia Institucional ' . uniqid(),
                'slug' => 'noticia-' . uniqid(),
                'excerpt' => 'Acompañamos el crecimiento profesional de nuestros matriculados con nuevas herramientas.',
                'content' => '<p>Seguimos trabajando para mejorar la experiencia de todos.</p>',
                'status' => 'published',
                'published_at' => now(),
                'featured_image_url' => $photos[rand(0, 2)]
            ]);
        }

        $latestNews = \App\Models\NewsArticle::where('school_id', $schoolId)
                                             ->where('status', 'published')
                                             ->latest('published_at')
                                             ->take(3)
                                             ->get();

        $agreements = \App\Models\Agreement::where('school_id', $schoolId)->where('is_active', true)->get();
        $collegiates = \App\Models\Collegiate::where('school_id', $schoolId)->get();

        $tenantSlug = $school->slug ?? 'default';
        if (view()->exists("tenants.{$tenantSlug}.welcome")) {
            return view("tenants.{$tenantSlug}.welcome", compact('school', 'mainMenu', 'slider', 'plans', 'boardMembers', 'latestNews', 'agreements', 'collegiates'));
        }

        return view('welcome', compact('school', 'mainMenu', 'slider', 'plans', 'boardMembers', 'latestNews', 'agreements', 'collegiates'));
    }

    public function showPage($slug)
    {
        $school = app('tenant') ?? \App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first();
        
        $page = Page::where('school_id', $school->id ?? 1)
                    ->where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();

        $mainMenu = Menu::where('school_id', $school->id ?? 1)
                        ->where('location', 'header')
                        ->where('is_active', true)
                        ->with(['items' => function($q) {
                            $q->where('is_active', true)->with('children', 'page');
                        }])
                        ->first();

        return view('landing.dynamic_page', compact('page', 'mainMenu'));
    }
    
    public function validateMatricula(Request $request)
    {
        $school = app('tenant') ?? \App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first();
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json(['success' => false, 'message' => 'Por favor, ingrese un DNI, matrícula o nombre para buscar.']);
        }

        $collegiate = \App\Models\Collegiate::where('school_id', $school->id ?? 1)
            ->where(function($q) use ($query) {
                $q->where('dni', $query)
                  ->orWhere('registration_number', $query)
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                  ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$query}%"]);
            })
            ->first();

        if (!$collegiate) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ningún profesional matriculado con esos datos en nuestra jurisdicción.'
            ]);
        }

        $isActive = in_array(strtolower($collegiate->status), ['active', 'activo']);

        return response()->json([
            'success' => true,
            'collegiate' => [
                'name' => mb_strtoupper($collegiate->first_name . ' ' . $collegiate->last_name, 'UTF-8'),
                'document' => $collegiate->dni,
                'registration' => $collegiate->registration_number,
                'status' => $isActive ? 'HABILITADO' : 'NO HABILITADO',
                'is_active' => $isActive
            ]
        ]);
        }
}

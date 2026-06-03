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
        // Obtener el menú principal (suponiendo que hay un colegio por defecto o se usa el primero para demo)
        $school = \App\Models\School::first(); 
        
        $mainMenu = Menu::where('school_id', $school->id ?? 1)
                        ->where('location', 'header')
                        ->where('is_active', true)
                        ->with(['items' => function($q) {
                            $q->where('is_active', true)->with('children', 'page');
                        }])
                        ->first();

        $slider = Slider::where('school_id', $school->id ?? 1)
                        ->where('is_active', true)
                        ->with('items')
                        ->first();

        $plans = \App\Models\SubscriptionPlan::all();

        return view('welcome', compact('mainMenu', 'slider', 'plans'));
    }

    public function showPage($slug)
    {
        $school = \App\Models\School::first(); 
        
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
}

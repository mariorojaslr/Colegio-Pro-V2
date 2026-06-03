<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Slider;
use App\Models\SliderItem;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    // === PAGES ===
    public function pagesIndex()
    {
        $pages = Page::where('school_id', auth()->user()->school_id)->get();
        return view('admin.cms.pages.index', compact('pages'));
    }

    public function pagesCreate()
    {
        return view('admin.cms.pages.create');
    }

    public function pagesStore(Request $request)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $slug = Str::slug($request->title);
        
        Page::create([
            'school_id' => auth()->user()->school_id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->input('content', ''),
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página creada correctamente.');
    }

    public function pagesEdit(Page $page)
    {
        return view('admin.cms.pages.edit', compact('page'));
    }

    public function pagesUpdate(Request $request, Page $page)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $slug = Str::slug($request->title);

        $page->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->input('content', ''),
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('admin.cms.pages.index')->with('success', 'Página actualizada correctamente.');
    }

    // === MENUS ===
    public function menusIndex()
    {
        $menus = Menu::where('school_id', auth()->user()->school_id)->with('items')->get();
        $pages = Page::where('school_id', auth()->user()->school_id)->get();
        return view('admin.cms.menus.index', compact('menus', 'pages'));
    }

    public function menusStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'nullable|string'
        ]);

        Menu::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'location' => $request->location,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Menú creado.');
    }

    public function menuItemsStore(Request $request, Menu $menu)
    {
        $request->validate(['title' => 'required|string']);
        
        MenuItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $request->parent_id,
            'title' => $request->title,
            'url' => $request->url,
            'page_id' => $request->page_id,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Ítem añadido al menú.');
    }

    // === SLIDERS ===
    public function slidersIndex()
    {
        $sliders = Slider::where('school_id', auth()->user()->school_id)->with('items')->get();
        return view('admin.cms.sliders.index', compact('sliders'));
    }

    public function slidersStore(Request $request)
    {
        Slider::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'is_active' => true
        ]);
        return redirect()->back()->with('success', 'Carrusel creado.');
    }

    public function sliderItemsStore(Request $request, Slider $slider)
    {
        // Integration with Bunny.net would go here for file upload. For now we save the URL.
        SliderItem::create([
            'slider_id' => $slider->id,
            'image_url' => $request->image_url ?? 'https://via.placeholder.com/1200x400',
            'title' => $request->title,
            'description' => $request->description,
            'order' => $request->order ?? 0
        ]);
        return redirect()->back()->with('success', 'Imagen añadida.');
    }
}

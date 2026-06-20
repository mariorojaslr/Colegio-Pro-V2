<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Slider;
use App\Models\SliderItem;
use App\Models\BoardMember;
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

    public function sliderItemsStore(Request $request, Slider $slider, \App\Services\BunnyService $bunny)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
        ]);

        $file = $request->file('image');
        $schoolId = auth()->user()->school_id;
        $remoteName = "tenant_{$schoolId}/sliders/" . time() . '_' . $file->getClientOriginalName();
        
        $upload = $bunny->uploadFile($file->getPathname(), $remoteName);
        $imageUrl = $upload['success'] ? $upload['url'] : $file->store('sliders', 'public');

        SliderItem::create([
            'slider_id' => $slider->id,
            'image_url' => $imageUrl,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'order' => $request->order ?? 0,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at
        ]);
        return redirect()->back()->with('success', 'Imagen añadida.');
    }

    public function sliderItemsDestroy(SliderItem $sliderItem)
    {
        // Verificar que el slider pertenece a la escuela actual
        if ($sliderItem->slider->school_id == auth()->user()->school_id) {
            $sliderItem->delete();
        }
        return redirect()->back()->with('success', 'Imagen eliminada del carrusel.');
    }

    // === BOARD MEMBERS (Autoridades) ===
    public function boardMembersIndex()
    {
        $schoolId = auth()->user()->school_id;
        $members = BoardMember::with('collegiate')->where('school_id', $schoolId)->orderBy('order')->get();
        $collegiates = \App\Models\Collegiate::where('school_id', $schoolId)->orderBy('last_name')->orderBy('first_name')->get();
        
        $departments = [
            'Comisión Directiva' => ['Presidente', 'Vicepresidente', 'Secretaria', 'Tesorera', '1er Vocal', '2do Vocal', 'Suplente'],
            'Tribunal de Ética' => ['Presidente', 'Vocal', 'Suplente'],
            'Revisores de Cuentas' => ['Titular', 'Suplente']
        ];

        return view('admin.cms.board_members.index', compact('members', 'collegiates', 'departments'));
    }

    public function boardMembersStore(Request $request)
    {
        $request->validate([
            'collegiate_id' => 'required|exists:collegiates,id',
            'department' => 'required|string',
            'role' => 'required|string',
        ]);

        $collegiate = \App\Models\Collegiate::where('id', $request->collegiate_id)->where('school_id', auth()->user()->school_id)->firstOrFail();

        BoardMember::create([
            'school_id' => auth()->user()->school_id,
            'collegiate_id' => $collegiate->id,
            'role' => $request->role,
            'department' => $request->department,
            'is_substitute' => $request->has('is_substitute'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->back()->with('success', 'Autoridad agregada correctamente.');
    }

    public function boardMembersDestroy(BoardMember $boardMember)
    {
        if ($boardMember->school_id == auth()->user()->school_id) {
            $boardMember->delete();
        }
        return redirect()->back()->with('success', 'Autoridad eliminada.');
    }

    public function boardMembersUpdate(Request $request, BoardMember $boardMember)
    {
        if ($boardMember->school_id != auth()->user()->school_id) {
            abort(403);
        }

        $request->validate([
            'collegiate_id' => 'required|exists:collegiates,id',
            'department' => 'required|string',
            'role' => 'required|string',
        ]);

        $collegiate = \App\Models\Collegiate::where('id', $request->collegiate_id)->where('school_id', auth()->user()->school_id)->firstOrFail();

        $boardMember->update([
            'collegiate_id' => $collegiate->id,
            'role' => $request->role,
            'department' => $request->department,
            'is_substitute' => $request->has('is_substitute'),
            'order' => $request->order ?? 0
        ]);

        return redirect()->back()->with('success', 'Autoridad actualizada correctamente.');
    }
}

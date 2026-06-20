<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventBannerController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $banners = EventBanner::where('school_id', $schoolId)->orderBy('created_at', 'desc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request, \App\Services\BunnyService $bunny)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
        ]);

        $file = $request->file('image');
        $schoolId = auth()->user()->school_id;
        $remoteName = "tenant_{$schoolId}/banners/" . time() . '_' . $file->getClientOriginalName();
        
        $upload = $bunny->uploadFile($file->getPathname(), $remoteName);
        $imagePath = $upload['success'] ? $upload['url'] : $file->store('banners', 'public');

        EventBanner::create([
            'school_id' => $schoolId,
            'title' => $request->title,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner creado exitosamente.');
    }

    public function edit(EventBanner $banner)
    {
        if ($banner->school_id !== auth()->user()->school_id) abort(403);
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, EventBanner $banner, \App\Services\BunnyService $bunny)
    {
        if ($banner->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Podríamos borrar de Bunny aquí usando $bunny->deleteFile() si tuviéramos el path relativo
            $file = $request->file('image');
            $remoteName = "tenant_{$banner->school_id}/banners/" . time() . '_' . $file->getClientOriginalName();
            $upload = $bunny->uploadFile($file->getPathname(), $remoteName);
            $data['image_path'] = $upload['success'] ? $upload['url'] : $file->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner actualizado exitosamente.');
    }

    public function destroy(EventBanner $banner)
    {
        if ($banner->school_id !== auth()->user()->school_id) abort(403);
        if ($banner->image_path) Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner eliminado exitosamente.');
    }

    public function toggle(EventBanner $banner)
    {
        if ($banner->school_id !== auth()->user()->school_id) abort(403);
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Estado del banner actualizado.');
    }
}

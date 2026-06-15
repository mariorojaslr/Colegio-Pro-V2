<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BunnyService;

class SchoolSettingsController extends Controller
{
    public function edit()
    {
        $school = Auth::user()->school;
        return view('admin.school_settings.edit', compact('school'));
    }

    public function update(Request $request, BunnyService $bunny)
    {
        $school = Auth::user()->school;

        $request->validate([
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'plus_code' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'map_embed_code' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $remoteName = "logos/{$school->slug}/logo_" . time() . ".{$extension}";
            
            $url = $bunny->uploadFile($file->getPathname(), $remoteName);
            if ($url) {
                $data['logo'] = $url;
            }
        }

        $school->update($data);

        return back()->with('success', 'Configuración de la institución actualizada exitosamente.');
    }
}

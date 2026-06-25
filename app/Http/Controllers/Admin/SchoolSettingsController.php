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
            'billing_day' => 'nullable|integer|min:1|max:28',
            'auto_billing_enabled' => 'boolean',
            'mp_access_token' => 'nullable|string',
            'mp_public_key' => 'nullable|string',
            'mp_sandbox_mode' => 'boolean',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'tertiary_color' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['logo']);
        $data['auto_billing_enabled'] = $request->has('auto_billing_enabled');
        $data['mp_sandbox_mode'] = $request->has('mp_sandbox_mode');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $remoteName = "logos/{$school->slug}/logo_" . time() . ".{$extension}";
            
            $result = $bunny->uploadFile($file->getPathname(), $remoteName);

            if ($result['success']) {
                $data['logo'] = $result['url'];
            }
        }

        $school->update($data);

        return back()->with('success', 'Configuración de la institución actualizada exitosamente.');
    }
}

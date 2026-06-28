<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function index()
    {
        $schoolId = app('tenant') ? app('tenant')->id : 1;
        $agreements = Agreement::where('school_id', $schoolId)->get();
        return view('admin.agreements.index', compact('agreements'));
    }

    public function create()
    {
        return view('admin.agreements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|string|max:255'
        ]);

        $schoolId = app('tenant') ? app('tenant')->id : 1;
        
        $data = $request->except('logo');
        $data['school_id'] = $schoolId;
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('agreements', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        Agreement::create($data);

        return redirect()->route('admin.agreements.index')->with('success', 'Convenio creado exitosamente.');
    }

    public function edit(Agreement $agreement)
    {
        return view('admin.agreements.edit', compact('agreement'));
    }

    public function update(Request $request, Agreement $agreement)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|string|max:255'
        ]);

        $data = $request->except('logo');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($agreement->logo_url) {
                $oldPath = str_replace('/storage/', '', $agreement->logo_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('logo')->store('agreements', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        $agreement->update($data);

        return redirect()->route('admin.agreements.index')->with('success', 'Convenio actualizado exitosamente.');
    }

    public function destroy(Agreement $agreement)
    {
        if ($agreement->logo_url) {
            $oldPath = str_replace('/storage/', '', $agreement->logo_url);
            Storage::disk('public')->delete($oldPath);
        }
        $agreement->delete();

        return redirect()->route('admin.agreements.index')->with('success', 'Convenio eliminado exitosamente.');
    }
}

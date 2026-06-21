<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Collegiate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        
        // Obtenemos a los usuarios que son ADMIN_COLEGIO o que tienen algún permiso
        $admins = User::where('school_id', $schoolId)
                    ->where(function($q) {
                        $q->where('role', 'ADMIN_COLEGIO')
                          ->orWhereNotNull('permissions');
                    })
                    ->with('collegiate')
                    ->get();
                    
        $collegiates = Collegiate::where('school_id', $schoolId)->orderBy('last_name')->get();

        return view('admin.permissions.index', compact('admins', 'collegiates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'role_type' => 'required|in:admin_general,custom',
            'permissions' => 'array|nullable'
        ]);

        $userIdInput = $request->user_id;
        $user = null;

        if (str_starts_with($userIdInput, 'col_')) {
            $colId = str_replace('col_', '', $userIdInput);
            $collegiate = Collegiate::findOrFail($colId);
            
            // Generate fallback email if null
            $fallbackEmail = $collegiate->email ?: 'col_' . $colId . '@' . ($collegiate->school->slug ?? 'sistema') . '.com';

            // Create user for collegiate silently so we can assign permissions
            $user = User::firstOrCreate(
                ['email' => $fallbackEmail],
                [
                    'name' => $collegiate->first_name . ' ' . $collegiate->last_name,
                    'password' => bcrypt(Str::random(12)),
                    'school_id' => $collegiate->school_id,
                    'role' => 'COLLEGIATE'
                ]
            );
            $collegiate->user_id = $user->id;
            $collegiate->email = $fallbackEmail; // Ensure the collegiate gets the email too
            $collegiate->save();
        } else {
            $uId = str_replace('usr_', '', $userIdInput);
            $user = User::where('id', $uId)->where('school_id', auth()->user()->school_id)->firstOrFail();
        }

        if ($request->role_type === 'admin_general') {
            $user->role = 'ADMIN_COLEGIO';
            $user->permissions = null; // Como es admin general, no necesita array específico
        } else {
            // Es un sub-admin
            if ($user->role === 'ADMIN_COLEGIO' && auth()->id() !== $user->id) {
                $user->role = 'COLLEGIATE'; // Lo bajamos de rango
            }
            $user->permissions = $request->permissions ?? [];
            
            // Si le quitaron todos los permisos y no es admin, limpiar
            if (empty($user->permissions) && $user->role !== 'ADMIN_COLEGIO') {
                $user->permissions = null;
            }
        }

        $user->save();

        return redirect()->back()->with('success', 'Permisos actualizados correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->school_id !== auth()->user()->school_id) abort(403);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'No puedes revocar tus propios permisos principales.');
        }

        $user->role = 'COLLEGIATE';
        $user->permissions = null;
        $user->save();

        return redirect()->back()->with('success', 'Permisos revocados. El usuario vuelve a ser un colegiado regular.');
    }
}

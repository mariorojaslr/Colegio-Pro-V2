<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Muestra el historial global de actividad para el OWNER.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->isOwner() && $user->role !== 'ADMIN_INTERNO') {
            abort(403, 'No tiene permisos para ver los logs globales.');
        }

        // Cargamos los logs con sus relaciones para evitar el problema N+1
        $logs = ActivityLog::with(['user', 'school'])
            ->latest()
            ->paginate(50);

        return view('admin.activity_logs.index', compact('logs'));
    }
}

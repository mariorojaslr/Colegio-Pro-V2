<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Notifications\GlobalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'message' => 'required|string',
            'type' => 'required|in:info,alert,success,billing',
            'school_id' => 'nullable|exists:schools,id', // null = todos los colegios
        ]);

        $title = $request->title;
        $message = $request->message;
        $type = $request->type;

        if ($request->school_id) {
            // Enviar solo a los administradores de un colegio específico
            $users = User::where('school_id', $request->school_id)
                        ->where('role', 'ADMIN_COLEGIO')
                        ->get();
        } else {
            // Enviar a TODOS los administradores de colegios (Owner -> SaaS)
            $users = User::where('role', 'ADMIN_COLEGIO')->get();
        }

        Notification::send($users, new GlobalNotification($title, $message, $type));

        return redirect()->back()->with('status', '¡Comunicado Global enviado exitosamente!');
    }
}

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
            // Enviar a todos los usuarios (admins y colegiados) de un colegio específico
            $users = User::where('school_id', $request->school_id)->get();
        } else {
            // Enviar a ABSOLUTAMENTE TODOS los usuarios del sistema (SaaS Global)
            $users = User::all();
        }

        Notification::send($users, new GlobalNotification($title, $message, $type));

        return redirect()->back()->with('status', '¡Comunicado Global enviado exitosamente!');
    }
}

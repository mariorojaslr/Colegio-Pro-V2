<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isOwner()) {
            $tickets = Ticket::with(['user', 'school'])->latest()->get();
        } else {
            $tickets = Ticket::where('school_id', $user->school_id)->with('user')->latest()->get();
        }
        
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();
        // Seguridad: El owner ve todo. El admin solo de su colegio.
        if (!$user->isOwner() && $ticket->school_id !== $user->school_id) {
            abort(403);
        }

        $ticket->load(['messages.user', 'user', 'school']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        if (!$user->isOwner() && $ticket->school_id !== $user->school_id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        // Cambiar a pendiente de respuesta del usuario (o dejar abierto)
        $ticket->update(['status' => 'pending']);

        return back()->with('success', 'Respuesta institucional enviada.');
    }

    public function resolve(Ticket $ticket)
    {
        $ticket->update(['status' => 'resolved']);
        return back()->with('success', 'Ticket marcado como resuelto.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Ticket cerrado permanentemente.');
    }
}

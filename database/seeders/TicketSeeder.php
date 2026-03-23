<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();
        
        foreach ($schools as $school) {
            $admin = $school->users()->where('role', 'ADMIN_COLEGIO')->first();
            if (!$admin) continue;

            // Ticket 1: Técnico
            $ticket1 = \App\Models\Ticket::create([
                'user_id' => $admin->id,
                'school_id' => $school->id,
                'subject' => 'Consulta sobre Bunny Stream',
                'category' => 'technical',
                'status' => 'open',
                'priority' => 'medium',
            ]);

            \App\Models\TicketMessage::create([
                'ticket_id' => $ticket1->id,
                'user_id' => $admin->id,
                'message' => 'Hola, ¿cómo puedo configurar la resolución automática en Bunny.net para que mis videos fluyan mejor?',
            ]);

            // Ticket 2: Facturación
            $ticket2 = \App\Models\Ticket::create([
                'user_id' => $admin->id,
                'school_id' => $school->id,
                'subject' => 'Problema con la última factura',
                'category' => 'billing',
                'status' => 'pending',
                'priority' => 'high',
            ]);

            \App\Models\TicketMessage::create([
                'ticket_id' => $ticket2->id,
                'user_id' => $admin->id,
                'message' => 'No se me aplicó el descuento del primer mes corporativo. Por favor revisar.',
            ]);

            // Respuesta del Owner al segundo ticket
            $owner = \App\Models\User::where('role', 'OWNER')->first();
            if ($owner) {
                \App\Models\TicketMessage::create([
                    'ticket_id' => $ticket2->id,
                    'user_id' => $owner->id,
                    'message' => 'Estamos revisando tu caso. En breve te contactaremos por aquí.',
                ]);
            }
        }
    }
}

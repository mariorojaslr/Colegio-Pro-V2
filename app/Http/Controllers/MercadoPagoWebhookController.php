<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\CollegiatePayment;
use App\Models\School;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook MP Recibido', $request->all());

        // MP manda notificaciones de varios tópicos (payment, merchant_order, etc)
        $topic = $request->query('topic') ?? $request->input('type');
        $id = $request->query('id') ?? $request->input('data.id');

        if ($topic === 'payment' && $id) {
            // Como este endpoint es global, para obtener el payment info necesitamos un token.
            // Para eso, necesitamos saber a qué tenant pertenece el pago. 
            // MP en los webhooks no nos dice el external_reference en el payload inicial.
            // Una estrategia global es que MercadoPago mande el ID del tenant en la query:
            // /webhook/mercadopago?school_id=5
            
            $schoolId = $request->query('school_id');
            if (!$schoolId) {
                Log::error('Webhook MP sin school_id en la URL');
                return response()->json(['error' => 'school_id missing'], 400);
            }

            $school = School::find($schoolId);
            if (!$school || !$school->mp_access_token) {
                Log::error("Tenant no encontrado o sin token para el webhook: School ID $schoolId");
                return response()->json(['error' => 'Invalid tenant'], 400);
            }

            // Consultar a la API de MP los detalles del pago
            $response = Http::withToken($school->mp_access_token)
                ->get("https://api.mercadopago.com/v1/payments/{$id}");

            if ($response->successful()) {
                $paymentData = $response->json();
                
                // Extraer el external_reference que nosotros mandamos (UUID)
                $externalReference = $paymentData['external_reference'] ?? null;
                $status = $paymentData['status'] ?? null;
                $paymentMethod = $paymentData['payment_method_id'] ?? 'unknown';

                if ($externalReference && $status === 'approved') {
                    // Buscar el comprobante pendiente
                    $collegiatePayment = CollegiatePayment::where('external_reference', $externalReference)
                        ->where('status', 'pending')
                        ->first();

                    if ($collegiatePayment) {
                        // 1. Marcar el comprobante como pagado
                        $collegiatePayment->update([
                            'status' => 'paid',
                            'gateway_payment_id' => $id,
                            'payment_method' => $paymentMethod,
                            'paid_at' => now(),
                        ]);

                        // 2. Marcar las cuotas vinculadas como pagadas
                        foreach ($collegiatePayment->dues as $due) {
                            $due->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                            ]);
                        }

                        // 3. Registrar el ingreso en la Billetera de Mercado Pago
                        $wallet = $school->wallets()->where('type', 'mercadopago')->first();
                        if ($wallet) {
                            $wallet->addFunds(
                                $collegiatePayment->amount,
                                "Cobro de cuotas. Comprobante #{$collegiatePayment->id}",
                                $id,
                                CollegiatePayment::class
                            );
                        }

                        // Si el colegiado no tiene deudas atrasadas, marcar is_fees_compliant
                        $collegiate = $collegiatePayment->collegiate;
                        if ($collegiate->pendingDues->where('status', 'overdue')->count() === 0) {
                            $collegiate->update(['is_fees_compliant' => true]);
                        }

                        Log::info("Pago {$id} procesado exitosamente para el comprobante {$collegiatePayment->id}");
                    }
                }
            } else {
                Log::error("Error consultando API MP: {$response->body()}");
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

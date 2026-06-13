<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Subscription;
use App\Models\PaymentRecord;

class GlobalBillingController extends Controller
{
    /**
     * Vista de Facturación Central Global (Multipost style)
     */
    public function index()
    {
        // Solo accesible por el OWNER
        if (!auth()->user()->isOwner()) {
            abort(403);
        }

        $schools = School::with(['activeSubscription.plan'])->get();

        $activeSchoolsCount = 0;
        $overdueSchoolsCount = 0;
        $expiringSchoolsCount = 0;

        $totalRevenue = PaymentRecord::where('status', 'paid')->sum('amount');
        
        // Simular datos de estado para cada escuela y agregar al listado
        $clientsStatus = [];

        foreach ($schools as $school) {
            $isOverdue = false;
            $isExpiring = false;
            $statusLabel = 'AL DÍA';
            $statusBadge = 'success';
            $daysLeft = 30;

            if ($school->activeSubscription) {
                $endsAt = $school->activeSubscription->ends_at;
                if ($endsAt) {
                    $daysLeft = now()->diffInDays($endsAt, false); // false para incluir negativos
                    
                    if ($daysLeft < 0) {
                        $isOverdue = true;
                        $statusLabel = 'VENCIDA (hace ' . abs(intval($daysLeft)) . ' días)';
                        $statusBadge = 'danger';
                        $overdueSchoolsCount++;
                    } elseif ($daysLeft <= 7) {
                        $isExpiring = true;
                        $statusLabel = 'PRÓXIMO VENCIMIENTO (en ' . intval($daysLeft) . ' días)';
                        $statusBadge = 'warning';
                        $expiringSchoolsCount++;
                        $activeSchoolsCount++;
                    } else {
                        $statusLabel = 'AL DÍA (faltan ' . intval($daysLeft) . ' días)';
                        $activeSchoolsCount++;
                    }
                } else {
                    $statusLabel = 'ILIMITADO (Lifetime)';
                    $activeSchoolsCount++;
                }
            } else {
                $isOverdue = true;
                $statusLabel = 'SIN PLAN ACTIVO';
                $statusBadge = 'danger';
                $overdueSchoolsCount++;
            }

            // Pagos históricos
            $payments = PaymentRecord::where('school_id', $school->id)->latest()->take(5)->get();
            $totalPaid = PaymentRecord::where('school_id', $school->id)->where('status', 'paid')->sum('amount');

            $clientsStatus[] = (object)[
                'school' => $school,
                'status_label' => $statusLabel,
                'status_badge' => $statusBadge,
                'total_paid' => $totalPaid,
                'payments' => $payments,
                'is_overdue' => $isOverdue
            ];
        }

        // Ordenar: primero los morosos, luego los que vencen, luego los al día
        usort($clientsStatus, function($a, $b) {
            if ($a->is_overdue && !$b->is_overdue) return -1;
            if (!$a->is_overdue && $b->is_overdue) return 1;
            return 0;
        });

        $metrics = [
            'active_schools' => $activeSchoolsCount,
            'overdue_schools' => $overdueSchoolsCount,
            'expiring_schools' => $expiringSchoolsCount,
            'total_revenue' => $totalRevenue
        ];

        return view('admin.billing.global', compact('metrics', 'clientsStatus'));
    }
}

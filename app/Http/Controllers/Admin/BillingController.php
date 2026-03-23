<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->isOwner()) {
            abort(403);
        }

        $totalRevenue = PaymentRecord::sum('amount');
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $recentPayments = PaymentRecord::with('school')->latest()->take(20)->get();
        
        // Métricas básicas para el Owner
        $stats = [
            'revenue' => $totalRevenue,
            'active_subs' => $activeSubscriptions,
            'total_payments' => PaymentRecord::count(),
        ];

        return view('admin.billing.index', compact('stats', 'recentPayments'));
    }
}

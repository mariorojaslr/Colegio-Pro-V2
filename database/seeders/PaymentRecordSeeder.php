<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = \App\Models\School::all();
        $plans = \App\Models\SubscriptionPlan::all();

        foreach ($schools as $school) {
            // Un mes anterior y el actual
            $plan = $plans->where('slug', $school->plan_category)->first();
            if (!$plan) $plan = $plans->first();

            // Pago del mes pasado
            \App\Models\PaymentRecord::create([
                'school_id' => $school->id,
                'amount' => $plan->price,
                'payment_method' => 'card',
                'status' => 'paid',
                'transaction_reference' => 'BILL-' . strtoupper(uniqid()),
                'created_at' => now()->subMonth(),
            ]);

            // Pago del mes actual
            \App\Models\PaymentRecord::create([
                'school_id' => $school->id,
                'amount' => $plan->price,
                'payment_method' => 'card',
                'status' => 'paid',
                'transaction_reference' => 'BILL-' . strtoupper(uniqid()),
                'created_at' => now(),
            ]);
        }
    }
}

<?php
$schools = \App\Models\School::doesntHave('activeSubscription')->get();
foreach($schools as $school) {
    $plan = \App\Models\SubscriptionPlan::where('slug', $school->plan_category)->first() ?? \App\Models\SubscriptionPlan::first();
    if($plan) {
        \App\Models\Subscription::create([
            'school_id' => $school->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addYears(10)
        ]);
        echo 'Asignado plan ' . $plan->name . ' a ' . $school->name . PHP_EOL;
    }
}

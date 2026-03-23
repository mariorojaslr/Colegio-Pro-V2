<?php

use App\Models\School;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;

$schools = School::all();
foreach ($schools as $school) {
    $plan = SubscriptionPlan::where('slug', $school->plan_category)->first();
    if ($plan) {
        $school->activeSubscription()->update(['subscription_plan_id' => $plan->id]);
        echo "Updated subscription for school: " . $school->name . " to plan: " . $plan->name . "\n";
    }
}

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $schoolId = 1; 
    $activeFee = App\Models\MembershipFee::where('school_id', $schoolId)->where('is_active', true)->first(); 
    if(!$activeFee) { 
        echo 'No active fee'; 
        exit; 
    } 
    $collegiates = App\Models\Collegiate::where('school_id', $schoolId)->where('status', 'active')->get(); 
    $dueDate = \Carbon\Carbon::now()->endOfMonth(); 
    $c = $collegiates->first(); 
    if($c) { 
        App\Models\CollegiateDue::create(['collegiate_id' => $c->id, 'amount' => $activeFee->amount, 'due_date' => clone $dueDate, 'status' => 'pending']); 
        echo 'Success'; 
    } 
} catch (\Exception $e) { 
    echo 'ERROR: ' . $e->getMessage(); 
}

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Reset all
DB::table('board_members')->update(['collegiate_id' => null]);

$members = App\Models\BoardMember::all();
foreach ($members as $member) {
    if ($member->name) {
        $cleanName = trim(str_replace(['Lic.', 'Ing.', 'Dr.', 'Dra.', 'Mgter.'], '', $member->name));
        $nameParts = explode(' ', $cleanName);
        $firstName = trim($nameParts[0] ?? '');
        $lastName = trim(end($nameParts) ?? '');
        
        if (!empty($firstName) && !empty($lastName)) {
            $collegiate = App\Models\Collegiate::where('first_name', 'LIKE', '%' . $firstName . '%')
                ->where('last_name', 'LIKE', '%' . $lastName . '%')
                ->first();
                
            if ($collegiate) {
                $member->collegiate_id = $collegiate->id;
                $member->save();
                echo "Linked " . $member->name . " to " . $collegiate->id . " (" . $collegiate->first_name . " " . $collegiate->last_name . ")\n";
            } else {
                echo "Could not find collegiate for " . $member->name . " (searched $firstName $lastName)\n";
            }
        }
    }
}
echo "Done.\n";

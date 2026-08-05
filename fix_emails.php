<?php
$collegiates = \App\Models\Collegiate::whereNotNull("user_id")->get();
$count = 0;
foreach($collegiates as $c) {
    $u = \App\Models\User::find($c->user_id);
    if ($u && $u->email !== $c->email) {
        $u->email = $c->email;
        $u->save();
        $count++;
    }
}
echo "Synced: " . $count;


<?php
$c = \App\Models\Collegiate::where("email", "like", "%karina.arias%")->first();
if ($c) {
    echo "Collegiate:\n";
    print_r($c->toArray());
    echo "\nUser:\n";
    $u = \App\Models\User::find($c->user_id);
    print_r($u ? $u->toArray() : "No user");
} else {
    echo "Not found";
}


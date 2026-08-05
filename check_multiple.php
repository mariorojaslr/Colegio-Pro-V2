<?php
$users = \App\Models\User::where("email", "karina.arias.claude@gmail.com")->get();
echo "Count: " . $users->count() . "\n";
foreach($users as $u) {
    echo "ID: " . $u->id . " School: " . $u->school_id . "\n";
}


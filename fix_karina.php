<?php
$orphan = \App\Models\User::find(163);
if ($orphan) $orphan->delete();
$u = \App\Models\User::find(165);
$c = \App\Models\Collegiate::where("user_id", 165)->first();
if ($u && $c) {
    $u->email = "karina.arias.claude@gmail.com";
    $u->password = \Illuminate\Support\Facades\Hash::make("Karina-12345");
    $u->save();
    $c->email = "karina.arias.claude@gmail.com";
    $c->save();
    echo "Karina fixed successfully!";
} else {
    echo "Error finding Karina.";
}


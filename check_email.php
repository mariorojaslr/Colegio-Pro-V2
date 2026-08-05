<?php
$c = \App\Models\Collegiate::where("email", "karina.arias.claude@gmail.com")->first();
$u = \App\Models\User::where("email", "karina.arias.claude@gmail.com")->first();
echo "Collegiate: " . ($c ? $c->id : "No") . "\n";
echo "User: " . ($u ? $u->id : "No") . "\n";


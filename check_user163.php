<?php
$u = \App\Models\User::find(163);
echo json_encode($u);
$c = \App\Models\Collegiate::where("user_id", 163)->first();
echo "\nCollegiate for 163: " . ($c ? json_encode($c) : "No");


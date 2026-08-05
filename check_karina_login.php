<?php
$u = \App\Models\User::where("email", "karina.arias.claude@gmail.com")->first();
if ($u) {
    echo "Found user!\n";
    echo "ID: " . $u->id . "\n";
    echo "Email: " . $u->email . "\n";
    echo "Is Active: " . $u->is_active . "\n";
    echo "School ID: " . $u->school_id . "\n";
    $matches = \Illuminate\Support\Facades\Hash::check("Karina-12345", $u->password);
    echo "Password Matches Karina-12345: " . ($matches ? "YES" : "NO") . "\n";
} else {
    echo "User NOT found!\n";
}


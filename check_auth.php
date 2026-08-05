<?php
$attempt = Auth::attempt(["email" => "karina.arias.claude@gmail.com", "password" => "Karina-12345"]);
echo "Auth Attempt: " . ($attempt ? "SUCCESS" : "FAILED") . "\n";


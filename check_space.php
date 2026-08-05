<?php
$u = \App\Models\User::find(165);
echo "Email length: " . strlen($u->email) . "\n";
echo "Email exact: [" . $u->email . "]\n";


<?php
$s = \App\Models\School::where("custom_domain", "cotolar.gentepiola.net")->first();
echo "Cotolar School ID: " . ($s ? $s->id : "NOT FOUND");


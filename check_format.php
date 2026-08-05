<?php
$reqs = \App\Models\ComplianceRequirement::select("delivery_format")->distinct()->get(); echo json_encode($reqs);


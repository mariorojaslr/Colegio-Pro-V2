<?php
$envPath = __DIR__ . '/.env';
$env = file_get_contents($envPath);

// Replace default values with the user's actual Bunny.net credentials
$env = preg_replace('/BUNNY_STORAGE_ZONE=.*/', 'BUNNY_USERNAME=gente-piola', $env);
$env = preg_replace('/BUNNY_API_KEY=.*/', 'BUNNY_PASSWORD=8b078c5f-ad56-4ad8-a4a7b28e775f-63eb-4d16', $env);
$env = preg_replace('/BUNNY_STORAGE_REGION=.*/', 'BUNNY_HOSTNAME=ny.storage.bunnycdn.com', $env);
$env = preg_replace('/BUNNY_PULL_ZONE_URL=.*/', 'BUNNY_PULL_ZONE_URL=https://gentepiola.b-cdn.net', $env);

file_put_contents($envPath, $env);
echo "Env updated.\n";

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey = env('GEMINI_API_KEY');
$systemPrompt = 'Responde en JSON {"response": "hola", "action_type": "none"}';

$response = Illuminate\Support\Facades\Http::withHeaders([
    'Content-Type' => 'application/json'
])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=$apiKey", [
    'contents' => [['parts' => [['text' => $systemPrompt]]]],
    'generationConfig' => [
        'responseMimeType' => 'application/json'
    ]
]);

echo $response->body();

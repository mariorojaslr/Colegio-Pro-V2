<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'bunny' => [
        'storage' => [
            'api_key' => env('BUNNY_STORAGE_API_KEY', env('BUNNY_API_KEY', env('BUNNY_PASSWORD'))),
            'zone' => env('BUNNY_STORAGE_ZONE', env('BUNNY_USERNAME')),
            'region' => env('BUNNY_STORAGE_REGION', env('BUNNY_HOSTNAME') ? str_replace('.bunnycdn.com', '', env('BUNNY_HOSTNAME')) : 'storage'),
            'pull_zone_url' => env('BUNNY_PULL_ZONE_URL'),
        ],
        'stream' => [
            'api_key' => env('BUNNY_STREAM_API_KEY', env('BUNNY_API_KEY')),
            'library_id' => env('BUNNY_STREAM_LIBRARY_ID'),
        ],
    ],

    'mercadopago' => [
        'client_id' => env('MP_MARKETPLACE_CLIENT_ID'),
        'client_secret' => env('MP_MARKETPLACE_CLIENT_SECRET'),
        'redirect_uri' => env('MP_MARKETPLACE_REDIRECT_URI'),
    ],

];

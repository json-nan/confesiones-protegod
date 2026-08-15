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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | Text-to-speech for confessions. Voice and language are fixed for the
    | product rather than per-request, so they live here instead of in a
    | caller's arguments.
    */
    'lemonfox' => [
        'key' => env('LEMONFOX_API_KEY', ''),
        'endpoint' => env('LEMONFOX_ENDPOINT', 'https://api.lemonfox.ai/v1/audio/speech'),
        'voice' => env('LEMONFOX_VOICE', 'dora'),
        'language' => env('LEMONFOX_LANGUAGE', 'es'),

        // The whole confession is read — Lemonfox documents no cap on `input`
        // and charges $2.50 per million characters, so the 10 000 the public
        // form allows costs about two cents. Synthesis time, not length, is
        // the real constraint: rendering is synchronous, so this timeout is
        // the ceiling on how long a panel request can hang on one click.
        'timeout' => (int) env('LEMONFOX_TIMEOUT', 180),

        // Where the rendered mp3 lands.
        'disk' => env('TTS_DISK', 'r2'),

        // Lifetime of the pre-signed playback URL, in minutes. Long enough to
        // outlast a panel session left open, short enough that a leaked link
        // goes stale.
        'url_ttl' => (int) env('TTS_URL_TTL', 360),
    ],

];

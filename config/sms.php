<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration du service SMS
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient la configuration pour l'envoi de SMS via différentes
    | APIs (Orange, Wave, etc.)
    |
    */

    // Activer/désactiver le service SMS
    'enabled' => env('SMS_ENABLED', false),

    // Fournisseur SMS à utiliser
    'provider' => env('SMS_PROVIDER', 'orange'), // orange, wave, generic

    // Configuration de l'API
    'api_key' => env('SMS_API_KEY'),
    'api_url' => env('SMS_API_URL'),
    'sender_name' => env('SMS_SENDER_NAME', 'CSAR'),

    // Comportement en cas d'erreur
    'fail_on_error' => env('SMS_FAIL_ON_ERROR', false), // Si true, fait échouer la demande en cas d'erreur SMS

    // Configuration spécifique par fournisseur
    'providers' => [
        'orange' => [
            'api_url' => env('ORANGE_SMS_API_URL', 'https://api.orange.com/smsmessaging/v1/outbound'),
            'api_key' => env('ORANGE_SMS_API_KEY'),
            'sender_name' => env('ORANGE_SMS_SENDER', 'CSAR'),
        ],
        'wave' => [
            'api_url' => env('WAVE_SMS_API_URL'),
            'api_key' => env('WAVE_SMS_API_KEY'),
            'sender_name' => env('WAVE_SMS_SENDER', 'CSAR'),
        ],
        'generic' => [
            'api_url' => env('GENERIC_SMS_API_URL'),
            'api_key' => env('GENERIC_SMS_API_KEY'),
            'sender_name' => env('GENERIC_SMS_SENDER', 'CSAR'),
        ],
    ],

    // Messages par défaut
    'messages' => [
        'confirmation' => 'Votre demande a bien été transmise au CSAR. Code de suivi: {tracking_code}',
        'status_update' => 'Mise à jour de votre demande #{tracking_code}: {status}',
        'reminder' => 'Rappel: Votre demande #{tracking_code} est en cours de traitement.',
    ],

    // Limites et quotas
    'limits' => [
        'max_retries' => 3,
        'retry_delay' => 5, // secondes
        'daily_limit' => 1000,
        'rate_limit' => 10, // SMS par minute
    ],

    // Logs et monitoring
    'logging' => [
        'enabled' => true,
        'log_level' => 'info', // debug, info, warning, error
        'log_failures' => true,
        'log_success' => true,
    ],
];
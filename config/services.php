<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Newsletter Service Configuration
    |--------------------------------------------------------------------------
    | Providers: mailchimp, sendgrid, brevo (sendinblue)
    */
    'newsletter' => [
        'provider' => env('NEWSLETTER_PROVIDER', 'mailchimp'),
        'api_key' => env('NEWSLETTER_API_KEY'),
        'list_id' => env('NEWSLETTER_LIST_ID'),
        'from_name' => env('NEWSLETTER_FROM_NAME', 'CSAR'),
        'reply_to' => env('NEWSLETTER_REPLY_TO', 'noreply@csar.sn'),
        'sender_id' => env('NEWSLETTER_SENDER_ID'), // Pour SendGrid
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Service Configuration
    |--------------------------------------------------------------------------
    | Providers: twilio, vonage, infobip, africastalking
    */
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'twilio'),
        'max_per_month' => env('SMS_MAX_PER_MONTH', 1000),

        'twilio' => [
            'account_sid' => env('TWILIO_ACCOUNT_SID'),
            'auth_token' => env('TWILIO_AUTH_TOKEN'),
            'from_number' => env('TWILIO_FROM_NUMBER'),
        ],

        'vonage' => [
            'api_key' => env('VONAGE_API_KEY'),
            'api_secret' => env('VONAGE_API_SECRET'),
            'from' => env('VONAGE_FROM', 'CSAR'),
        ],

        'infobip' => [
            'api_key' => env('INFOBIP_API_KEY'),
            'base_url' => env('INFOBIP_BASE_URL', 'https://api.infobip.com'),
            'from' => env('INFOBIP_FROM', 'CSAR'),
        ],

        'africastalking' => [
            'username' => env('AFRICASTALKING_USERNAME'),
            'api_key' => env('AFRICASTALKING_API_KEY'),
            'from' => env('AFRICASTALKING_FROM', 'CSAR'),
        ],
    ],
];

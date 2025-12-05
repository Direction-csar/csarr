<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration de sécurité CSAR
    |--------------------------------------------------------------------------
    |
    | Configuration centralisée pour tous les aspects de sécurité
    | de la plateforme CSAR
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Authentification à deux facteurs
    |--------------------------------------------------------------------------
    */
    'two_factor' => [
        'enabled' => env('TWO_FACTOR_ENABLED', true),
        'required_for_admin' => env('TWO_FACTOR_REQUIRED_ADMIN', true),
        'issuer' => env('TWO_FACTOR_ISSUER', 'CSAR Platform'),
        'window' => env('TWO_FACTOR_WINDOW', 1), // Fenêtre de tolérance en secondes
    ],

    /*
    |--------------------------------------------------------------------------
    | Limitation de taux (Rate Limiting)
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'login_attempts' => env('RATE_LIMIT_LOGIN', 5),
        'login_window' => env('RATE_LIMIT_LOGIN_WINDOW', 15), // minutes
        'api_requests' => env('RATE_LIMIT_API', 100),
        'api_window' => env('RATE_LIMIT_API_WINDOW', 60), // minutes
        'contact_form' => env('RATE_LIMIT_CONTACT', 3),
        'contact_window' => env('RATE_LIMIT_CONTACT_WINDOW', 60), // minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocage d'IP
    |--------------------------------------------------------------------------
    */
    'ip_blocking' => [
        'enabled' => env('IP_BLOCKING_ENABLED', true),
        'max_failed_attempts' => env('IP_BLOCKING_MAX_ATTEMPTS', 10),
        'block_duration' => env('IP_BLOCKING_DURATION', 3600), // secondes
        'whitelist' => [
            '127.0.0.1',
            '::1',
            // Ajouter les IPs de confiance ici
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation des mots de passe
    |--------------------------------------------------------------------------
    */
    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 8),
        'require_uppercase' => env('PASSWORD_REQUIRE_UPPERCASE', true),
        'require_lowercase' => env('PASSWORD_REQUIRE_LOWERCASE', true),
        'require_numbers' => env('PASSWORD_REQUIRE_NUMBERS', true),
        'require_symbols' => env('PASSWORD_REQUIRE_SYMBOLS', true),
        'max_age_days' => env('PASSWORD_MAX_AGE_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Chiffrement et sécurité des données
    |--------------------------------------------------------------------------
    */
    'encryption' => [
        'sensitive_data' => env('ENCRYPT_SENSITIVE_DATA', true),
        'audit_logs' => env('ENCRYPT_AUDIT_LOGS', true),
        'user_data' => env('ENCRYPT_USER_DATA', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Journal d'audit
    |--------------------------------------------------------------------------
    */
    'audit' => [
        'enabled' => env('AUDIT_ENABLED', true),
        'retention_days' => env('AUDIT_RETENTION_DAYS', 365),
        'log_failed_logins' => env('AUDIT_LOG_FAILED_LOGINS', true),
        'log_successful_logins' => env('AUDIT_LOG_SUCCESSFUL_LOGINS', true),
        'log_data_changes' => env('AUDIT_LOG_DATA_CHANGES', true),
        'log_security_events' => env('AUDIT_LOG_SECURITY_EVENTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTPS et SSL
    |--------------------------------------------------------------------------
    */
    'https' => [
        'force_https' => env('FORCE_HTTPS', true),
        'hsts_enabled' => env('HSTS_ENABLED', true),
        'hsts_max_age' => env('HSTS_MAX_AGE', 31536000), // 1 an
        'secure_cookies' => env('SECURE_COOKIES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Headers de sécurité
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'x_frame_options' => env('X_FRAME_OPTIONS', 'DENY'),
        'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'x_xss_protection' => env('X_XSS_PROTECTION', '1; mode=block'),
        'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),
        'content_security_policy' => env('CONTENT_SECURITY_POLICY', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:; connect-src 'self';"),
    ],

    /*
    |--------------------------------------------------------------------------
    | Détection d'intrusion
    |--------------------------------------------------------------------------
    */
    'intrusion_detection' => [
        'enabled' => env('INTRUSION_DETECTION_ENABLED', true),
        'suspicious_patterns' => [
            'sql_injection' => [
                '/union\s+select/i',
                '/drop\s+table/i',
                '/delete\s+from/i',
                '/insert\s+into/i',
                '/update\s+set/i',
                '/exec\s*\(/i',
                '/script\s*>/i',
            ],
            'xss_attempts' => [
                '/<script/i',
                '/javascript:/i',
                '/vbscript:/i',
                '/onload\s*=/i',
                '/onerror\s*=/i',
            ],
            'path_traversal' => [
                '/\.\.\//',
                '/\.\.\\\\/',
                '/%2e%2e%2f/i',
                '/%2e%2e%5c/i',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications de sécurité
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'security_alerts' => env('SECURITY_ALERTS_ENABLED', true),
        'admin_email' => env('SECURITY_ADMIN_EMAIL', 'admin@csar.sn'),
        'alert_on_failed_login' => env('ALERT_ON_FAILED_LOGIN', true),
        'alert_on_suspicious_activity' => env('ALERT_ON_SUSPICIOUS_ACTIVITY', true),
        'alert_on_ip_block' => env('ALERT_ON_IP_BLOCK', true),
    ],
];

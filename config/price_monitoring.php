<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Price Monitoring Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour la surveillance automatique des prix
    |
    */

    'enabled' => env('PRICE_MONITORING_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Data Sources
    |--------------------------------------------------------------------------
    |
    | Sources de données pour la surveillance des prix
    |
    */

    'data_sources' => [
        [
            'name' => 'ANSD_Market_Prices',
            'url' => 'https://api.ansd.sn/market-prices',
            'headers' => [
                'Authorization' => 'Bearer ' . env('ANSD_API_KEY'),
                'Accept' => 'application/json',
            ],
            'data_key' => 'prices',
            'market_location' => 'Dakar',
            'region' => 'Dakar',
            'currency' => 'XOF',
            'unit' => 'kg',
            'market_type' => 'retail',
            'threshold_percentage' => 5.0,
            'send_sms' => true,
        ],
        
        [
            'name' => 'Market_Watch_Senegal',
            'url' => 'https://api.marketwatch.sn/prices',
            'headers' => [
                'X-API-Key' => env('MARKET_WATCH_API_KEY'),
                'Accept' => 'application/json',
            ],
            'data_key' => 'market_data',
            'market_location' => 'Thiès',
            'region' => 'Thiès',
            'currency' => 'XOF',
            'unit' => 'kg',
            'market_type' => 'wholesale',
            'threshold_percentage' => 7.0,
            'send_sms' => true,
        ],

        [
            'name' => 'Agricultural_Monitoring',
            'url' => 'https://api.agriculture.sn/crop-prices',
            'headers' => [
                'Authorization' => 'Bearer ' . env('AGRICULTURE_API_KEY'),
                'Accept' => 'application/json',
            ],
            'data_key' => 'crop_prices',
            'market_location' => 'Kaolack',
            'region' => 'Kaolack',
            'currency' => 'XOF',
            'unit' => 'kg',
            'market_type' => 'export',
            'threshold_percentage' => 10.0,
            'send_sms' => true,
        ],

        // Source de simulation pour les tests
        [
            'name' => 'Simulation_Data',
            'url' => 'https://api.simulation-prices.com/data',
            'headers' => [
                'Accept' => 'application/json',
            ],
            'data_key' => 'prices',
            'market_location' => 'Test Market',
            'region' => 'Dakar',
            'currency' => 'XOF',
            'unit' => 'kg',
            'market_type' => 'retail',
            'threshold_percentage' => 5.0,
            'send_sms' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Settings
    |--------------------------------------------------------------------------
    |
    | Paramètres de surveillance
    |
    */

    'monitoring' => [
        'check_interval' => env('PRICE_CHECK_INTERVAL', 3600), // 1 heure en secondes
        'batch_size' => env('PRICE_BATCH_SIZE', 100),
        'timeout' => env('PRICE_API_TIMEOUT', 30),
        'retry_attempts' => env('PRICE_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('PRICE_RETRY_DELAY', 300), // 5 minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert Thresholds
    |--------------------------------------------------------------------------
    |
    | Seuils pour les différents types d'alertes
    |
    */

    'thresholds' => [
        'normal_change' => 5.0,      // Changement normal
        'significant_change' => 10.0, // Changement significatif
        'abnormal_change' => 20.0,   // Changement anormal
        'critical_change' => 50.0,   // Changement critique
    ],

    /*
    |--------------------------------------------------------------------------
    | Severity Mapping
    |--------------------------------------------------------------------------
    |
    | Mapping des pourcentages de changement vers les niveaux de sévérité
    |
    */

    'severity_mapping' => [
        'low' => [
            'min' => 0,
            'max' => 5.0,
        ],
        'medium' => [
            'min' => 5.0,
            'max' => 10.0,
        ],
        'high' => [
            'min' => 10.0,
            'max' => 20.0,
        ],
        'critical' => [
            'min' => 20.0,
            'max' => PHP_FLOAT_MAX,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Categories
    |--------------------------------------------------------------------------
    |
    | Catégories de produits surveillés
    |
    */

    'product_categories' => [
        'cereals' => 'Céréales',
        'vegetables' => 'Légumes',
        'fruits' => 'Fruits',
        'meat' => 'Viandes',
        'fish' => 'Poissons',
        'dairy' => 'Produits laitiers',
        'spices' => 'Épices',
        'oilseeds' => 'Oléagineux',
        'other' => 'Autres',
    ],

    /*
    |--------------------------------------------------------------------------
    | Market Types
    |--------------------------------------------------------------------------
    |
    | Types de marchés surveillés
    |
    */

    'market_types' => [
        'wholesale' => 'Gros',
        'retail' => 'Détail',
        'export' => 'Export',
        'import' => 'Import',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Paramètres de notification
    |
    */

    'notifications' => [
        'sms_enabled' => env('PRICE_SMS_NOTIFICATIONS', true),
        'email_enabled' => env('PRICE_EMAIL_NOTIFICATIONS', true),
        'webhook_enabled' => env('PRICE_WEBHOOK_NOTIFICATIONS', false),
        'webhook_url' => env('PRICE_WEBHOOK_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Retention
    |--------------------------------------------------------------------------
    |
    | Paramètres de rétention des données
    |
    */

    'data_retention' => [
        'price_data_days' => env('PRICE_DATA_RETENTION_DAYS', 365), // 1 an
        'alerts_days' => env('PRICE_ALERTS_RETENTION_DAYS', 90), // 3 mois
        'cleanup_enabled' => env('PRICE_CLEANUP_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Keys
    |--------------------------------------------------------------------------
    |
    | Clés API pour les différentes sources
    |
    */

    'api_keys' => [
        'ansd' => env('ANSD_API_KEY'),
        'market_watch' => env('MARKET_WATCH_API_KEY'),
        'agriculture' => env('AGRICULTURE_API_KEY'),
    ],
];

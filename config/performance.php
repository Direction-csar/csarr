<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des performances
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les paramètres d'optimisation des performances
    | pour la plateforme CSAR.
    |
    */

    'cache' => [
        'enabled' => env('PERFORMANCE_CACHE_ENABLED', true),
        'ttl' => env('PERFORMANCE_CACHE_TTL', 3600), // 1 heure
        'prefix' => env('PERFORMANCE_CACHE_PREFIX', 'csar_perf'),
    ],

    'images' => [
        'optimize' => env('PERFORMANCE_OPTIMIZE_IMAGES', true),
        'quality' => env('PERFORMANCE_IMAGE_QUALITY', 85),
        'max_width' => env('PERFORMANCE_MAX_IMAGE_WIDTH', 1920),
        'webp_enabled' => env('PERFORMANCE_WEBP_ENABLED', true),
        'lazy_loading' => env('PERFORMANCE_LAZY_LOADING', true),
    ],

    'css' => [
        'minify' => env('PERFORMANCE_MINIFY_CSS', true),
        'combine' => env('PERFORMANCE_COMBINE_CSS', true),
        'critical_css' => env('PERFORMANCE_CRITICAL_CSS', true),
    ],

    'js' => [
        'minify' => env('PERFORMANCE_MINIFY_JS', true),
        'combine' => env('PERFORMANCE_COMBINE_JS', true),
        'defer' => env('PERFORMANCE_DEFER_JS', true),
    ],

    'database' => [
        'query_cache' => env('PERFORMANCE_QUERY_CACHE', true),
        'connection_pooling' => env('PERFORMANCE_CONNECTION_POOLING', true),
        'slow_query_log' => env('PERFORMANCE_SLOW_QUERY_LOG', false),
    ],

    'http' => [
        'compression' => env('PERFORMANCE_HTTP_COMPRESSION', true),
        'etags' => env('PERFORMANCE_ETAGS', true),
        'expires' => env('PERFORMANCE_EXPIRES', true),
        'max_age' => env('PERFORMANCE_MAX_AGE', 31536000), // 1 an
    ],

    'cdn' => [
        'enabled' => env('PERFORMANCE_CDN_ENABLED', false),
        'url' => env('PERFORMANCE_CDN_URL', ''),
        'assets' => [
            'images' => env('PERFORMANCE_CDN_IMAGES', true),
            'css' => env('PERFORMANCE_CDN_CSS', true),
            'js' => env('PERFORMANCE_CDN_JS', true),
        ],
    ],

    'monitoring' => [
        'enabled' => env('PERFORMANCE_MONITORING', false),
        'slow_request_threshold' => env('PERFORMANCE_SLOW_REQUEST_THRESHOLD', 2000), // 2 secondes
        'memory_threshold' => env('PERFORMANCE_MEMORY_THRESHOLD', 128), // 128 MB
    ],

    'search' => [
        'enabled' => env('PERFORMANCE_SEARCH_ENABLED', true),
        'cache_results' => env('PERFORMANCE_SEARCH_CACHE', true),
        'cache_ttl' => env('PERFORMANCE_SEARCH_CACHE_TTL', 1800), // 30 minutes
        'max_results' => env('PERFORMANCE_SEARCH_MAX_RESULTS', 20),
    ],

    'pagination' => [
        'default_per_page' => env('PERFORMANCE_PAGINATION_DEFAULT', 12),
        'max_per_page' => env('PERFORMANCE_PAGINATION_MAX', 50),
    ],

    'api' => [
        'rate_limiting' => env('PERFORMANCE_API_RATE_LIMITING', true),
        'rate_limit' => env('PERFORMANCE_API_RATE_LIMIT', 60), // 60 requêtes par minute
        'cache_responses' => env('PERFORMANCE_API_CACHE', true),
        'response_compression' => env('PERFORMANCE_API_COMPRESSION', true),
    ],
];



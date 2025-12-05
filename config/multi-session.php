<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Multi-Session Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour permettre les sessions multiples entre interfaces
    | Admin, DG, Responsable, et Agent.
    |
    */

    'enabled' => env('MULTI_SESSION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Session Names
    |--------------------------------------------------------------------------
    |
    | Noms des sessions pour chaque interface
    |
    */
    'session_names' => [
        'admin' => 'csar_admin_session',
        'dg' => 'csar_dg_session',
        'responsable' => 'csar_responsable_session',
        'agent' => 'csar_agent_session',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Roles
    |--------------------------------------------------------------------------
    |
    | Rôles autorisés pour chaque interface
    |
    */
    'allowed_roles' => [
        'admin' => ['admin'],
        'dg' => ['dg', 'admin'],
        'responsable' => ['responsable', 'admin'],
        'agent' => ['agent', 'admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Durée de vie des sessions en minutes
    |
    */
    'lifetime' => env('MULTI_SESSION_LIFETIME', 480), // 8 heures

    /*
    |--------------------------------------------------------------------------
    | Auto Logout
    |--------------------------------------------------------------------------
    |
    | Déconnexion automatique après inactivité
    |
    */
    'auto_logout' => env('MULTI_SESSION_AUTO_LOGOUT', true),

    /*
    |--------------------------------------------------------------------------
    | Max Concurrent Sessions
    |--------------------------------------------------------------------------
    |
    | Nombre maximum de sessions simultanées par utilisateur
    |
    */
    'max_concurrent_sessions' => env('MULTI_SESSION_MAX_CONCURRENT', 4),

    /*
    |--------------------------------------------------------------------------
    | Session Cleanup
    |--------------------------------------------------------------------------
    |
    | Nettoyage automatique des sessions expirées
    |
    */
    'cleanup_enabled' => env('MULTI_SESSION_CLEANUP', true),
    'cleanup_interval' => env('MULTI_SESSION_CLEANUP_INTERVAL', 60), // minutes
]; 
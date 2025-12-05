<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class MultiSessionHelper
{
    /**
     * Vérifier si l'utilisateur peut accéder à une interface
     */
    public static function canAccessInterface(User $user, string $interface): bool
    {
        $allowedRoles = config("multi-session.allowed_roles.{$interface}", []);
        return in_array($user->role, $allowedRoles);
    }

    /**
     * Créer une session pour une interface spécifique
     */
    public static function createInterfaceSession(User $user, string $interface): void
    {
        $sessionName = config("multi-session.session_names.{$interface}");
        $lifetime = config('multi-session.lifetime', 480);

        Session::put($sessionName, [
            'user_id' => $user->id,
            'interface' => $interface,
            'logged_in_at' => now(),
            'expires_at' => now()->addMinutes($lifetime),
        ]);
    }

    /**
     * Vérifier si l'utilisateur est connecté à une interface
     */
    public static function isLoggedInToInterface(string $interface): bool
    {
        $sessionName = config("multi-session.session_names.{$interface}");
        
        if (!Session::has($sessionName) || !Auth::check()) {
            return false;
        }

        $sessionData = Session::get($sessionName);
        
        // Vérifier si la session n'est pas expirée
        if (isset($sessionData['expires_at']) && now()->isAfter($sessionData['expires_at'])) {
            self::clearInterfaceSession($interface);
            return false;
        }

        return $sessionData['user_id'] === Auth::id();
    }

    /**
     * Nettoyer la session d'une interface
     */
    public static function clearInterfaceSession(string $interface): void
    {
        $sessionName = config("multi-session.session_names.{$interface}");
        Session::forget($sessionName);
    }

    /**
     * Obtenir toutes les interfaces actives pour un utilisateur
     */
    public static function getActiveInterfaces(): array
    {
        $interfaces = ['admin', 'dg', 'responsable', 'agent'];
        $activeInterfaces = [];

        foreach ($interfaces as $interface) {
            if (self::isLoggedInToInterface($interface)) {
                $activeInterfaces[] = $interface;
            }
        }

        return $activeInterfaces;
    }

    /**
     * Obtenir le nombre de sessions actives
     */
    public static function getActiveSessionCount(): int
    {
        return count(self::getActiveInterfaces());
    }

    /**
     * Vérifier si l'utilisateur peut créer une nouvelle session
     */
    public static function canCreateNewSession(): bool
    {
        $maxSessions = config('multi-session.max_concurrent_sessions', 4);
        return self::getActiveSessionCount() < $maxSessions;
    }

    /**
     * Nettoyer toutes les sessions expirées
     */
    public static function cleanupExpiredSessions(): void
    {
        if (!config('multi-session.cleanup_enabled', true)) {
            return;
        }

        $interfaces = ['admin', 'dg', 'responsable', 'agent'];

        foreach ($interfaces as $interface) {
            $sessionName = config("multi-session.session_names.{$interface}");
            
            if (Session::has($sessionName)) {
                $sessionData = Session::get($sessionName);
                
                if (isset($sessionData['expires_at']) && now()->isAfter($sessionData['expires_at'])) {
                    self::clearInterfaceSession($interface);
                }
            }
        }
    }

    /**
     * Prolonger la session d'une interface
     */
    public static function extendInterfaceSession(string $interface): void
    {
        if (self::isLoggedInToInterface($interface)) {
            $sessionName = config("multi-session.session_names.{$interface}");
            $sessionData = Session::get($sessionName);
            $lifetime = config('multi-session.lifetime', 480);

            $sessionData['expires_at'] = now()->addMinutes($lifetime);
            Session::put($sessionName, $sessionData);
        }
    }

    /**
     * Obtenir les informations de session d'une interface
     */
    public static function getInterfaceSessionInfo(string $interface): ?array
    {
        $sessionName = config("multi-session.session_names.{$interface}");
        
        if (!Session::has($sessionName)) {
            return null;
        }

        return Session::get($sessionName);
    }

    /**
     * Forcer la déconnexion de toutes les interfaces
     */
    public static function logoutFromAllInterfaces(): void
    {
        $interfaces = ['admin', 'dg', 'responsable', 'agent'];

        foreach ($interfaces as $interface) {
            self::clearInterfaceSession($interface);
        }

        Auth::logout();
        Session::flush();
    }
} 
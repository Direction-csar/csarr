<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MultiSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Déterminer le type d'interface basé sur l'URL
        $interface = $this->getInterfaceType($request);
        
        // Créer un nom de session unique pour chaque interface
        $sessionName = 'csar_' . $interface . '_session';
        
        // Configurer la session pour cette interface
        config(['session.cookie' => $sessionName]);
        
        // Si l'utilisateur est connecté, vérifier qu'il a le bon rôle pour cette interface
        if (Auth::check()) {
            $user = Auth::user();
            $allowedRoles = $this->getAllowedRoles($interface);
            
            if (!in_array($user->role, $allowedRoles)) {
                // L'utilisateur n'a pas le bon rôle pour cette interface
                Auth::logout();
                Session::flush();
                
                return redirect()->route('login')->with('error', 'Vous n\'avez pas les permissions pour accéder à cette interface.');
            }
        }
        
        return $next($request);
    }
    
    /**
     * Déterminer le type d'interface basé sur l'URL
     */
    private function getInterfaceType(Request $request): string
    {
        $path = $request->path();
        
        if (str_starts_with($path, 'admin')) {
            return 'admin';
        } elseif (str_starts_with($path, 'dg')) {
            return 'dg';
        } elseif (str_starts_with($path, 'responsable')) {
            return 'responsable';
        } elseif (str_starts_with($path, 'agent')) {
            return 'agent';
        } else {
            return 'public';
        }
    }
    
    /**
     * Obtenir les rôles autorisés pour chaque interface
     */
    private function getAllowedRoles(string $interface): array
    {
        return match($interface) {
            'admin' => ['admin'],
            'dg' => ['dg', 'admin'],
            'responsable' => ['responsable', 'admin'],
            'agent' => ['agent', 'admin'],
            default => ['admin', 'dg', 'responsable', 'agent']
        };
    }
}

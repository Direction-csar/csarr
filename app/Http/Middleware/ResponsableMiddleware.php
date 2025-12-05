<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ResponsableMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette interface.');
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur a le rôle responsable ou admin
        if (!in_array($user->role_id, [1, 3])) { // 1 = admin, 3 = responsable
            Auth::logout();
            Session::flush();
            return redirect()->route('responsable.login')->with('error', 'Vous n\'avez pas les permissions pour accéder à l\'interface Responsable.');
        }

        // Vérifier si l'utilisateur est connecté à l'interface responsable
        $sessionKey = 'csar_responsable_session';
        if (!Session::has($sessionKey)) {
            // Créer la session pour cette interface
            Session::put($sessionKey, [
                'user_id' => $user->id,
                'interface' => 'responsable',
                'logged_in_at' => now(),
            ]);
        }

        return $next($request);
    }
}

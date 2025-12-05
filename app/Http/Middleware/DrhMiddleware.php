<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrhMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Autoriser les rôles: drh, admin, dg
        if ($user && in_array($user->role_id, [1, 2, 5])) { // 1 = admin, 2 = dg, 5 = drh
            return $next($request);
        }

        // Rediriger vers la page de connexion DRH avec un message
        return redirect()->route('drh.login')
            ->withErrors(['email' => "Vous n'avez pas les permissions pour accéder à l'interface DRH."]);
    }
}

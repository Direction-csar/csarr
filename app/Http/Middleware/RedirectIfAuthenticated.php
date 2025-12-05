<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Si l'utilisateur est connecté, rediriger vers le dashboard approprié
                $user = Auth::guard($guard)->user();
                
                if ($user->role_id === 1) {
                    return redirect('/admin');
                } elseif ($user->role_id === 2) {
                    return redirect('/dg');
                } elseif ($user->role_id === 3) {
                    return redirect('/entrepot');
                } elseif ($user->role_id === 4) {
                    return redirect('/agent');
                } elseif ($user->role_id === 5) {
                    return redirect('/drh');
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
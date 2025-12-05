<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si c'est une requête AJAX pour obtenir un token CSRF
        if ($request->is('csrf-token') && $request->ajax()) {
            return response()->json([
                'token' => csrf_token()
            ]);
        }

        // Pour les autres requêtes, continuer normalement
        return $next($request);
    }
}

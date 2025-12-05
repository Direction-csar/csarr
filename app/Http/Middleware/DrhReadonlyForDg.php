<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrhReadonlyForDg
{
    /**
     * Allow DG to view-only on DRH routes. Admin and DRH keep full rights.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        // If user is DG, restrict to safe methods
        if ($user->role === 'dg') {
            $method = strtoupper($request->getMethod());
            $allowed = ['GET', 'HEAD', 'OPTIONS'];
            if (!in_array($method, $allowed, true)) {
                return redirect()->back()->withErrors([
                    'email' => "Accès en lecture seule pour le DG sur la section DRH."
                ]);
            }
        }

        return $next($request);
    }
}

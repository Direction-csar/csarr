<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Logger les actions de connexion/déconnexion
        if ($request->route()) {
            $routeName = $request->route()->getName();
            
            if ($routeName === 'admin.login.submit' && $response->getStatusCode() === 302) {
                // Connexion réussie (redirection)
                $this->logLogin($request);
            } elseif ($routeName === 'admin.logout') {
                // Déconnexion
                $this->logLogout($request);
            }
        }

        return $response;
    }

    protected function logLogin(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'model_type' => null,
                'model_id' => null,
                'description' => 'Connexion réussie à l\'administration CSAR',
                'old_values' => null,
                'new_values' => [
                    'login_time' => now()->toDateTimeString(),
                    'login_method' => 'admin_panel'
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }

    protected function logLogout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'logout',
                'model_type' => null,
                'model_id' => null,
                'description' => 'Déconnexion de l\'administration CSAR',
                'old_values' => null,
                'new_values' => [
                    'logout_time' => now()->toDateTimeString(),
                    'session_duration' => 'calculé automatiquement'
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }
}


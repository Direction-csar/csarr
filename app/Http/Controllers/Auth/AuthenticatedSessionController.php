<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Déterminer l'interface basée sur l'URL
        $path = request()->path();
        
        if (str_contains($path, 'drh')) {
            return view('auth.drh-login');
        }
        if (str_contains($path, 'admin')) {
            return view('auth.admin-login');
        } elseif (str_contains($path, 'dg')) {
            return view('auth.dg-login');
        } elseif (str_contains($path, 'responsable') || str_contains($path, 'entrepot')) {
            return view('auth.responsable-login');
        } elseif (str_contains($path, 'agent')) {
            return view('auth.agent-login');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Déterminer l'interface à partir de l'URL
        $path = request()->path();
        $user = Auth::user();
        
        // Récupérer le nom du rôle depuis la relation
        $roleName = $user->role_id ? \App\Models\Role::find($user->role_id)->name : 'agent';
        
        // Rediriger selon l'interface demandée
        if (str_contains($path, 'drh')) {
            if (in_array($roleName, ['drh','admin','dg'])) {
                return redirect()->intended('/drh');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => "Vous n'avez pas les permissions pour accéder à l'interface DRH."]);
            }
        } elseif (str_contains($path, 'dg')) {
            if ($roleName === 'dg' || $roleName === 'admin') {
                return redirect()->intended('/dg');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Vous n\'avez pas les permissions pour accéder à l\'interface DG.']);
            }
        } elseif (str_contains($path, 'entrepot') || str_contains($path, 'responsable')) {
            if ($roleName === 'responsable' || $roleName === 'admin') {
                return redirect()->intended('/entrepot');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Vous n\'avez pas les permissions pour accéder à l\'interface Entrepôt.']);
            }
        } elseif (str_contains($path, 'agent')) {
            if ($roleName === 'agent' || $roleName === 'admin') {
                return redirect()->intended('/agent');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Vous n\'avez pas les permissions pour accéder à l\'interface Agent.']);
            }
        } elseif (str_contains($path, 'admin')) {
            if ($roleName === 'admin') {
                return redirect()->intended('/admin');
            } else {
                Auth::logout();
                return redirect()->back()->withErrors(['email' => 'Vous n\'avez pas les permissions pour accéder à l\'interface Admin.']);
            }
        }

        // Fallback: rediriger selon le rôle
        if ($user->role) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'dg':
                    return redirect()->intended('/dg/dashboard');
                case 'responsable':
                    return redirect()->intended('/entrepot/dashboard');
                case 'agent':
                    return redirect()->intended('/agent/dashboard');
                default:
                    return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Déterminer l'interface à partir de l'URL
        $path = request()->path();
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Rediriger vers la page de connexion appropriée
        if (str_contains($path, 'dg')) {
            return redirect()->route('dg.login')->with('success', 'Déconnexion réussie');
        } elseif (str_contains($path, 'drh')) {
            return redirect()->route('drh.login')->with('success', 'Déconnexion réussie');
        } elseif (str_contains($path, 'entrepot') || str_contains($path, 'responsable')) {
            return redirect()->route('responsable.login')->with('success', 'Déconnexion réussie');
        } elseif (str_contains($path, 'agent')) {
            return redirect()->route('agent.login')->with('success', 'Déconnexion réussie');
        } elseif (str_contains($path, 'admin')) {
            return redirect()->route('admin.login')->with('success', 'Déconnexion réussie');
        }

        return redirect('/');
    }
} 
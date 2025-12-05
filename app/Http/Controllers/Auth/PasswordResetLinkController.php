<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(Request $request): View
    {
        // Détecter d'où vient l'utilisateur pour le rediriger vers la bonne page de connexion
        $referer = $request->header('referer');
        $backUrl = '/admin/login'; // Par défaut
        
        if ($referer) {
            if (str_contains($referer, '/admin/login')) {
                $backUrl = '/admin/login';
            } elseif (str_contains($referer, '/dg/login')) {
                $backUrl = '/dg/login';
            } elseif (str_contains($referer, '/agent/login')) {
                $backUrl = '/agent/login';
            } elseif (str_contains($referer, '/entrepot/login')) {
                $backUrl = '/entrepot/login';
            } elseif (str_contains($referer, '/login')) {
                $backUrl = '/login';
            }
        }
        
        // Utiliser la vue appropriée selon le type d'utilisateur
        $view = 'auth.forgot-password'; // Vue par défaut
        
        if ($referer) {
            if (str_contains($referer, '/admin/login')) {
                $view = 'auth.admin-forgot-password';
            } elseif (str_contains($referer, '/dg/login')) {
                $view = 'auth.dg-forgot-password';
            } elseif (str_contains($referer, '/agent/login')) {
                $view = 'auth.agent-forgot-password';
            } elseif (str_contains($referer, '/entrepot/login')) {
                $view = 'auth.responsable-forgot-password';
            }
        }
        
        return view($view, compact('backUrl'));
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
} 
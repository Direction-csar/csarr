<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Headers de sécurité
        $securityHeaders = config('security.headers', []);

        // X-Frame-Options
        if (isset($securityHeaders['x_frame_options'])) {
            $response->headers->set('X-Frame-Options', $securityHeaders['x_frame_options']);
        }

        // X-Content-Type-Options
        if (isset($securityHeaders['x_content_type_options'])) {
            $response->headers->set('X-Content-Type-Options', $securityHeaders['x_content_type_options']);
        }

        // X-XSS-Protection
        if (isset($securityHeaders['x_xss_protection'])) {
            $response->headers->set('X-XSS-Protection', $securityHeaders['x_xss_protection']);
        }

        // Referrer-Policy
        if (isset($securityHeaders['referrer_policy'])) {
            $response->headers->set('Referrer-Policy', $securityHeaders['referrer_policy']);
        }

        // Content-Security-Policy
        if (isset($securityHeaders['content_security_policy'])) {
            $response->headers->set('Content-Security-Policy', $securityHeaders['content_security_policy']);
        }

        // Strict-Transport-Security (HSTS)
        if (config('security.https.hsts_enabled', false) && $request->isSecure()) {
            $maxAge = config('security.https.hsts_max_age', 31536000);
            $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains");
        }

        // Permissions-Policy
        $response->headers->set('Permissions-Policy', 
            'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), speaker=()'
        );

        // Cache-Control pour les pages sensibles
        if ($request->is('admin/*') || $request->is('dg/*') || $request->is('entrepot/*') || $request->is('agent/*') || $request->is('drh/*')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}

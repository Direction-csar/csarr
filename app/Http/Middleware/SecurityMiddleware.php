<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\Response;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        
        // Vérifier si l'IP est bloquée
        if (SecurityService::isIpBlocked($ip)) {
            SecurityService::logSecurityAlert('blocked_ip_access', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'error' => 'Accès temporairement bloqué',
                'message' => 'Trop de tentatives échouées. Veuillez réessayer plus tard.'
            ], 429);
        }
        
        // Vérifier les patterns suspects dans l'URL
        if ($this->isSuspiciousUrl($request->fullUrl())) {
            SecurityService::logSecurityAlert('suspicious_url_access', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'error' => 'Accès non autorisé'
            ], 403);
        }
        
        // Vérifier les headers suspects
        if ($this->hasSuspiciousHeaders($request)) {
            SecurityService::logSecurityAlert('suspicious_headers', [
                'ip' => $ip,
                'headers' => $request->headers->all(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        return $next($request);
    }
    
    /**
     * Vérifier si l'URL contient des patterns suspects
     */
    private function isSuspiciousUrl($url)
    {
        $suspiciousPatterns = [
            '/\.\./',           // Directory traversal
            '/<script/i',       // XSS attempts
            '/union\s+select/i', // SQL injection
            '/drop\s+table/i',  // SQL injection
            '/exec\s*\(/i',     // Command injection
            '/eval\s*\(/i',     // Code injection
            '/base64_decode/i', // Base64 obfuscation
            '/javascript:/i',   // JavaScript protocol
            '/data:/i',         // Data URI
            '/vbscript:/i',     // VBScript protocol
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Vérifier les headers suspects
     */
    private function hasSuspiciousHeaders($request)
    {
        $suspiciousHeaders = [
            'X-Forwarded-For' => '/\d+\.\d+\.\d+\.\d+,\s*\d+\.\d+\.\d+\.\d+/', // Multiple IPs
            'User-Agent' => '/(bot|crawler|spider|scraper)/i', // Bot detection
        ];
        
        foreach ($suspiciousHeaders as $header => $pattern) {
            $value = $request->header($header);
            if ($value && preg_match($pattern, $value)) {
                return true;
            }
        }
        
        return false;
    }
}


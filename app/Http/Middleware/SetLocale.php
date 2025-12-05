<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Langues supportées
        $supportedLocales = ['fr', 'en'];
        
        // Récupérer la langue depuis l'URL, la session ou les préférences du navigateur
        $locale = $request->segment(1);
        
        // Si la langue n'est pas dans l'URL, vérifier la session
        if (!in_array($locale, $supportedLocales)) {
            $locale = Session::get('locale', $this->getBrowserLocale($request));
        }
        
        // Fallback vers le français si la langue n'est pas supportée
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'fr';
        }
        
        // Définir la locale pour l'application
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return $next($request);
    }
    
    /**
     * Détecter la langue du navigateur
     */
    private function getBrowserLocale(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if ($acceptLanguage) {
            $languages = explode(',', $acceptLanguage);
            foreach ($languages as $language) {
                $locale = substr(trim($language), 0, 2);
                if (in_array($locale, ['fr', 'en'])) {
                    return $locale;
                }
            }
        }
        
        return 'fr'; // Fallback vers le français
    }
}
<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Afficher la politique de confidentialité
     */
    public function privacy()
    {
        return view('public.politique');
    }

    /**
     * Afficher les conditions d'utilisation
     */
    public function terms()
    {
        return view('public.conditions');
    }

    /**
     * Afficher les mentions légales
     */
    public function legalNotice()
    {
        return view('public.mentions-legales');
    }

    /**
     * Gérer les préférences de cookies
     */
    public function cookiePreferences()
    {
        return view('public.cookie-preferences');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CsrfController extends Controller
{
    /**
     * Retourne un nouveau token CSRF
     */
    public function getToken(Request $request)
    {
        return response()->json([
            'token' => csrf_token(),
            'timestamp' => time()
        ]);
    }
}

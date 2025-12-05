<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'CSAR Platform is working!',
            'timestamp' => now(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
        ]);
    }

    public function simple()
    {
        return '<h1>CSAR Platform - Test Page</h1><p>Laravel is working correctly!</p>';
    }
} 
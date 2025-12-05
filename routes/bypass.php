<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\DGLoginController;
use App\Http\Controllers\Auth\AgentLoginController;
use App\Http\Controllers\Auth\ResponsableLoginController;

// Routes de contournement pour éviter l'erreur 419
Route::group(['prefix' => 'bypass', 'middleware' => []], function () {
    
    // Page de connexion admin bypass
    Route::get('/admin-login', function () {
        return view('auth.admin-login-bypass');
    })->name('bypass.admin.login');
    
    // Page de connexion DG bypass
    Route::get('/dg-login', function () {
        return view('auth.dg-login-bypass');
    })->name('bypass.dg.login');
    
    // Page de connexion Agent bypass
    Route::get('/agent-login', function () {
        return view('auth.agent-login-bypass');
    })->name('bypass.agent.login');
    
    // Page de connexion Responsable bypass
    Route::get('/responsable-login', function () {
        return view('auth.responsable-login-bypass');
    })->name('bypass.responsable.login');
    
    // Traitement des connexions sans CSRF
    Route::post('/admin-login', [AdminLoginController::class, 'login'])->withoutMiddleware(['web']);
    Route::post('/dg-login', [DGLoginController::class, 'login'])->withoutMiddleware(['web']);
    Route::post('/agent-login', [AgentLoginController::class, 'login'])->withoutMiddleware(['web']);
    Route::post('/responsable-login', [ResponsableLoginController::class, 'login'])->withoutMiddleware(['web']);
});

// Routes de redirection directe
Route::get('/admin-direct', function () {
    return redirect('/admin/dashboard');
})->name('admin.direct');

Route::get('/dg-direct', function () {
    return redirect('/dg/dashboard');
})->name('dg.direct');

Route::get('/agent-direct', function () {
    return redirect('/agent/dashboard');
})->name('agent.direct');

Route::get('/responsable-direct', function () {
    return redirect('/entrepot/dashboard');
})->name('responsable.direct');
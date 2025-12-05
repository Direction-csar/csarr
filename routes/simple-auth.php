<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Route de connexion simple
Route::post('/simple-login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    
    // Vérifier les identifiants
    $user = User::where('email', $email)->first();
    
    if ($user && Hash::check($password, $user->password)) {
        // Connexion réussie
        Auth::login($user);
        $request->session()->regenerate();
        
        // Rediriger vers le dashboard approprié selon le rôle
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'dg':
                return redirect('/dg/dashboard');
            case 'responsable':
                return redirect('/entrepot/dashboard');
            case 'agent':
                return redirect('/agent/dashboard');
            default:
                return redirect('/admin/dashboard');
        }
    } else {
        // Connexion échouée
        return back()->withErrors(['email' => 'Identifiants incorrects']);
    }
})->name('simple.login');

// Route pour afficher le formulaire de connexion simple
Route::get('/simple-login', function () {
    return view('simple-login');
})->name('simple.login.form');

// Route de connexion universelle
Route::post('/universal-login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');
    $role = $request->input('role', 'admin');
    
    // Vérifier les identifiants
    $user = User::where('email', $email)->first();
    
    if ($user && Hash::check($password, $user->password)) {
        // Vérifier que le rôle correspond
        if ($user->role === $role || $user->role === 'admin') {
            // Connexion réussie
            Auth::login($user);
            
            // Rediriger vers le dashboard approprié selon le rôle
            switch ($role) {
                case 'admin':
                    return redirect('/admin/dashboard');
                case 'dg':
                    return redirect('/dg/dashboard');
                case 'responsable':
                    return redirect('/responsable/dashboard');
                case 'agent':
                    return redirect('/agent/dashboard');
                default:
                    return redirect('/admin/dashboard');
            }
        } else {
            return back()->withErrors(['email' => 'Vous n\'avez pas les permissions pour cette interface.']);
        }
    } else {
        // Connexion échouée
        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }
})->name('universal.login');

// Route pour afficher le formulaire de connexion universel
Route::get('/universal-login', function () {
    return view('universal-login');
})->name('universal.login.form');

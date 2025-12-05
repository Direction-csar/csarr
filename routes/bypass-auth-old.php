<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Route de connexion qui contourne CSRF
Route::post('/bypass-login', function (Request $request) {
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
        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }
})->name('bypass.login');

// Route pour afficher le formulaire de connexion sans CSRF
Route::get('/bypass-login', function () {
    return view('bypass-login');
})->name('bypass.login.form');

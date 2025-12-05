<?php
// Routes de connexion simplifiées sans middleware CSRF

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Route de test simple
Route::get('/test-login', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'Route de test accessible',
        'timestamp' => now()
    ]);
});

// Route de connexion admin simplifiée
Route::post('/simple-admin-login', function(Request $request) {
    try {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], 400);
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }
        
        if ($user->role !== 'admin' && $user->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Permissions insuffisantes'
            ], 403);
        }
        
        // Connexion manuelle
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'redirect' => '/admin'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
});

// Route de connexion DG simplifiée
Route::post('/simple-dg-login', function(Request $request) {
    try {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], 400);
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }
        
        if ($user->role !== 'dg' && $user->role_id !== 2) {
            return response()->json([
                'success' => false,
                'message' => 'Permissions insuffisantes'
            ], 403);
        }
        
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Connexion DG réussie',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'redirect' => '/dg/dashboard'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
});

// Route de connexion Agent simplifiée
Route::post('/simple-agent-login', function(Request $request) {
    try {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], 400);
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }
        
        if ($user->role !== 'agent' && $user->role_id !== 4) {
            return response()->json([
                'success' => false,
                'message' => 'Permissions insuffisantes'
            ], 403);
        }
        
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Connexion Agent réussie',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'redirect' => '/agent'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
});

// Route de connexion Responsable simplifiée
Route::post('/simple-responsable-login', function(Request $request) {
    try {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Email et mot de passe requis'
            ], 400);
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        
        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect'
            ], 401);
        }
        
        if ($user->role !== 'responsable' && $user->role_id !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Permissions insuffisantes'
            ], 403);
        }
        
        Auth::login($user);
        
        return response()->json([
            'success' => true,
            'message' => 'Connexion Responsable réussie',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'redirect' => '/entrepot'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
});











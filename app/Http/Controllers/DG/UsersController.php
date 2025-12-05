<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index()
    {
        try {
            // Récupérer les utilisateurs avec pagination
            $users = User::latest()->paginate(10);
            
            // Statistiques des utilisateurs
            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'admins' => User::where('role', 'admin')->count(),
                'inactive' => User::where('is_active', false)->count(),
            ];
            
            Log::info('Accès à la gestion des utilisateurs DG', [
                'user_id' => auth()->id(),
                'timestamp' => Carbon::now()
            ]);
            
            return view('dg.users.index', compact('users', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des utilisateurs DG', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement des utilisateurs');
        }
    }
    
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
            Log::info('Consultation utilisateur DG', [
                'user_id' => auth()->id(),
                'viewed_user_id' => $id,
                'timestamp' => Carbon::now()
            ]);
            
            return view('dg.users.show', compact('user'));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la consultation de l\'utilisateur DG', [
                'user_id' => auth()->id(),
                'viewed_user_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Utilisateur non trouvé');
        }
    }
}
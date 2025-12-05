<?php

namespace App\Http\Controllers\DRH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Afficher le profil DRH
     */
    public function index()
    {
        try {
            $user = auth()->user();
            return view('drh.profile.index', compact('user'));

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH ProfileController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement du profil');
        }
    }

    /**
     * Mettre à jour le profil DRH
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20'
            ]);

            $user = auth()->user();
            $user->update($request->only(['name', 'email', 'phone']));

            return redirect()->back()->with('success', 'Profil mis à jour avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur dans DRH ProfileController@update', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du profil');
        }
    }
}


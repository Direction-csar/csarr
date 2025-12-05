@extends('layouts.responsable')

@section('title', 'Mon Profil - Responsable Entrepôt')
@section('page-title', 'Mon Profil')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Informations personnelles</h2>
        <p class="card-subtitle">Gérez vos informations de profil</p>
    </div>
    
    <form action="{{ route('responsable.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Informations de base -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    👤 Informations de base
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label for="name" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Nom complet *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ $user->name }}"
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('name')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Email *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ $user->email }}"
                               required
                               readonly
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #f9fafb; color: #6b7280;">
                        <div style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">
                            L'email ne peut pas être modifié
                        </div>
                    </div>
                    
                    <div>
                        <label for="phone" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Téléphone *
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ $user->phone }}"
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('phone')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Informations professionnelles -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    💼 Informations professionnelles
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label for="warehouse" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Entrepôt assigné
                        </label>
                        <input type="text" 
                               id="warehouse" 
                               name="warehouse" 
                               value="{{ $warehouse ? $warehouse->name : 'Aucun entrepôt assigné' }}"
                               readonly
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #f9fafb; color: #6b7280;">
                        <div style="color: #6b7280; font-size: 0.875rem; margin-top: 0.25rem;">
                            Assigné par l'administration
                        </div>
                    </div>
                    
                    <div>
                        <label for="role" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Rôle
                        </label>
                        <input type="text" 
                               id="role" 
                               name="role" 
                               value="Responsable d'entrepôt"
                               readonly
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #f9fafb; color: #6b7280;">
                    </div>
                    
                    <div>
                        <label for="address" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Adresse *
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  required
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; resize: vertical;">{{ $user->address }}</textarea>
                        @error('address')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn-primary">
                💾 Sauvegarder les modifications
            </button>
            
            <button type="button" class="btn-secondary" onclick="resetForm()">
                🔄 Réinitialiser
            </button>
        </div>
    </form>
</div>

<!-- Changement de mot de passe -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🔐 Sécurité</h2>
        <p class="card-subtitle">Modifier votre mot de passe</p>
    </div>
    
    <form action="{{ route('responsable.profile.password') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <div>
                <label for="current_password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                    Mot de passe actuel *
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                @error('current_password')
                    <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label for="new_password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                    Nouveau mot de passe *
                </label>
                <input type="password" 
                       id="new_password" 
                       name="new_password" 
                       required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                @error('new_password')
                    <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label for="new_password_confirmation" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                    Confirmer le nouveau mot de passe *
                </label>
                <input type="password" 
                       id="new_password_confirmation" 
                       name="new_password_confirmation" 
                       required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
            </div>
        </div>
        
        <button type="submit" class="btn-primary">
            🔐 Changer le mot de passe
        </button>
    </form>
</div>

<!-- Statistiques du responsable -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 Mes statistiques</h2>
        <p class="card-subtitle">Vos performances en tant que responsable d'entrepôt</p>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; text-align: center;">
            <div style="font-size: 2rem; color: #059669; margin-bottom: 0.5rem;">📦</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">156</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Mouvements ce mois</div>
        </div>
        
        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; text-align: center;">
            <div style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;">✅</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">98%</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Précision des données</div>
        </div>
        
        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; text-align: center;">
            <div style="font-size: 2rem; color: #7c3aed; margin-bottom: 0.5rem;">⏱️</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">2.3h</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Temps de réponse moyen</div>
        </div>
        
        <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; text-align: center;">
            <div style="font-size: 2rem; color: #dc2626; margin-bottom: 0.5rem;">🚨</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">3</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Alertes gérées</div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    // Réinitialiser les champs modifiables
    document.getElementById('name').value = '{{ $user->name }}';
    document.getElementById('phone').value = '{{ $user->phone }}';
    document.getElementById('address').value = '{{ $user->address }}';
    
    // Réinitialiser les champs de mot de passe
    document.getElementById('current_password').value = '';
    document.getElementById('new_password').value = '';
    document.getElementById('new_password_confirmation').value = '';
}
</script>
@endsection 
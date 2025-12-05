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
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('email')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Téléphone
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ $user->phone }}"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('phone')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="address" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Adresse
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3"
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; resize: vertical;">{{ $user->address }}</textarea>
                        @error('address')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Informations de connexion -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    🔐 Informations de connexion
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="padding: 1rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.375rem;">
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                            <strong>Rôle :</strong> Responsable Entrepôt
                        </p>
                        <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0 0 0;">
                            <strong>Dernière connexion :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    
                    <div>
                        <a href="#change-password" 
                           onclick="document.getElementById('password-section').style.display = 'block'; this.style.display = 'none';"
                           style="display: inline-block; padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500;">
                            🔑 Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Section changement de mot de passe -->
        <div id="password-section" style="display: none; margin-top: 2rem; padding: 1.5rem; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                🔑 Changer le mot de passe
            </h3>
            
            <form action="{{ route('responsable.profile.password') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
                @csrf
                @method('PUT')
                
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
                    <label for="password" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        Nouveau mot de passe *
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           minlength="8"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                    @error('password')
                        <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                        Confirmer le nouveau mot de passe *
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           minlength="8"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                </div>
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" 
                            style="padding: 0.75rem 1.5rem; background-color: #059669; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;">
                        Mettre à jour le mot de passe
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('password-section').style.display = 'none'; document.querySelector('a[href=\'#change-password\']').style.display = 'inline-block';"
                            style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Boutons d'action -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
            <button type="submit" 
                    style="padding: 0.75rem 2rem; background-color: #059669; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer;">
                💾 Mettre à jour le profil
            </button>
            <a href="{{ route('responsable.dashboard') }}" 
               style="display: inline-block; padding: 0.75rem 2rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 0.375rem; font-weight: 500;">
                ← Retour au tableau de bord
            </a>
        </div>
    </form>
</div>

@if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background-color: #059669; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 1000;">
        ✅ {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('[style*="position: fixed"]').style.display = 'none';
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div style="position: fixed; top: 20px; right: 20px; background-color: #dc2626; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); z-index: 1000;">
        ❌ {{ session('error') }}
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('[style*="position: fixed"]').style.display = 'none';
        }, 3000);
    </script>
@endif
@endsection

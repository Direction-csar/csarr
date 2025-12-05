@extends('layouts.public')

@section('title', 'Test Formulaire de Demande')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1>🧪 TEST - Formulaire de Demande Simplifié</h1>
    
    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #dc2626; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <h4 style="color: #dc2626; margin: 0 0 10px 0;">Erreurs de validation :</h4>
            <ul style="margin: 0; color: #dc2626;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORMULAIRE ULTRA-SIMPLE SANS JAVASCRIPT -->
    <form method="POST" action="{{ route('demande.store') }}" style="background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        @csrf
        
        <!-- Type de demande fixé -->
        <input type="hidden" name="type_demande" value="aide_alimentaire">
        
        <h2 style="color: #0284c7; margin-bottom: 20px;">Aide Alimentaire - Test</h2>
        
        <!-- Informations de base -->
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Nom *</label>
            <input type="text" name="nom" value="{{ old('nom', 'Test') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Prénom *</label>
            <input type="text" name="prenom" value="{{ old('prenom', 'Utilisateur') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Email *</label>
            <input type="email" name="email" value="{{ old('email', 'test@example.com') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Téléphone *</label>
            <input type="text" name="telephone" value="{{ old('telephone', '+221123456789') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Objet *</label>
            <input type="text" name="objet" value="{{ old('objet', 'Demande d\'aide alimentaire test') }}" required 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: 600; margin-bottom: 5px;">Description *</label>
            <textarea name="description" required rows="4" 
                      style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">{{ old('description', 'Test de soumission du formulaire de demande d\'aide alimentaire.') }}</textarea>
        </div>
        
        <!-- Géolocalisation simplifiée -->
        <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
            <h3 style="margin: 0 0 10px 0; color: #374151;">Localisation (pour aide alimentaire)</h3>
            
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Adresse *</label>
                <input type="text" name="address" value="{{ old('address', 'Dakar, Sénégal') }}" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 10px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Région *</label>
                <input type="text" name="region" value="{{ old('region', 'Dakar') }}" required 
                       style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            
            <!-- Coordonnées GPS optionnelles -->
            <input type="hidden" name="latitude" value="14.73396">
            <input type="hidden" name="longitude" value="-17.31781">
        </div>
        
        <!-- Consentement -->
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; font-weight: 500;">
                <input type="checkbox" name="consentement" required style="margin-right: 8px;" {{ old('consentement') ? 'checked' : '' }}> 
                J'accepte que mes données soient utilisées pour le traitement de ma demande par le CSAR.
            </label>
        </div>
        
        <!-- Boutons de test -->
        <div style="text-align: center;">
            <button type="submit" style="background: #16a34a; color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; margin-right: 10px;">
                🚀 ENVOYER TEST
            </button>
            
            <a href="{{ route('demande.create') }}?type=aide_alimentaire" style="background: #6b7280; color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 600;">
                ← Retour au formulaire normal
            </a>
        </div>
    </form>
    
    <div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 20px;">
        <h3 style="margin: 0 0 10px 0; color: #92400e;">ℹ️ Informations de test</h3>
        <p style="margin: 0; color: #92400e; font-size: 14px;">
            Ce formulaire de test contient des valeurs pré-remplies et aucun JavaScript complexe. 
            Il permet de tester directement la soumission vers l'API Laravel.
        </p>
    </div>
</div>

<script>
console.log('🧪 FORMULAIRE DE TEST CHARGÉ');
console.log('Action du formulaire:', document.querySelector('form').action);
console.log('Méthode:', document.querySelector('form').method);
</script>
@endsection










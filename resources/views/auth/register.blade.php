@extends('layouts.auth')

@section('title', 'Inscription - CSAR')

@section('content')
<div class="auth-card">
    <!-- Logo et titre -->
    <div class="auth-header">
        <img src="{{ asset('images/logos/LOGO CSAR vectoriel-01.png') }}" alt="Logo CSAR" class="auth-logo">
        <h1 class="auth-title">Créer un compte</h1>
        <p class="auth-subtitle">Commissariat à la Sécurité Alimentaire et à la Résilience</p>
    </div>
    
    <!-- Formulaire d'inscription -->
    <form action="{{ route('register') }}" method="POST">
        @csrf
        
        <!-- Nom complet -->
        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input id="name" name="name" type="text" required 
                   class="form-input @error('name') error @enderror"
                   placeholder="Votre nom complet"
                   value="{{ old('name') }}">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">Adresse email</label>
            <input id="email" name="email" type="email" required 
                   class="form-input @error('email') error @enderror"
                   placeholder="votre.email@csar.sn"
                   value="{{ old('email') }}">
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Mot de passe -->
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" name="password" type="password" required 
                   class="form-input @error('password') error @enderror"
                   placeholder="Votre mot de passe">
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Confirmation du mot de passe -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required 
                   class="form-input"
                   placeholder="Confirmez votre mot de passe">
        </div>

        <!-- Bouton d'inscription -->
        <button type="submit" class="submit-button">
            Créer mon compte
        </button>
    </form>
    
    <!-- Lien de connexion -->
    <div class="auth-footer">
        <p>Déjà un compte ?</p>
        <a href="{{ route('login') }}">Se connecter</a>
    </div>
</div>
@endsection 
@extends('layouts.admin')

@section('title', 'Nouvel Utilisateur')
@section('page-title', 'Créer un Nouvel Utilisateur')

@section('content')
<div class="container-fluid px-3">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">👤 Nouvel Utilisateur</h1>
                        <p class="text-muted mb-0 small">Créer un nouvel utilisateur pour le système CSAR</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- Informations personnelles -->
                        <div class="col-lg-6 mb-3">
                            <h6 class="fw-bold mb-3">👤 Informations Personnelles</h6>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-bold">Nom complet *</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label small fw-bold">Email *</label>
                                <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label small fw-bold">Téléphone *</label>
                                <input type="tel" class="form-control form-control-sm" id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                        
                        <!-- Rôle et statut -->
                        <div class="col-lg-6 mb-3">
                            <h6 class="fw-bold mb-3">🔐 Rôle et Accès</h6>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label small fw-bold">Rôle *</label>
                                <select class="form-select form-select-sm" id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="dg" {{ old('role') == 'dg' ? 'selected' : '' }}>Directeur Général</option>
                                    <option value="responsable" {{ old('role') == 'responsable' ? 'selected' : '' }}>Responsable</option>
                                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="status" class="form-label small fw-bold">Statut *</label>
                                <select class="form-select form-select-sm" id="status" name="status" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="actif" {{ old('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mot de passe -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold mb-3">🔑 Mot de Passe</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label small fw-bold">Mot de passe *</label>
                                    <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                                    <div class="form-text small">Minimum 8 caractères</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label small fw-bold">Confirmer le mot de passe *</label>
                                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save me-1"></i>Créer l'utilisateur
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
  /* Optimisation de l'espace */
  .container-fluid { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
  
  /* Cards plus compactes */
  .card-modern { margin-bottom: 0.5rem !important; }
  
  /* Réduction des marges */
  .row { margin-bottom: 0.5rem !important; }
  .mb-2 { margin-bottom: 0.5rem !important; }
  
  /* Textes plus compacts */
  .h4 { font-size: 1.25rem !important; }
  .h6 { font-size: 0.9rem !important; }
  
  /* Boutons plus petits et cliquables */
  .btn-sm { 
    padding: 0.25rem 0.5rem !important; 
    font-size: 0.8rem !important; 
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
  }
  
  /* Formulaires compacts */
  .form-control-sm, .form-select-sm {
    font-size: 0.8rem !important;
    padding: 0.25rem 0.5rem !important;
  }
  
  /* Labels */
  .form-label {
    font-size: 0.8rem !important;
    margin-bottom: 0.25rem !important;
  }
</style>
@endpush